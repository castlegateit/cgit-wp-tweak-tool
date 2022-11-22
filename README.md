# Castlegate IT WP Tweak Tool #

Another plugin that makes it easy to tweak the WordPress admin interface, including:

*   Hide the admin toolbar
*   Edit the welcome message
*   Force plain text paste in the content editor

## Options ##

### Editing options ###

The options are stored as an associative array, with the following default values:

~~~ php
$options = [
    'force_plain_text_paste' => true,
    'hide_admin_bar' => false,
    'welcome_message' => false,
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

### Editing the welcome message ###

If the custom welcome message contains `%s`, it will include the current user's display name using `sprintf()`.

## Changes since version 3.0

The following features have been removed:

*   Hide menu items in the Dashboard for non-admin users (`hide_menus`). We now recommend this is done on a per-site and per-theme basis.

*   Show navigation menu settings when appearance settings are hidden for some users (`show_nav_menus`). This feature is now provided by the [Site Manager](https://github.com/castlegateit/cgit-wp-site-manager) plugin.

*   Hide update notifications for non-admin users (`hide_notifications`). This feature is now provided by the [Admin Notifications](https://github.com/castlegateit/cgit-wp-admin-notifications) plugin.

*   Hide presentational markup (`hide_editor_buttons`), some block-level elements (`hide_editor_elements`), and media controls (`hide_media_buttons`) controls in TinyMCE. We now recommend this is done on a per-site and per-theme basis.

*   Hide templates and pages (`hide_templates`). We now recommend this is done with filters on a per-site and per-theme basis.

*   Move Yoast SEO fields to the bottom of the screen (`move_yoast_to_bottom`). This feature is now provided by the [SEO Headings](https://github.com/castlegateit/cgit-wp-seo-headings) plugin.

## License

Released under the [MIT License](https://opensource.org/licenses/MIT). See [LICENSE](LICENSE) for details.
