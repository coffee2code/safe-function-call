<?php

function sfc_test_real_function( $arg1, $arg2 = '' ) {
	return "$arg1 + $arg2";
}

function sfc_test_fallback( $missing_callback, $arg1, $arg2 = '' ) {
	return "$arg1 / $arg2";
}

class Safe_Function_Call_Test extends WP_UnitTestCase {

	/**
	 *
	 * HELPER FUNCTIONS
	 *
	 */


	function real_object_function( $arg1, $arg2 = '' ) {
		return "$arg2 + $arg1";
	}

	function fallback( $missing_callback, $arg1, $arg2 = '' ) {
		return "$arg1 * $arg2";
	}

	/**
	 *
	 * TESTS
	 *
	 */


	function test__sfc_on_nonexistent_function() {
		$this->assertEmpty( _sfc( 'doesnt_exist', 5, 'a' ) );
		$this->assertEmpty( _sfc( array( $this, 'fake_object_function' ), 5, 'a' ) );
	}

	function test__sfc_on_existing_function() {
		$this->assertEquals( "5 + a", _sfc( 'sfc_test_real_function', 5, 'a' ) );
		$this->assertEquals( "a + 5", _sfc( array( $this, 'real_object_function' ), 5, 'a' ) );
	}

	function test__sfce_on_nonexistent_function() {
		$this->assertEmpty( _sfce( 'doesnt_exist', 5, 'a' ) );
		$this->assertEmpty( _sfce( array( $this, 'fake_object_function' ), 5, 'a' ) );
	}

	function test__sfce_on_existing_function() {
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

	function test__sfcf_on_nonexistent_function_with_no_fallback() {
		$this->assertEmpty( _sfcf( 'doesnt_exist' ) );
		$this->assertEmpty( _sfcf( array( $this, 'fake_object_function' ) ) );
	}

	function test__sfcf_on_nonexistent_function_with_fallback() {
		$this->assertEquals( '5 / a', _sfcf( 'doesnt_exist', 'sfc_test_fallback', 5, 'a' ) );
		$this->assertEquals( 'a * 5', _sfcf( array( $this, 'fake_object_function' ), array( $this, 'fallback' ), 'a', 5 ) );
	}

	function test__sfcf_on_existing_function() {
		$this->assertEquals( "5 + a", _sfcf( 'sfc_test_real_function', 'sfc_test_fallback', 5, 'a' ) );
		$this->assertEquals( "a + 5", _sfcf( array( $this, 'real_object_function' ), array( $this, 'fallback' ), 5, 'a' ) );
	}

	function test__sfcm_on_nonexistent_function() {
		$this->assertEmpty( _sfcm( 'doesnt_exist' ) );
		$this->assertEmpty( _sfcm( array( $this, 'fake_object_function' ) ) );
	}

	function test__sfcm_on_nonexistent_function_with_message() {
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

	function test__sfcm_on_existing_function() {
		$msg = 'does not exist';

		$this->assertEquals( "5 + a", _sfcm( 'sfc_test_real_function', $msg, 5, 'a' ) );
		$this->assertEquals( "a + 5", _sfcm( array( $this, 'real_object_function' ), $msg, 5, 'a' ) );
	}

}
