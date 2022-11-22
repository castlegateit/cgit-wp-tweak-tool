# Castlegate IT WP Tweak Tool #

Another plugin that makes it easy to tweak the WordPress admin interface, including:

*   Hide the admin toolbar
*   Edit the welcome message
*   Hide menu items in the Dashboard for non-admin users
*   Hide update notifications for non-admin users
*   Hide media controls in the content editor
*   Hide presentational markup controls
*   Hide some block-level elements in the content editor
*   Force plain text paste in the content editor
*   Disable queries for particular page or template types
*   Move Yoast SEO to the bottom of editor screens

## Options ##

### Editing options ###

The options are stored as an associative array, with the following default values:

~~~ php
$options = [
    'force_plain_text_paste' => true,
    'hide_admin_bar' => false,
    'hide_editor_buttons' => true,
    'hide_editor_elements' => true,
    'hide_media_buttons' => false,
    'hide_menus' => [],
    'hide_notifications' => true,
    'hide_templates' => [],
    'user_whitelist' => [],
    'welcome_message' => false,
    'show_nav_menus' => false,
    'move_yoast_to_bottom' => true,
];
~~~

You can use the `cgit_tweak_tool_options` filter to edit the options:

~~~ php
add_action('init', function () {
    add_filter('cgit_tweak_tool_options', function ($options) {
        $options['hide_admin_bar'] = true;
        return $options;
    });
});
~~~

Note that the options are set in the `init` action with priority `20` so this filter must be applied earlier, either using a higher priority in the `init` action (the default value of `10` should work) or using an earlier action.

### Hiding menus ###

You can hide most of the main menus in the dashboard:

~~~ php
$options = [
    'hide_menus' => [
        'posts',
        'media',
        'links',
        'pages',
        'comments',
        'themes',
        'plugins',
        'profile',
        'users',
        'tools',
        'settings',
        'custom_fields', // ACF
        'categories',
        'tags',
    ],
];
~~~

### Editing the welcome message ###

If the custom welcome message contains `%s`, it will include the current user's display name using `sprintf()`.

### Restricting tweaks to particular users ###

The `hide_menus` and `hide_notifications` options only affect non-admin users. This can be restricted further by providing a whitelist of user IDs:

~~~ php
$options = [
    'user_whitelist' => [1, 4],
];
~~~

This is useful when a client has been given an administrator account (e.g. to edit users) but you still want to hide some menus and notifications from their account.

### Showing menus without themes ###

You can also use the whitelist feature to allow restricted administrator accounts to edit menus without switching themes:

~~~ php
add_filter('cgit_tweak_tool_options', function ($options) {
    $options['user_whitelist'] = [1, 4];
    $options['hide_menus'] = ['themes'];
    $options['show_nav_menus'] = true;

    return $options;
});
~~~

### Disabling queries ###

You can disable particular queries using the `hide_templates` option. For example, the following will prevent site visitors from accessing search results or single posts (i.e. queries including `is_search` and `is_single`).

~~~ php
$options['hide_template'] = ['search', 'single'];
~~~

You can use any of the [boolean properties of `WP_Query`](https://codex.wordpress.org/Class_Reference/WP_Query) (without `is_`) to disable their corresponding templates and send 404 error messages instead.

## License

Released under the [MIT License](https://opensource.org/licenses/MIT). See [LICENSE](LICENSE) for details.
