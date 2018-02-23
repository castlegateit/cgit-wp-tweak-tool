<?php

namespace Cgit\TweakTool\Tweaks;

class Notifications extends \Cgit\TweakTool\Tweak
{
    /**
     * Tweak
     *
     * @return void
     */
    public function tweak()
    {
        $this->toggleUpdateNotifications();
    }

    /**
     * Show or hide notifications
     *
     * Notifications will always be visible to administrator users. You can use
     * the user_whitelist configuration option to restrict the range of users
     * that are considered administrators.
     *
     * @return void
     */
    protected function toggleUpdateNotifications()
    {
        if ($this->userIsAdmin() || !$this->config->get('hide_notifications')) {
            return;
        }

        add_action('admin_menu', function () {
            remove_action('admin_notices', 'update_nag', 3);
        });
    }
}
