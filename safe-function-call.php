<?php
/**
 * @package Safe_Function_Call
 * @author Scott Reilly
 * @version 1.1
 */
/*
Plugin Name: Safe Function Call
Version: 1.1
Plugin URI: http://coffee2code.com/wp-plugins/safe-function-call
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Safely call functions that may not be available (for example, from within a template, calling a function that is provided by a plugin that may be deactivated).

Compatible with WordPress 1.5+, 2.0+, 2.1+, 2.2+, 2.3+, 2.5+, 2.6+, 2.7+, 2.8+, 2.9+.

=>> Read the accompanying readme.txt file for more information.  Also, visit the plugin's homepage
=>> for more information and the latest updates

Installation:

1.  Download the file http://coffee2code.com/wp-plugins/safe-function-call.zip and unzip it into your 
/wp-content/plugins/ directory (or install via the built-in WordPress plugin installer).
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Use any of the four functions (_sfc(), _sfce(), _sfsf(), or _sfcm()) provided by this plugin as desired

Usage:
	Assuming you had something like this in a template:

	<?php list_cities('Texas', 3); ?>

	If you deactivated the plugin that provided list_cities(), your site would generate an error when that template is accessed.

	You can instead use _sfc(), which is provided by this plugin to call other functions, like so:

	<?php _sfc('list_cities', 'Texas', 3); ?>

	That will simply do nothing if the list_cities() function is not available.

	If you'd rather display a message when the function does not exist, use _sfcm() instead, like so:

	<?php _sfcm('list_cities', 'The cities listing is temporarily disabled.', 'Texas', 3); ?>

	In this case, if list_cities() is not available, the text "The cities listing is temporarily disabled." will be displayed.

	If you'd rather call another function when the function does not exist, use _sfcf() instead, like so:

	<?php
		function unavailable_function_handler($function_name) { echo "The function $function_name is not available."; }
		_sfcf('nonexistent_function', 'unavailable_function_handler');
	?>

	In the event you want to safely call a function and echo its value, you can use _sfce() like so:

	<?php _sfce('largest_city', 'Tx'); ?>

	Which is roughly equivalent to doing :

	<?php if function_exists('largest_city') { echo largest_city('Tx'); } ?>

*/

/*
Copyright (c) 2007-2010 by Scott Reilly (aka coffee2code)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation 
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, 
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the 
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

/**
 * Safely invoke the function by the name of $function_name.  Any additional
 * arguments will get passed to $function_name().  If $function_name() does
 * not exist, nothing is displayed and no error is generated.
 *
 * @param string $function_name Name of the function to call
 * @return mixed If $function_name exists as a function, returns whatever that function returns. Otherwise, returns nothing.
 */
function _sfc( $function_name ) {
	if ( !function_exists($function_name) ) return;
	$args = array_slice(func_get_args(), 1);
	return call_user_func_array($function_name, $args);	
}

/**
 * Safely invoke the function by the name of $function_name and echo its return
 * value .  Any additional arguments will get passed to $function_name().  If
 * $function_name() does not exist, nothing is displayed and no error is
 * generated.
 *
 * This functions is the same as _sfc() except that it echoes the return value
 * of $function_name() before returning that value.
 *
 * @param string $function_name Name of the function to call
 * @return mixed If $function_name exists as a function, returns whatever that function returns. Otherwise, returns nothing.
 */
function _sfce( $function_name ) {
	if ( !function_exists($function_name) ) return;
	$args = func_get_args();
	$value = call_user_func_array('_sfc', $args);
	if ( $value )
		echo $value;
	return $value;
}

/**
 * Safely invoke the function by the name of $function_name.  Any additional
 * arguments will get passed to $function_name().  If $function_name() does
 * not exist, then an alternative function is called.
 *
 * This functions is the same as _sfc() except that if the intended function
 * does not exist, then an alternative function is invoked (if it exists
 * itself).
 *
 * @param string $function_name Name of the function to call
 * @param string $function_if_missing (optional) Name of the alternative function to call if $function_name does not exists
 * @return mixed If $function_name exists as a function, returns whatever that function returns. Otherwise, returns nothing.
 */
function _sfcf( $function_name, $function_if_missing = null ) {
	$args = array_slice(func_get_args(), 2);
	if ( !function_exists($function_name) ) {
		if ( function_exists($function_if_missing) ) {
			$args = array_merge(array($function_name), $args);
			return call_user_func_array($function_if_missing, $args);
		}
		return;
	}
	return call_user_func_array($function_name, $args);
}

/**
 * Safely invoke the function by the name of $function_name.  Any additional
 * arguments will get passed to $function_name().  If $function_name() does
 * not exist, then an alternative function is called with identical arguments.
 *
 * This functions is the same as _sfc() except that if the intended function
 * does not exist, then a string is echoed (if provided).
 *
 * @param string $function_name Name of the function to call
 * @param string $msg_if_missing (optional) String to be echoed if $function_name does not exist
 * @return mixed If $function_name exists as a function, returns whatever that function returns.  Otherwise, returns nothing.
 */
function _sfcm( $function_name, $msg_if_missing = '' ) {
	if ( !function_exists($function_name) ) {
		if ( $msg_if_missing ) echo $msg_if_missing;
		return;
	}
	$args = array_slice(func_get_args(), 2);
	return call_user_func_array($function_name, $args);
}

?>