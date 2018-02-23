<?php

namespace Cgit\TweakTool\Tweaks;

class AdminBar extends \Cgit\TweakTool\Tweak
{
    /**
     * Tweak
     *
     * @return void
     */
    public function tweak()
    {
        $this->toggleAdminBar();
        $this->editWelcomeMessage();
    }

    /**
     * Show or hide the WordPress admin bar
     *
     * Provides the option of removing the admin bar that usually appears at the
     * top of the screen when you are logged in. This method should be run
     * during the init action, which should happen because this class is only
     * instantiated on init.
     *
     * @return void
     */
    protected function toggleAdminBar()
    {
        if (!$this->config->get('hide_admin_bar')) {
            return;
        }

        show_admin_bar(false);
    }

    /**
     * Edit the welcome message
     *
     * Edit or remove the "Howdy" message. The welcome message is formatted with
     * sprintf, so you can use %s in place of the user display name.
     *
     * @return void
     */
    protected function editWelcomeMessage()
    {
        if (!$this->config->get('welcome_message')) {
            return;
        }

        add_filter('admin_bar_menu', function ($toolbar) {
            $user = wp_get_current_user();
            $name = $user->display_name;
            $account = $toolbar->get_node('my-account');
            $text = sprintf($this->options['welcome_message'], $name);
            $title = preg_replace('/[^<>]*' . $name . '[^<>]*/i', $text,
                $account->title);
            $node = [
                'id' => 'my-account',
                'title' => $title,
            ];

            $toolbar->add_node($node);

            return $toolbar;
        });
    }
}
