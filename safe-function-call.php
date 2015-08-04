<?php
/**
 * Plugin Name: Safe Function Call
 * Version:     1.2.3
 * Plugin URI:  http://coffee2code.com/wp-plugins/safe-function-call/
 * Author:      Scott Reilly
 * Author URI:  http://coffee2code.com/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Description: Safely and easily call functions that may not be available (such as those provided by a plugin that gets deactivated).
 *
 * Compatible with WordPress 1.5 through 4.3+.
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/safe-function-call/
 *
 * @package Safe_Function_Call
 * @author  Scott Reilly
 * @version 1.2.3
 */

/*
	Copyright (c) 2007-2015 by Scott Reilly (aka coffee2code)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die();

if ( ! function_exists( '__sfc_is_valid_callback' ) ) {
	/**
	 * Safely invoke the function by the name of $callback.  Any additional
	 * arguments will get passed to $callback().  If the callback does
	 * not exist, nothing is displayed and no error is generated.
	 *
	 * @param callback $callback The function to call.
	 * @return bool True if callback is valid, false otherwise.
	 */
	function __sfc_is_valid_callback( $callback ) {
		if ( is_string( $callback ) ) {
			if ( ! function_exists( $callback ) ) {
				return false;
			}
		} elseif ( is_array( $callback ) && 2 === count( $callback ) ) {
			if ( ! method_exists( $callback[0], $callback[1] ) ) {
				return false;
			}
		} else {
			return false;
		}

		return true;
	}
}

if ( ! function_exists( '_sfc' ) ) :
	/**
	 * Safely invoke the function by the name of $callback and return the
	 * result. Any additional arguments will get passed to $callback(). If the
	 * callback does not exist, nothing is displayed and no error is generated.
	 *
	 * @param callback $callback The function to call.
	 * @return mixed If the callback exists as a function, returns whatever that function returns. Otherwise, returns nothing.
	 */
	function _sfc( $callback ) {
		if ( __sfc_is_valid_callback( $callback ) ) {
			$args = array_slice( func_get_args(), 1 );
			return call_user_func_array( $callback, $args );
		}
	}
endif;


if ( ! function_exists( '_sfce' ) ) :
	/**
	 * Safely invoke the function by the name of $callback and echo and return
	 * the result. Any additional arguments will get passed to it. If the callback
	 * does not exist, nothing is displayed and no error is generated.
	 *
	 * This function is the same as _sfc() except that it echoes the return value
	 * of the callback before returning that value.
	 *
	 * @param string $callback The function to call.
	 * @return mixed If the callback exists as a function, returns whatever that function returns. Otherwise, returns nothing.
	 */
	function _sfce( $callback ) {
		if ( __sfc_is_valid_callback( $callback ) ) {
			$args = func_get_args();
			$value = call_user_func_array( '_sfc', $args );
			if ( $value ) echo $value;
			return $value;
		}
	}
endif;


if ( ! function_exists( '_sfcf' ) ) :
	/**
	 * Safely invoke the function by the name of $callback and return the
	 * result. Any additional arguments will get passed to it. If the callback
	 * does not exist, then an alternative function is called.
	 *
	 * This functions is the same as _sfc() except that if the intended function
	 * does not exist, then an alternative function is invoked (if it exists
	 * itself).
	 *
	 * @param string $callback The function to call.
	 * @param string $fallback_callback (optional) Name of the alternative function to call if the callback does not exist.
	 * @return mixed If the callback exists as a function, returns whatever that function returns. Otherwise, returns nothing.
	 */
	function _sfcf( $callback, $fallback_callback = null ) {
		$args = array_slice( func_get_args(), 2 );
		if ( ! __sfc_is_valid_callback( $callback ) ) {
			if ( __sfc_is_valid_callback( $fallback_callback ) ) {
				$args = array_merge( array( $callback ), $args );
				return call_user_func_array( $fallback_callback, $args );
			}
			return;
		}
		return call_user_func_array( $callback, $args );
	}
endif;


if ( ! function_exists( '_sfcm' ) ) :
	/**
	 * Safely invoke the function by the name of $callback and return the
	 * result. Any additional arguments will get passed to it. If the callback
	 * does not exist, then a message gets echoed.
	 *
	 * This functions is the same as _sfc() except that if the intended function
	 * does not exist, then a string is echoed (if provided).
	 *
	 * @param string $callback The function to call.
	 * @param string $msg_if_missing (optional) String to be echoed if $callback does not exist.
	 * @return mixed If $callback exists as a function, returns whatever that function returns. Otherwise, returns nothing.
	 */
	function _sfcm( $callback, $msg_if_missing = '' ) {
		if ( ! __sfc_is_valid_callback( $callback ) ) {
			if ( $msg_if_missing ) {
				echo $msg_if_missing;
			}
			return;
		}
		$args = array_slice( func_get_args(), 2 );
		return call_user_func_array( $callback, $args );
	}
endif;
