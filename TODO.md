# TODO

The following list comprises ideas, suggestions, and known issues, all of which are in consideration for possible implementation in future releases.

***This is not a roadmap or a task list.*** Just because something is listed does not necessarily mean it will ever actually get implemented. Some might be bad ideas. Some might be impractical. Some might either not benefit enough users to justify the effort or might negatively impact too many existing users. Or I may not have the time to devote to the task.

* Change functions to being variadic
* `_sfcm()` should really not have echoed the message for a missing function. It should have returned the specified message/value. A new `_sfcme()` should have echoed the value if the function was missing.
    * Since that ship sailed, maybe a `_sfcr()` should be created that will return a value if the function is missing, without echoing anything.
* Create longer, more explicitly named aliases for each function, making for a better readability at the expense of brevity
    * e.g. `sfc_call_with_fallback_message()` => `_sfcm()`, `sfc_call_with_fallback_function()` => `_sfcf()`
* Add optional logging to record when a function was called that doesn't exist.

Feel free to make your own suggestions or champion for something already on the list (via the [plugin's support forum on WordPress.org](https://wordpress.org/support/plugin/safe-function-call/) or on [GitHub](https://github.com/coffee2code/safe-function-call/) as an issue or PR).