<?php
/**
 * Custom Post Type Staff Admin
 *
 * @package Custom Post Type Staff
 * @author Ralf Hortt
 */
final class Custom_Post_Type_Staff_Admin
{



    /**
     * Plugin constructor
     *
     * @access public
     * @since 2.0
     * @author Ralf Hortt
     **/
    public function __construct()
    {

        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'manage_staff_posts_custom_column', array( $this, 'manage_staff_posts_custom_column' ), 10, 2 );
        add_action( 'save_post', array( $this, 'staff_save_metabox' ) );
        add_action('enqueue_block_editor_assets', [$this, 'register_block'] );
            
        add_filter( 'manage_edit-staff_columns', array( $this, 'manage_edit_staff_columns' ) );
        add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );

    } // END __construct



    /**
     * Register Metaboxes
     *
     * @access public
     * @since 2.0
     * @author Ralf Hortt
     **/
    public function add_meta_boxes()
    {

        add_meta_box( 'staff-meta', __( 'Information', 'custom-post-type-staff' ), array( $this, 'staff_meta' ), 'staff', 'normal' );

    } // END add_meta_boxes



    /**
     * Add custom columns
     *
     * @access public
     * @param array $columns Columns
     * @return array Columns
     * @since 2.0
     * @author Ralf Hortt
     **/
    public function manage_edit_staff_columns( $columns )
    {

        unset( $columns['cb'], $columns['taxonomy-division'], $columns['date'] );

        $prepend = array(
            'cb' => '<input type="checkbox" />',
            'thumbnail' => __( 'Thumbnail' ),
            'title' => __( 'Title' ),
        );

        $append = array(
            'phone' => __( 'Phone', 'custom-post-type-staff' ),
            'mobile' => __( 'Mobile', 'custom-post-type-staff' ),
            'fax' => __( 'Fax', 'custom-post-type-staff' ),
            'email' => __( 'E-Mail', 'custom-post-type-staff' ),
            'taxonomy-division' => __( 'Divisions', 'custom-post-type-staff' ),
            'date' => __( 'Date' ),
        );

        return $prepend + $columns + $append;

    } // END manage_edit_staff_columns



    /**
     * Print custom columns
     *
     * @access public
     * @param str $column Column name
     * @param int $post_id Post ID
     * @since 2.0
     * @author Ralf Hortt
     **/
    public function manage_staff_posts_custom_column( $column, $post_id )
    {

        global $post;

        $meta = get_post_meta( $post_id, '_staff-meta', TRUE );

        switch( $column ) :

            case 'thumbnail' :
                if ( !has_post_thumbnail( $post_id ) ) :
                    echo '<img src="http://www.gravatar.com/avatar/' . md5( $meta['email'] ) . '?d=mm" />';
                else :
                    echo get_the_post_thumbnail( $post_id, 'thumbnail' );
                endif;
                break;

            case 'phone' :
                echo $meta['phone'];
                break;

            case 'mobile' :
                echo $meta['mobile'];
                break;

            case 'fax' :
                echo $meta['fax'];
                break;

            case 'email' :
                echo '<a href="mailto:' . $meta['email'] . '">' . $meta['email'] . '</a>';
                break;

            default :
                break;

        endswitch;

    } // END manage_staff_posts_custom_column



    /**
     * Update messages
     *
     * @access public
     * @param array $messages Messages
     * @return array Messages
     * @since 2.0
     * @author Ralf Hortt
     **/
    public function post_updated_messages( $messages )
    {

        $post             = get_post();
        $post_type        = 'staff';
        $post_type_object = get_post_type_object( $post_type );

        $messages[$post_type] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => __( 'Staff updated.', 'custom-post-type-staff' ),
            2  => __( 'Custom field updated.', 'custom-post-type-staff' ),
            3  => __( 'Custom field deleted.', 'custom-post-type-staff' ),
            4  => __( 'Staff updated.', 'custom-post-type-staff' ),
            /* translators: %s: date and time of the revision */
            5  => isset( $_GET['revision'] ) ? sprintf( __( 'Staff restored to revision from %s', 'custom-post-type-staff' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => __( 'Staff published.', 'custom-post-type-staff' ),
            7  => __( 'Staff saved.', 'custom-post-type-staff' ),
            8  => __( 'Staff submitted.', 'custom-post-type-staff' ),
            9  => sprintf( __( 'Staff scheduled for: <strong>%1$s</strong>.', 'custom-post-type-staff' ), date_i18n( __( 'M j, Y @ G:i', 'custom-post-type-staff' ), strtotime( $post->post_date ) ) ),
            10 => __( 'Staff draft updated.', 'custom-post-type-staff' )
        );

        if ( !$post_type_object->publicly_queryable )
            return $messages;

        $permalink = get_permalink( $post->ID );

        $view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View staff', 'custom-post-type-staff' ) );
        $messages[$post_type][1] .= $view_link;
        $messages[$post_type][6] .= $view_link;
        $messages[$post_type][9] .= $view_link;

        $preview_permalink = add_query_arg( 'preview', 'true', $permalink );
        $preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview staff', 'custom-post-type-staff' ) );
        $messages[$post_type][8]  .= $preview_link;
        $messages[$post_type][10] .= $preview_link;

        return $messages;

    } // END post_updated_messages


    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function register_block()
    {
        wp_enqueue_script('custom-post-type-staff-blocks', plugin_dir_url(__FILE__) . '../dist/js/blocks.js', [], filemtime(plugin_dir_path(__FILE__) . '../dist/js/blocks.js'), true);
    }



    /**
     * Information meta box
     *
     * @access public
     * @param obj $post Post object
     * @since 2.0
     * @author Ralf Hortt
     **/
    public function staff_meta( $post )
    {

        $meta = apply_filters( 'staff-meta', get_post_meta( $post->ID, '_staff-meta', TRUE ) );

        do_action( 'staff-meta-table-before', $post, $meta );

        ?>

        <table class="form-table">

            <?php do_action( 'staff-meta-before', $post, $meta ) ?>

            <tr>
                <th><?php _e( 'Salutation:', 'custom-post-type-staff' ); ?></th>
                <td>
                    <label><input <?php if ( $meta ) checked( 'mr', $meta['salutation'] ) ?> type="radio" value="mr" name="staff-salutation" id="staff-salutation-male"> <?php _e( 'Mr', 'custom-post-type-staff' ); ?></label><br>
                    <label><input <?php if ( $meta ) checked( 'ms', $meta['salutation'] ) ?> type="radio" value="ms" name="staff-salutation" id="staff-salutation-female"> <?php _e( 'Ms', 'custom-post-type-staff' ); ?></label>
                </td>
            </tr>
            <tr>
                <th><label for="staff-grade"><?php _e( 'Grade:', 'custom-post-type-staff' ); ?></label></th>
                <td><input size="50" type="text" value="<?php if ( $meta ) echo esc_attr( $meta['grade'] ) ?>" name="staff-grade" id="staff-grade"></td>
            </tr>
            <tr>
                <th><label for="staff-first-name"><?php _e( 'First name:', 'custom-post-type-staff' ); ?></label></th>
                <td><input size="50" type="text" value="<?php if ( $meta ) echo esc_attr( $meta['first-name'] ) ?>" name="staff-first-name" id="staff-first-name"></td>
            </tr>
            <tr>
                <th><label for="staff-last-name"><?php _e( 'Last name:', 'custom-post-type-staff' ); ?></label></th>
                <td><input size="50" type="text" value="<?php if ( $meta ) echo esc_attr( $meta['last-name'] ) ?>" name="staff-last-name" id="staff-last-name"></td>
            </tr>
            <tr>
                <th><label for="staff-role"><?php _e( 'Role:', 'custom-post-type-staff' ); ?></label></th>
                <td><input size="50" type="text" value="<?php if ( $meta ) echo esc_attr( $meta['role'] ) ?>" name="staff-role" id="staff-role"></td>
            </tr>
            <tr>
                <th><label for="staff-room"><?php _e( 'Room:', 'custom-post-type-staff' ); ?></label></th>
                <td><input size="50" type="text" value="<?php if ( $meta ) echo esc_attr( $meta['room'] ) ?>" name="staff-room" id="staff-room"></td>
            </tr>
            <tr>
                <th><label for="staff-phone"><?php _e( 'Phone:', 'custom-post-type-staff' ); ?></label></th>
                <td><input size="50" type="text" value="<?php if ( $meta ) echo esc_attr( $meta['phone'] ) ?>" name="staff-phone" id="staff-phone"></td>
            </tr>
            <tr>
                <th><label for="staff-fax"><?php _e( 'Fax:', 'custom-post-type-staff' ); ?></label></th>
                <td><input size="50" type="text" value="<?php if ( $meta ) echo esc_attr( $meta['fax'] ) ?>" name="staff-fax" id="staff-fax"></td>
            </tr>
            <tr>
                <th><label for="staff-mobile"><?php _e( 'Mobile:', 'custom-post-type-staff' ); ?></label></th>
                <td><input size="50" type="text" value="<?php if ( $meta ) echo esc_attr( $meta['mobile'] ) ?>" name="staff-mobile" id="staff-mobile"></td>
            </tr>
            <tr>
                <th><label for="staff-email"><?php _e( 'E-Mail:', 'custom-post-type-staff' ); ?></label></th>
                <td><input size="50" type="text" value="<?php if ( $meta ) echo esc_attr( $meta['email'] ) ?>" name="staff-email" id="staff-email"></td>
            </tr>
            <tr>
                <th><label for="staff-url"><?php _e( 'URL:', 'custom-post-type-staff' ); ?></label></th>
                <td><input size="50" type="text" value="<?php if ( $meta ) echo esc_url( $meta['url'] ) ?>" name="staff-url" id="staff-url"></td>
            </tr>

            <?php do_action( 'staff-meta-after', $post, $meta ) ?>

        </table>

        <?php

        do_action( 'staff-meta-table-after', $post, $meta );

        wp_nonce_field( 'save-staff-meta', 'staff-meta-nonce' );

    } // END staff_meta



    /**
     * Save Metabox
     *
     * @access public
     * @param int $post_id Post ID
     * @since 2.0
     * @author Ralf Hortt
     **/
    public function staff_save_metabox( $post_id )
    {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return;

        if ( !isset( $_POST['staff-meta-nonce'] ) || !wp_verify_nonce( $_POST['staff-meta-nonce'], 'save-staff-meta' ) )
            return;

        $meta = array(
            'salutation' => ( isset( $_POST['staff-salutation'] ) ) ? sanitize_text_field( $_POST['staff-salutation'] ) : '',
            'first-name' => sanitize_text_field( $_POST['staff-first-name'] ),
            'last-name' => sanitize_text_field( $_POST['staff-last-name'] ),
            'grade' => sanitize_text_field( $_POST['staff-grade'] ),
            'role' => sanitize_text_field( $_POST['staff-role'] ),
            'room' => sanitize_text_field( $_POST['staff-room'] ),
            'phone' => sanitize_text_field( $_POST['staff-phone'] ),
            'mobile' => sanitize_text_field( $_POST['staff-mobile'] ),
            'fax' => sanitize_text_field( $_POST['staff-fax'] ),
            'email' => sanitize_text_field( $_POST['staff-email'] ),
            'url' => esc_url_raw( $_POST['staff-url'] ),
        );

        update_post_meta( $post_id, '_staff-meta', apply_filters( 'save-staff-meta', $meta, $post_id ) );

    } // END staff_save_metabox



} // END Custom_Post_Type_Staff_Admin

new Custom_Post_Type_Staff_Admin;
