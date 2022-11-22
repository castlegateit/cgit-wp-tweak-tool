<?php

namespace Cgit\TweakTool\Tweaks;

class Editor extends \Cgit\TweakTool\Tweak
{
    /**
     * Tweak
     *
     * @return void
     */
    public function tweak()
    {
        $this->toggleTextPaste();
    }

    /**
     * Force plain text paste
     *
     * @return void
     */
    protected function toggleTextPaste()
    {
        if (!$this->config->get('force_plain_text_paste')) {
            return;
        }

        $editor_filters = ['tiny_mce_before_init', 'teeny_mce_before_init'];
        $button_filters = ['mce_buttons', 'mce_buttons_2'];
        $plugin_filters = ['teeny_mce_plugins'];

        foreach ($editor_filters as $filter) {
            add_filter($filter, [$this, 'forceEditorTextPaste']);
        }

        foreach ($button_filters as $filter) {
            add_filter($filter, [$this, 'forceButtonTextPaste']);
        }

        foreach ($plugin_filters as $filter) {
            add_filter($filter, [$this, 'forcePluginTextPaste']);
        }
    }

    /**
     * Force plain text paste in the editor
     *
     * This should be used with the TinyMCE editor filters to force plain text
     * mode paste within the editor.
     *
     * @param array $init
     * @return array
     */
    public function forceEditorTextPaste($init)
    {
        global $tinymce_version;

        if ($tinymce_version[0] < 4) {
            $init['paste_text_sticky'] = true;
            $init['paste_text_sticky_default'] = true;
        } else {
            $init['paste_as_text'] = true;
        }

        return $init;
    }

    /**
     * Force plain text paste using the editor buttons
     *
     * This should be used with the TinyMCE editor buttons filters to force
     * plain text mode paste when using the TinyMCE buttons.
     *
     * @param array $buttons
     * @return array
     */
    public function forceButtonTextPaste($buttons)
    {
        $old_buttons = ['pastetext'];
        $new_buttons = array_diff($buttons, $old_buttons);

        return $new_buttons;
    }

    /**
     * Force plain text paste in TinyMCE plugins
     *
     * This should be used with the TinyMCE editor plugins filters to force
     * plain text mode paste in TinyMCE.
     *
     * @param array $plugins
     * @return array
     */
    public function forcePluginTextPaste($plugins)
    {
        $plugins[] = 'paste';

        return $plugins;
    }
}
