<?php
/**
 * @package Safe_Function_Call
 * @author Scott Reilly
 * @version 1.1.4
 */
/*
Plugin Name: Safe Function Call
Version: 1.1.4
Plugin URI: http://coffee2code.com/wp-plugins/safe-function-call/
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Safely and easily call functions that may not be available (such as those provided by a plugin that gets deactivated).

Compatible with WordPress 1.5+, 2.0+, 2.1+, 2.2+, 2.3+, 2.5+, 2.6+, 2.7+, 2.8+, 2.9+, 3.0+, 3.1+, 3.2+.

=>> Read the accompanying readme.txt file for instructions and documentation.
=>> Also, visit the plugin's homepage for additional information and updates.
=>> Or visit: http://wordpress.org/extend/plugins/safe-function-call/

*/

/*
Copyright (c) 2007-2011 by Scott Reilly (aka coffee2code)

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

if ( ! function_exists( '_sfc' ) ) :
/**
 * Safely invoke the function by the name of $function_name.  Any additional
 * arguments will get passed to $function_name().  If $function_name() does
 * not exist, nothing is displayed and no error is generated.
 *
 * @param string $function_name Name of the function to call
 * @return mixed If $function_name exists as a function, returns whatever that function returns. Otherwise, returns nothing.
 */
function _sfc( $function_name ) {
	if ( ! function_exists( $function_name ) )
		return;
	$args = array_slice( func_get_args(), 1 );
	return call_user_func_array( $function_name, $args );
}
endif;


if ( ! function_exists( '_sfce' ) ) :
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
	if ( ! function_exists( $function_name ) )
		return;
	$args = func_get_args();
	$value = call_user_func_array( '_sfc', $args );
	if ( $value )
		echo $value;
	return $value;
}
endif;


if ( ! function_exists( '_sfcf' ) ) :
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
	$args = array_slice( func_get_args(), 2 );
	if ( ! function_exists( $function_name ) ) {
		if ( function_exists( $function_if_missing ) ) {
			$args = array_merge( array( $function_name ), $args );
			return call_user_func_array( $function_if_missing, $args );
		}
		return;
	}
	return call_user_func_array( $function_name, $args );
}
endif;


if ( ! function_exists( '_sfcm' ) ) :
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
	if ( ! function_exists( $function_name ) ) {
		if ( $msg_if_missing )
			echo $msg_if_missing;
		return;
	}
	$args = array_slice( func_get_args(), 2 );
	return call_user_func_array( $function_name, $args );
}
endif;

?>