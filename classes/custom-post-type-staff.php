<?php
/**
 * Custom Post Type Staff.
 *
 * @author Ralf Hortt
 **/
final class Custom_Post_Type_Staff
{
    /**
     * Plugin constructor.
     *
     * @since 2.0
     *
     * @author Ralf Hortt
     **/
    public function __construct()
    {
        add_action('init', [$this, 'register_post_type']);
        add_action('init', [$this, 'register_taxonomy']);
        add_action('plugins_loaded', [$this, 'load_plugin_textdomain']);
    }

    // END __construct

    /**
     * Load plugin translation.
     *
     * @return void
     *
     * @author Ralf Hortt <me@horttcore.de>
     *
     * @since v2.0
     **/
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain('custom-post-type-staff', false, dirname(plugin_basename(__FILE__)).'/../languages/');
    }

    // END load_plugin_textdomain

    /**
     * Register post type.
     *
     * @since 2.0
     *
     * @author Ralf Hortt
     */
    public function register_post_type()
    {
        register_post_type('staff', [
            'labels' => [
                'name'               => _x('Staff', 'post type general name', 'custom-post-type-staff'),
                'singular_name'      => _x('Staff', 'post type singular name', 'custom-post-type-staff'),
                'add_new'            => _x('Add New', 'Staff', 'custom-post-type-staff'),
                'add_new_item'       => __('Add New Staff', 'custom-post-type-staff'),
                'edit_item'          => __('Edit Staff', 'custom-post-type-staff'),
                'new_item'           => __('New Staff', 'custom-post-type-staff'),
                'view_item'          => __('View Staff', 'custom-post-type-staff'),
                'search_items'       => __('Search Staff', 'custom-post-type-staff'),
                'not_found'          => __('No Staff found', 'custom-post-type-staff'),
                'not_found_in_trash' => __('No Staff found in Trash', 'custom-post-type-staff'),
                'parent_item_colon'  => '',
                'menu_name'          => __('Staff', 'custom-post-type-staff'),
            ],
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => ['slug' => _x('staff', 'Post Type Slug', 'custom-post-type-staff')],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-groups',
            'supports'           => ['title', 'editor', 'thumbnail', 'page-attributes'],
        ]);
    }

    // END register_post_type

    /**
     * Register taxonomy.
     *
     * @since 2.0
     *
     * @author Ralf Hortt
     */
    public function register_taxonomy()
    {
        $labels = [
            'name'              => _x('Divisions', 'taxonomy general name', 'custom-post-type-staff'),
            'singular_name'     => _x('Division', 'taxonomy singular name', 'custom-post-type-staff'),
            'search_items'      => __('Search Divisions', 'custom-post-type-staff'),
            'all_items'         => __('All Divisions', 'custom-post-type-staff'),
            'parent_item'       => __('Parent Division', 'custom-post-type-staff'),
            'parent_item_colon' => __('Parent Division:', 'custom-post-type-staff'),
            'edit_item'         => __('Edit Division', 'custom-post-type-staff'),
            'update_item'       => __('Update Division', 'custom-post-type-staff'),
            'add_new_item'      => __('Add New Division', 'custom-post-type-staff'),
            'new_item_name'     => __('New Division Name', 'custom-post-type-staff'),
            'menu_name'         => __('Divisions', 'custom-post-type-staff'),
        ];

        register_taxonomy('division', ['staff'], [
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => ['slug' => _x('division', 'Division Slug', 'custom-post-type-staff')],
        ]);
    }

    // END register_taxonomy
} // END final class Custom_Post_Type_Staff

new Custom_Post_Type_Staff();
