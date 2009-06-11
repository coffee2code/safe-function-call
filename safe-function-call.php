<?php
/*
Plugin Name: Safe Function Call
Version: 1.0.1
Plugin URI: http://coffee2code.com/wp-plugins/safe-function-call
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Safely call functions that may not be available (for example, from within a template, calling a function that is provided by a plugin that may be deactivated).

Compatible with WordPress 1.5+, 2.0+, 2.1+, 2.2+, 2.3+, 2.5+, 2.6+, 2.7+, 2.8+.

=>> Read the accompanying readme.txt file for more information.  Also, visit the plugin's homepage
=>> for more information and the latest updates

Installation:

1.  Download the file http://coffee2code.com/wp-plugins/safe-function-call.zip and unzip it into your 
/wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' admin menu in WordPress
3. Use any of the three functions (_sfc(), _sfce(), or _sfcm()) provided by this plugin as desired

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

	In the event you want to safely call a function and echo its value, you can use _sfce() like so:
	
	<?php _sfce('largest_city', 'Tx'); ?>
	
	Which is roughly equivalent to doing :
	
	<?php if function_exists('largest_city') { echo largest_city('Tx'); } ?>
	
*/

/*
Copyright (c) 2007-2009 by Scott Reilly (aka coffee2code)

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

/* This will safely invoke the function by the name of $function_name.  You can specify an arbitrary number of additional arguments
   that will get passed to $function_name().  If $function_name() does not exist, nothing is displayed and no error is generated. */
function _sfc( $function_name ) {
	if (!function_exists($function_name)) return;
	$args = array_slice(func_get_args(), 1);
	return call_user_func_array($function_name, $args);	
}

/* The same as _sfc() except that it echoes the return value of $function_name() before returning that value */
function _sfce( $function_name ) {
	if (!function_exists($function_name)) return;
	$args = func_get_args();
	$value = call_user_func_array('_sfc', $args);
	if ($value)
		echo $value;
	return $value;
}

/* The same as _sfc() except that it displays a message (the value of $message_if_missing) if $function_name() does not exist. */
function _sfcm( $function_name, $msg_if_missing = '' ) {
	if (!function_exists($function_name)) {
		if ($msg_if_missing) echo $msg_if_missing;
		return;
	}
	$args = array_slice(func_get_args(), 2);
	return call_user_func_array($function_name, $args);	
}

?>