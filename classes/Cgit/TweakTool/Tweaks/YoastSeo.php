<?php

namespace Cgit\TweakTool\Tweaks;

class YoastSeo extends \Cgit\TweakTool\Tweak
{
    /**
     * Tweak
     *
     * @return void
     */
    public function tweak()
    {
        if (!$this->config->get('move_yoast_to_bottom')) {
            return;
        }

        // Move Yoast SEO to the bottom of editor screens.
        add_filter(
            'wpseo_metabox_prio',
            function () {
                return 'low';
            }
        );
    }
}
