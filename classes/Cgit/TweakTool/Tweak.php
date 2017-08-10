<?php

namespace Cgit\TweakTool;

abstract class Tweak
{
    /**
     * Settings
     *
     * @var Config
     */
    protected $config;

    /**
     * Constructor
     *
     * By default, the constructor should only assign the configuration options
     * class instance to a property so that it can be accessed by any classes
     * that extend this class.
     *
     * @param Config $config
     * @return void
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Apply the tweak(s) to the site
     *
     * Any classes that extend this class should redefine this method to apply
     * any necessary tweaks.
     *
     * @return void
     */
    public function tweak()
    {
        // ...
    }

    /**
     * Is the current user an administrator?
     *
     * If the user_whitelist configuration option has been set, return true if
     * the current user ID appears in the list. Otherwise, return true if the
     * user has a typical administrator capability.
     *
     * @return boolean
     */
    protected function userIsAdmin()
    {
        $admins = $this->config->get('user_whitelist') ?: [];

        if ($admins) {
            return in_array(get_current_user_id(), $admins);
        }

        return current_user_can('update_core');
    }
}
