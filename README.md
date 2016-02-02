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

## Options ##

### Editing options ###

The options are stored as an associative array, with the following default values:

    $options = [
        'force_plain_text_paste' => false,
        'hide_admin_bar' => false,
        'hide_editor_buttons' => true,
        'hide_editor_elements' => true,
        'hide_media_buttons' => true,
        'hide_menus' => [],
        'hide_notifications' => true,
        'welcome_message' => false,
        'whitelist' => [],
    ];

There are several ways of editing the options. You can change one or more options using the `Cgit\TweakTool` object:

    $tool = Cgit\TweakTool::getInstance();
    $tool = cgit_tweak_tool(); // equivalent to the previous line

    // Change multiple options
    $tool->tweak([
        'hide_admin_bar' => true,
        'hide_menus' => [
            'posts',
            'pages',
        ],
    ]);

    // Change options individually
    $tool->tweak('hide_admin_bar', true);
    $tool->tweak('hide_menus', ['posts', 'pages']);

You can use the `cgit_tweak_tool()` function itself to edit multiple options:

    cgit_tweak_tool([
        'hide_admin_bar' => true,
    ]);

You can also use the `cgit_tweak_tool_options` filter:

    add_filter('cgit_tweak_tool_options', filter($options) {
        $option['hide_admin_bar'] = true,
        return $options;
    });

Options set with the filter will override any options set using the object method.

### Hiding menus ###

You can hide most of the main menus in the dashboard:

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
            'categories',
            'tags',
        ],
    ];

### Editing the welcome message ###

If the custom welcome message contains `%s`, it will include the current user's display name using `sprintf()`.

### Restricting tweaks to particular users ###

The `hide_menus` and `hide_notifications` options only affect non-admin users. This can be restricted further by providing a whitelist of user IDs:

    $options = [
        'whitelist' => [1, 4],
    ];

This is useful when a client has been given an administrator account (e.g. to edit users) but you still want to hide some menus and notifications from their account.

## Admin Tweaks ##

The options available in this plugin are also available in the [Admin Tweaks](https://github.com/castlegateit/cgit-wp-admin-tweaks) plugin. The difference is that this plugin does not store its settings in the database, which means they can be version controlled and deployed.
