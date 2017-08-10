<?php

namespace Cgit\TweakTool\Tweaks;

class Menus extends \Cgit\TweakTool\Tweak
{
    /**
     * Menus
     *
     * @var array
     */
    private $menus = [
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

    /**
     * Secondary menus
     *
     * @var array
     */
    private $submenus = [
        'edit.php' => [
            'categories' => 'edit-tags.php?taxonomy=category',
            'tags' => 'edit-tags.php?taxonomy=post_tag',
        ],
    ];

    /**
     * Admin bar nodes
     *
     * @var array
     */
    private $nodes = [
        'comments' => 'comments',
        'media' => 'new-media',
        'pages' => 'new-page',
        'posts' => 'new-post',
    ];

    /**
     * Hidden menus
     *
     * @var array
     */
    private $hidden = [];

    /**
     * Tweak
     *
     * Assign the list of hidden menus to a property. If there are hidden menus
     * and if the user is not an administrator, hide the hidden menus.
     *
     * @return void
     */
    public function tweak()
    {
        $this->hidden = $this->config->get('hide_menus');

        $this->toggleMenus();
    }

    /**
     * Show or hide menus
     *
     * Provides the option of removing menus from the WordPress admin panel. If
     * a menu has been removed, the corresponding "New" links should also be
     * removed. Note that all menus and links should always be visible to
     * administrative users.
     *
     * @return void
     */
    protected function toggleMenus()
    {
        if ($this->userIsAdmin() || !$this->config->get('hide_menus')) {
            return;
        }

        add_action('admin_menu', [$this, 'hideAdminMenus']);
        add_action('admin_bar_menu', [$this, 'hideAdminBarMenus'], 999);
    }

    /**
     * Hide menus
     *
     * Hide the admin menus specified by the configuration options. This should
     * be run on the admin_menu action.
     *
     * @return void
     */
    public function hideAdminMenus()
    {
        foreach ($this->menus as $key => $menu) {
            if (in_array($key, $this->hidden)) {
                remove_menu_page($menu);
            }
        }

        foreach ($this->submenus as $menu => $submenus) {
            foreach ($submenus as $key => $submenu) {
                if (in_array($key, $this->hidden)) {
                    remove_submenu_page($menu, $submenu);
                }
            }
        }
    }

    /**
     * Hide admin bar links
     *
     * @return void
     */
    public function hideAdminBarMenus($bar)
    {
        foreach ($this->nodes as $key => $node) {
            if (in_array($key, $this->hidden)) {
                $bar->remove_node($node);
            }
        }
    }
}
