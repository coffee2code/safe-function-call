# Developer Documentation

This plugin provides [hooks](#hooks) and [template tags](#template-tags).

## Template Tags

The plugin provides four template tags for use in your theme templates, functions.php, or plugins.

### Functions:

* `<?php function _sfc( $callback ) ?>`
This will safely invoke the specified callback. You can specify an arbitrary number of additional arguments that will get passed to it. If the callback does not exist, nothing is displayed and no error is generated.
* `<?php function _sfce( $callback ) ?>`
The same as `_sfc()` except that it echoes the return value of the callback before returning that value.
* `<?php function _sfcf( $callback, $fallback_callback = '' ) ?>`
The same as `_sfc()` except that it invokes the fallback callback (if it exists) if the callback does not exist.  `$function_name_if_missing()` is sent `$function_name` as its first argument, and then subsequently all arguments that would have otherwise been sent to `$function_name()`.
* `<?php function _sfcm( $callback, $message_if_missing = '' ) ?>`
The same as `_sfc()` except that it displays a message (the value of `$message_if_missing`), if the callback does not exist.

### Arguments:

* `$callback` _(string)_
A string representing the name of the function to be called, or an array of a class or object and its method (as can be done for `add_action()`/`add_filter()`)

* `$message_if_missing` _(string)_
(For `_sfcm()` only.)  The message to be displayed if `$function_name()` does not exist as a function.

* `$fallback_callback` _(string)_
(For `_sfcf()` only.)  The function to be called if the callback does not exist.

### Examples:

* `<?php _sfc('list_cities', 'Texas', 3); /* Assuming list_cities() is a valid function */ ?>`
"Austin, Dallas, Fort Worth"

* `<?php _sfc(array('Cities', 'list_cities'), 'Texas', 3); /* Assuming list_cities() is a valid function in the 'Cities' class */ ?>`
"Austin, Dallas, Fort Worth"

* `<?php _sfc(array($obj, 'list_cities'), 'Texas', 3); /* Assuming list_cities() is a valid function in the object $obj */ ?>`
"Austin, Dallas, Fort Worth"

* `<?php _sfc('list_cities', 'Texas', 3); /* Assuming list_cities() is not a valid function */ ?>`
""

* `<?php _sfce('largest_city', 'Tx'); /* Assuming largest_city() is a valid function that does not echo/display its return value */ ?>`
"Houston"

* `<?php _sfcm('list_cities', 'Unable to list cities at the moment', 'Texas', 3); /* Assuming list_cities() is a valid function */ ?>`
"Austin, Dallas, Fort Worth"

* `<?php _sfcm('list_cities', 'Unable to list cities at the moment', 'Texas', 3); /* Assuming list_cities() is not a valid function */ ?>`
"Unable to list cities at the moment"

* 
```php
<?php
	function unavailable_function_handler( $callback ) {
		echo "Sorry, but the function {$callback}() does not exist.";
	}
	_sfcf('nonexistent_function', 'unavailable_function_handler');
	?>
```

## Hooks

The plugin exposes a number of filters for hooking. Code using these filters should ideally be put into a mu-plugin or site-specific plugin (which is beyond the scope of this readme to explain). These hooks are intended for filter invocation usage rather than typical content filtering.

* `_sfc`  : Filter to safely invoke `_sfc()` in such a way that if the plugin were deactivated or deleted, then your calls to the function won't cause errors in your site.
* `_sfce` : Filter to safely invoke `_sfce()` in such a way that if the plugin were deactivated or deleted, then your calls to the function won't cause errors in your site.
* `_sfcf` : Filter to safely invoke `_sfcf()` in such a way that if the plugin were deactivated or deleted, then your calls to the function won't cause errors in your site.
* `_sfcm` : Filter to safely invoke `_sfcm()` in such a way that if the plugin were deactivated or deleted, then your calls to the function won't cause errors in your site.

### `_sfc` _(filter)_

The `_sfc` hook allows you to use an alternative approach to safely invoke `_sfc()` in such a way that if the plugin were deactivated or deleted, then your calls to the function won't cause errors in your site.

#### Arguments:

* same as for `_sfc()`

#### Example:

Instead of:

`<?php $cities = _sfc( 'list_cities', 'Texas', 3 ); ?>`

Do:

`<?php $cities = apply_filters( '_sfc', 'list_cities', 'Texas', 3 ); ?>`

### `_sfce`_(filter)_

The `_sfce` hook allows you to use an alternative approach to safely invoke `_sfce()` in such a way that if the plugin were deactivated or deleted, then your calls to the function won't cause errors in your site.

#### Arguments:

* same as for `_sfce()`

#### Example:

Instead of:

`<?php _sfce( 'largest_city', 'Tx' ); ?>`

Do:

`<?php apply_filters( '_sfce', 'largest_city', 'Tx' ); ?>`

### `_sfcf`_(filter)_

The `_sfcf` hook allows you to use an alternative approach to safely invoke `_sfcf()` in such a way that if the plugin were deactivated or deleted, then your calls to the function won't cause errors in your site.

#### Arguments:

* same as for `_sfcf()`

#### Example:

Instead of:

`<?php _sfcf( 'nonexistent_function', 'unavailable_function_handler' ); ?>`

Do:

`<?php apply_filters( '_sfcf', 'nonexistent_function', 'unavailable_function_handler' ); ?>`

### `_sfcm`_(filter)_

The `_sfcm` hook allows you to use an alternative approach to safely invoke `_sfcm()` in such a way that if the plugin were deactivated or deleted, then your calls to the function won't cause errors in your site.

#### Arguments:

* same as for `_sfcm()`

#### Example:

Instead of:

`<?php _sfcm( 'list_cities', 'Texas', 'Unable to list cities at the moment', 3 ); ?>`

Do:

`<?php apply_filters( '_sfcm', 'list_cities', 'Texas', 'Unable to list cities at the moment', 3 ); ?>`
