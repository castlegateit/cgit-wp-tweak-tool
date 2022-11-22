<?php

namespace Cgit\TweakTool;

class Config
{
    /**
     * Default settings
     *
     * @var array
     */
    private $defaults = [
        'force_plain_text_paste' => true,
        'hide_admin_bar' => false,
        'user_whitelist' => [],
        'welcome_message' => false,
    ];

    /**
     * Active settings
     *
     * @var array
     */
    private $settings = [];

    /**
     * Constructor
     *
     * Applies a WordPress filter to customize the active settings, then uses
     * array_merge and array_intersect_key to fill in any missing settings and
     * remove any invalid settings respectively.
     *
     * @return void
     */
    public function __construct()
    {
        $settings = apply_filters('cgit_tweak_tool_options', $this->defaults);
        $settings = array_merge($this->defaults, $settings);
        $settings = array_intersect_key($settings, $this->defaults);

        $this->settings = $settings;
    }

    /**
     * Return a configuration setting
     *
     * @return mixed
     */
    public function get($key)
    {
        if (!array_key_exists($key, $this->settings)) {
            return false;
        }

        return $this->settings[$key];
    }

    /**
     * Edit a configuration setting
     *
     * @return mixed
     */
    public function set($key, $value)
    {
        if (!array_key_exists($key, $this->settings)) {
            return false;
        }

        $this->settings[$key] = $value;

        return $this;
    }
}
