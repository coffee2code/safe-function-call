<?php

defined( 'ABSPATH' ) or die();

function sfc_test_real_function( $arg1, $arg2 = '' ) {
	return "$arg1 + $arg2";
}

function sfc_test_fallback( $missing_callback, $arg1, $arg2 = '' ) {
	return "$arg1 / $arg2";
}

class Safe_Function_Call_Test extends WP_UnitTestCase {

	//
	//
	// HELPER FUNCTIONS
	//
	//


	 public function real_object_function( $arg1, $arg2 = '' ) {
		return "$arg2 + $arg1";
	}

	 public function fallback( $missing_callback, $arg1, $arg2 = '' ) {
		return "$arg1 * $arg2";
	}

	//
	//
	// TESTS
	//
	//


	/* _sfc() */

	public function test__sfc_on_nonexistent_function() {
		$this->assertEmpty( _sfc( 'doesnt_exist', 5, 'a' ) );
		$this->assertEmpty( apply_filters( '_sfc', 'doesnt_exist', 5, 'a' ) );
		$this->assertEmpty( _sfc( array( $this, 'fake_object_function' ), 5, 'a' ) );
		$this->assertEmpty( apply_filters( '_sfc', array( $this, 'fake_object_function' ), 5, 'a' ) );
	}

	public function test__sfc_on_existing_function() {
		$this->assertEquals( "5 + a", _sfc( 'sfc_test_real_function', 5, 'a' ) );
		$this->assertEquals( "5 + a", apply_filters( '_sfc', 'sfc_test_real_function', 5, 'a' ) );
		$this->assertEquals( "a + 5", _sfc( array( $this, 'real_object_function' ), 5, 'a' ) );
		$this->assertEquals( "a + 5", apply_filters( '_sfc', array( $this, 'real_object_function' ), 5, 'a' ) );
	}

	/* _sfce() */

	public function test__sfce_on_nonexistent_function() {
		$this->assertEmpty( _sfce( 'doesnt_exist', 5, 'a' ) );
		$this->assertEmpty( apply_filters( '_sfce', 'doesnt_exist', 5, 'a' ) );
		$this->assertEmpty( _sfce( array( $this, 'fake_object_function' ), 5, 'a' ) );
		$this->assertEmpty( apply_filters( '_sfce', array( $this, 'fake_object_function' ), 5, 'a' ) );
	}

	public function test__sfce_on_existing_function() {
		ob_start();
		_sfce( 'sfc_test_real_function', 5, 'a' );
		$out = ob_get_contents();
		ob_end_clean();

		$this->assertEquals( "5 + a", $out );

		ob_start();
		_sfce( array( $this, 'real_object_function' ), 5, 'a' );
		$out = ob_get_contents();
		ob_end_clean();

		$this->assertEquals( "a + 5", $out );

	}

	public function test__sfce_on_existing_function_using_filter_invocation() {
		ob_start();
		apply_filters( '_sfce', 'sfc_test_real_function', 5, 'a' );
		$out = ob_get_contents();
		ob_end_clean();

		$this->assertEquals( "5 + a", $out );

		ob_start();
		apply_filters( '_sfce', array( $this, 'real_object_function' ), 5, 'a' );
		$out = ob_get_contents();
		ob_end_clean();

		$this->assertEquals( "a + 5", $out );

	}

	/* _sfcf() */

	public function test__sfcf_on_nonexistent_function_with_no_fallback() {
		$this->assertEmpty( _sfcf( 'doesnt_exist' ) );
		$this->assertEmpty( apply_filters( '_sfcf', 'doesnt_exist' ) );
		$this->assertEmpty( _sfcf( array( $this, 'fake_object_function' ) ) );
		$this->assertEmpty( apply_filters( '_sfcf', array( $this, 'fake_object_function' ) ) );
	}

	public function test__sfcf_on_nonexistent_function_with_fallback() {
		$this->assertEquals( '5 / a', _sfcf( 'doesnt_exist', 'sfc_test_fallback', 5, 'a' ) );
		$this->assertEquals( '5 / a', apply_filters( '_sfcf', 'doesnt_exist', 'sfc_test_fallback', 5, 'a' ) );
		$this->assertEquals( 'a * 5', _sfcf( array( $this, 'fake_object_function' ), array( $this, 'fallback' ), 'a', 5 ) );
		$this->assertEquals( 'a * 5', apply_filters( '_sfcf', array( $this, 'fake_object_function' ), array( $this, 'fallback' ), 'a', 5 ) );
	}

	public function test__sfcf_on_existing_function() {
		$this->assertEquals( "5 + a", _sfcf( 'sfc_test_real_function', 'sfc_test_fallback', 5, 'a' ) );
		$this->assertEquals( "5 + a", apply_filters( '_sfcf', 'sfc_test_real_function', 'sfc_test_fallback', 5, 'a' ) );
		$this->assertEquals( "a + 5", _sfcf( array( $this, 'real_object_function' ), array( $this, 'fallback' ), 5, 'a' ) );
		$this->assertEquals( "a + 5", apply_filters( '_sfcf', array( $this, 'real_object_function' ), array( $this, 'fallback' ), 5, 'a' ) );
	}

	/* _sfcm() */

	public function test__sfcm_on_nonexistent_function() {
		$this->assertEmpty( _sfcm( 'doesnt_exist' ) );
		$this->assertEmpty( apply_filters( '_sfcm', 'doesnt_exist' ) );
		$this->assertEmpty( _sfcm( array( $this, 'fake_object_function' ) ) );
		$this->assertEmpty( apply_filters( '_sfcm', array( $this, 'fake_object_function' ) ) );
	}

	public function test__sfcm_on_nonexistent_function_with_message() {
		$msg = 'does not exist';

		ob_start();
		_sfcm( 'doesnt_exist', $msg, 4 );
		$out = ob_get_contents();
		ob_end_clean();

		$this->assertEquals( $msg, $out );

		ob_start();
		_sfcm( array( $this, 'fake_object_function' ), $msg );
		$out = ob_get_contents();
		ob_end_clean();

		$this->assertEquals( $msg, $out );
	}

	public function test__sfcm_on_nonexistent_function_with_message_using_filter_invocation() {
		$msg = 'does not exist';

		ob_start();
		apply_filters( '_sfcm', 'doesnt_exist', $msg, 4 );
		$out = ob_get_contents();
		ob_end_clean();

		$this->assertEquals( $msg, $out );

		ob_start();
		apply_filters( '_sfcm', array( $this, 'fake_object_function' ), $msg );
		$out = ob_get_contents();
		ob_end_clean();

		$this->assertEquals( $msg, $out );
	}

	public function test__sfcm_on_existing_function() {
		$msg = 'does not exist';

		$this->assertEquals( "5 + a", _sfcm( 'sfc_test_real_function', $msg, 5, 'a' ) );
		$this->assertEquals( "5 + a", apply_filters( '_sfcm', 'sfc_test_real_function', $msg, 5, 'a' ) );
		$this->assertEquals( "a + 5", _sfcm( array( $this, 'real_object_function' ), $msg, 5, 'a' ) );
		$this->assertEquals( "a + 5", apply_filters( '_sfcm', array( $this, 'real_object_function' ), $msg, 5, 'a' ) );
	}

	/**
	 * Provide an example of code that checks when filter invocation calls a
	 * function that is not available.
	 */
	public function test_filter_invocation_returns_function_name_when_callback_does_not_exist() {
		$x = apply_filters( '_sfc_dne', 'some_plugin_function', 'argument' );
		if ( $x !== 'some_plugin_function' ) {
			// Work with the value of $x here.
			$this->assertEmpty( "This branch won't be traversed during this test." );
		} else {
			// A theoretical function provided by the Safe Function Call plugin
			// isn't active, like due to plugin activation.
			$x = false; // Maybe set the variable to something that makes sense in this scenario. */
			$this->assertFalse( $x );
		}
	}

}
