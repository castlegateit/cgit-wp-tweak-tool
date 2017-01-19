<?php

namespace Cgit;

class TweakTool
{
    /**
     * Reference to the singleton instance of the class
     */
    private static $instance;

    /**
     * Default options
     *
     * The user whitelist is an array of user IDs or a single user ID that
     * should be considered the site administrator(s). The hide menus option
     * accepts an array consisting of one or more of: posts, media, links,
     * pages, comments, themes, plugins, profile, users, tools, settings,
     * categories, tags.
     */
    private $defaultOptions = [
        'force_plain_text_paste' => false,
        'hide_admin_bar' => false,
        'hide_editor_buttons' => true,
        'hide_editor_elements' => true,
        'hide_media_buttons' => true,
        'hide_menus' => [],
        'hide_notifications' => true,
        'welcome_message' => false,
        'user_whitelist' => [],
    ];

    /**
     * Site options
     */
    private $options = [];

    /**
     * Constructor
     *
     * Private constructor ...
     */
    private function __construct($options = [])
    {
        // Set default options
        $this->options = $this->defaultOptions;

        // Set custom options
        $this->tweak($options);
    }

    /**
     * Return the singleton instance of the class
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Tweak option
     *
     * Edit a single option (in which case, the second argument is required) or
     * multiple options as an associative array (in which case the second
     * argument is ignored).
     */
    public function tweak($options, $value = null)
    {
        // If single option, convert to array
        if (is_string($options)) {
            if ($value == null) {
                return false;
            }

            $options = [
                $options => $value,
            ];
        }

        // Set options
        foreach ($options as $key => $value) {
            if (array_key_exists($key, $this->defaultOptions)) {
                $this->options[$key] = $value;
            }
        }

        // Apply tweaks
        $this->update();
    }

    /**
     * Apply tweaks
     */
    public function update()
    {
        // Filter options
        $this->options = apply_filters(
            'cgit_tweak_tool_options',
            $this->options
        );

        // Run methods
        foreach ($this->options as $key => $value) {
            $method = $this->camelize($key);

            if ($value && method_exists($this, $method)) {
                $this->$method();
            }
        }
    }

    /**
     * Force plain text paste
     */
    private function forcePlainTextPaste()
    {
        $mce_filters = ['tiny_mce_before_init', 'teeny_mce_before_init'];
        $plugin_filters = ['teeny_mce_plugins'];
        $button_filters = ['mce_buttons', 'mce_buttons_2'];

        foreach ($mce_filters as $filter) {
            add_filter($filter, function($init) {
                global $tinymce_version;

                if ($tinymce_version[0] < 4) {
                    $init['paste_text_sticky'] = true;
                    $init['paste_text_sticky_default'] = true;
                } else {
                    $init['paste_as_text'] = true;
                }

                return $init;
            });
        }

        foreach ($plugin_filters as $filter) {
            add_filter($filter, function($plugins) {
                $plugins[] = 'paste';

                return $plugins;
            });
        }

        foreach ($button_filters as $filter) {
            add_filter($filter, function($buttons) {
                $old_buttons = ['pastetext'];
                $new_buttons = array_diff($buttons, $old_buttons);

                return $new_buttons;
            });
        }
    }

    /**
     * Hide admin bar
     */
    private function hideAdminBar()
    {
        add_action('init', function() {
            show_admin_bar(false);
        });
    }

    /**
     * Hide editor buttons
     *
     * Removes buttons for various presentational elements from the WordPress
     * content editor.
     */
    private function hideEditorButtons()
    {
        $button_filters = ['mce_buttons', 'mce_buttons_2'];

        foreach ($button_filters as $filter) {
            add_filter($filter, function($buttons) {
                $old_buttons = [
                    'forecolor',
                    'indent',
                    'aligncenter',
                    'alignfull',
                    'alignleft',
                    'alignright',
                    'outdent',
                    'strikethrough',
                    'underline',
                    'wp_more'
                ];
                $new_buttons = array_diff($buttons, $old_buttons);

                return $new_buttons;
            });
        }
    }

    /**
     * Hide editor elements
     *
     * Restricts the range of available block level elements to those that will
     * definitely be available in the theme.
     */
    private function hideEditorElements()
    {
        add_filter('tiny_mce_before_init', function($init) {
            $init['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;'
                . 'Heading 4=h4';

            return $init;
        });
    }

    /**
     * Hide media buttons
     *
     * Prevent users from embedding images in the content using the WordPress
     * content editor.
     */
    private function hideMediaButtons()
    {
        add_action('admin_head', function() {
            remove_action('media_buttons', 'media_buttons');
        });
    }

    /**
     * Hide menus
     */
    private function hideMenus()
    {
        add_action('admin_menu', function() {
            if ($this->isAdmin()) {
                return;
            }

            $menus = [
                'posts' => 'edit.php',
                'media' => 'upload.php',
                'links' => 'link-manager.php',
                'pages' => 'edit.php?post_type=page',
                'comments' => 'edit-comments.php',
                'themes' => 'themes.php',
                'plugins' => 'plugins.php',
                'profile' => 'profile.php',
                'users' => 'users.php',
                'tools' => 'tools.php',
                'settings' => 'options-general.php',
                'custom_fields' => 'edit.php?post_type=acf-field-group',
            ];

            $submenus = [
                'edit.php' => [
                    'categories' => 'edit-tags.php?taxonomy=category',
                    'tags' => 'edit-tags.php?taxonomy=post_tag',
                ],
            ];

            foreach ($menus as $key => $menu) {
                if (in_array($key, $this->options['hide_menus'])) {
                    remove_menu_page($menu);
                }
            }

            foreach ($submenus as $menu => $submenus) {
                foreach ($submenus as $key => $submenu) {
                    if (in_array($key, $this->options['hide_menus'])) {
                        remove_submenu_page($menu, $submenu);
                    }
                }
            }
        });
    }

    /**
     * Hide notifications
     */
    private function hideNotifications()
    {
        add_action('admin_menu', function() {
            if ($this->isAdmin()) {
                return;
            }

            remove_action('admin_notices', 'update_nag', 3);
        });
    }

    /**
     * Welcome message
     *
     * Use a custom welcome message. If the string contains %s, the user name
     * will be added with sprintf().
     */
    private function welcomeMessage()
    {
        add_filter('admin_bar_menu', function($toolbar) {
            $user = wp_get_current_user();
            $name = $user->display_name;
            $account = $toolbar->get_node('my-account');
            $text = sprintf($this->options['welcome_message'], $name);
            $title = preg_replace(
                '/[^<>]*' . $name . '[^<>]*/i',
                $text,
                $account->title
            );
            $node = [
                'id' => 'my-account',
                'title' => $title,
            ];

            $toolbar->add_node($node);

            return $toolbar;
        });
    }

    /**
     * Is current user an administrator?
     *
     * By default, administrators are those users with the administrator user
     * role. If the whitelist option is set, administrators are those users with
     * IDs that appear in the whitelist array.
     */
    public function isAdmin()
    {
        $whitelist = $this->options['user_whitelist'];

        if ($whitelist) {
            if (!is_array($whitelist)) {
                $whitelist = [$whitelist];
            }

            return in_array(get_current_user_id(), $whitelist);
        }

        return current_user_can('update_core');
    }

    /**
     * Convert string to camel case
     */
    private function camelize($str)
    {
        return lcfirst(str_replace('_', '', ucwords($str, '_')));
    }
}
