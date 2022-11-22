<?php

namespace Cgit\TweakTool;

class Plugin
{
    /**
     * Tweaks available
     *
     * @var array
     */
    private $tweaks = [
        'AdminBar',
        'Editor',
        'YoastSeo',
    ];

    /**
     * Constructor
     *
     * Load the configuration and loop through each tweak class and instantiate
     * each one to apply any tweaks specified in the configuration.
     *
     * @return void
     */
    public function __construct()
    {
        add_action('init', function () {
            $this->config = new Config;

            foreach ($this->tweaks as $tweak) {
                $class = '\\Cgit\\TweakTool\\Tweaks\\' . $tweak;
                $instance = new $class($this->config);

                $instance->tweak();
            }
        }, 20);
    }
}
