<?php

namespace Cgit\TweakTool\Tweaks;

class Templates extends \Cgit\TweakTool\Tweak
{
    /**
     * Available templates
     *
     * The list of templates that can be disabled excludes "404" because a
     * disabled template will trigger a 404 error and "admin" because we should
     * not disable to admin panel.
     *
     * @var array
     */
    private $templates = [
        'archive',
        'attachment',
        'author',
        'category',
        'comment_feed',
        'date',
        'day',
        'embed',
        'feed',
        'home',
        'month',
        'page',
        'paged',
        'post_type_archive',
        'posts_page',
        'preview',
        'robots',
        'search',
        'single',
        'singular',
        'tag',
        'tax',
        'time',
        'trackback',
        'year',
    ];

    /**
     * Disabled templates
     *
     * @var array
     */
    private $disabled = [];

    /**
     * Tweak
     *
     * Assign the list of hidden templates to a property, removing any invalid
     * entries, and run the toggle method.
     *
     * @return void
     */
    public function tweak()
    {
        $this->disabled = $this->config->get('hide_templates');
        $this->disabled = array_intersect($this->disabled, $this->templates);

        $this->toggleTemplates();
    }

    /**
     * Enable or disable templates
     *
     * @return void
     */
    protected function toggleTemplates()
    {
        if (!$this->disabled) {
            return;
        }

        add_action('pre_get_posts', [$this, 'hideTemplates']);
    }

    /**
     * Hide templates
     *
     * This should be run during the pre_get_posts action to disable particular
     * templates based on the configuration options, which correspond to boolean
     * properties on the query object. This does not apply to is_admin queries.
     *
     * @param WP_Query $query
     * @return void
     */
    public function hideTemplates($query)
    {
        foreach ($this->disabled as $template) {
            $property = 'is_' . $template;

            if (!$query->is_admin && $query->$property) {
                $error_template = locate_template('404.php');

                status_header(404);
                $query->set_404();

                if ($error_template) {
                    require_once $error_template;
                }

                exit;
            }
        }
    }
}
