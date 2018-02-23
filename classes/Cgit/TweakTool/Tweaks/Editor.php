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
        $this->toggleEditorButtons();
        $this->toggleEditorElements();
        $this->toggleMediaButtons();
        $this->toggleTextPaste();
    }

    /**
     * Editor buttons
     *
     * Removes TinyMCE editor buttons for various presentational elements that
     * have no reason to be in the content editor.
     *
     * @return void
     */
    protected function toggleEditorButtons()
    {
        if (!$this->config->get('hide_editor_buttons')) {
            return;
        }

        foreach (['mce_buttons', 'mce_buttons_2'] as $filter) {
            add_filter($filter, function ($buttons) {
                $forbidden = [
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

                return array_diff($buttons, $forbidden);
            });
        }
    }

    /**
     * Editor elements
     *
     * Restricts the range of available block elements in the TinyMCE editor to
     * those that are most likely to be supported by the theme.
     *
     * @return void
     */
    protected function toggleEditorElements()
    {
        if (!$this->config->get('hide_editor_elements')) {
            return;
        }

        add_filter('tiny_mce_before_init', function ($init) {
            $elements = [
                'Paragraph=p',
                'Heading 2=h2',
                'Heading 3=h3',
                'Heading 4=h4',

            ];

            $init['block_formats'] = implode(';', $elements);

            return $init;
        });
    }

    /**
     * Media upload buttons
     *
     * Prevent users from embedding images in the main content by removing the
     * media upload button from the TinyMCE editor.
     *
     * @return void
     */
    protected function toggleMediaButtons()
    {
        if (!$this->config->get('hide_media_buttons')) {
            return;
        }

        add_action('admin_head', function () {
            remove_action('media_buttons', 'media_buttons');
        });
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
