<?php

class StaffBlock
{
    /**
     * Register hooks.
     *
     * @todo Refactor in a composer package
     *
     * @return void
     **/
    public function register()
    {
        register_block_type('horttcore/staff', [
            'render_callback' => function ($attributes) {
                return $this->render($attributes);
            },
        ]);
    }

    /**
     * Render the meta box.
     *
     * @param mixed $attributes Attributes
     *
     * @return string HTML output
     *
     * @since 1.0.0
     */
    public function render($attributes)
    {
        ob_start();

        $attributes = wp_parse_args($attributes, [
            'orderby'     => 'menu_order',
            'order'       => 'ASC',
            'postsToShow' => get_option('posts_per_page'),
        ]);

        $query = new \WP_Query([
            'post_type' => 'staff',
            'orderby'   => $attributes['orderBy'] ?? 'menu_order',
            'order'     => $attributes['order'] ?? 'ASC',
            'showposts' => $attributes['postsToShow'] ?? get_option('posts_per_page'),
        ]);

        if ($query->have_posts()) :

            require apply_filters('custom-post-type-staff-loop-template', plugin_dir_path(__FILE__).'../views/loop.php', $query, $attributes);

        endif;

        return ob_get_clean();
    }
}
(new StaffBlock())->register();
