<?php
/*
Plugin Name: Custom Post Type Staff
Plugin URI: http://horttcore.de
Description: A custom post type for managing staff
Version: 1.1.2
Author: Ralf Hortt
Author URI: http://horttcore.de
License: GPL2
*/



/**
 *
 *  Custom Post Type Staff
 *
 */
class Custom_Post_Type_Staff
{



	/**
	 * Plugin constructor
	 *
	 * @access public
	 * @since 1.0
	 * @author Ralf Hortt
	 **/
	public function __construct()
	{

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_taxonomy' ) );
		add_action( 'manage_staff_posts_custom_column', array( $this, 'manage_staff_posts_custom_column' ), 10, 2 );
		add_action( 'save_post', array( $this, 'staff_save_metabox' ) );

		add_filter( 'manage_edit-staff_columns', array( $this, 'manage_edit_staff_columns' ) );
		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );

		add_shortcode( 'STAFF', array( $this, 'shortcode_staff' ) );

		load_plugin_textdomain( 'cpt-staff', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/'  );

	} // end __construct



	/**
	 * Register Metaboxes
	 *
	 * @access public
	 * @since 1.0
	 * @author Ralf Hortt
	 **/
	public function add_meta_boxes()
	{

		add_meta_box( 'staff-meta', __( 'Information', 'cpt-staff' ), array( $this, 'staff_meta' ), 'staff', 'normal' );

	} // add_meta_boxes



	/**
	 * Add custom columns
	 *
	 * @access public
	 * @param array $columns Columns
	 * @return array Columns
	 * @since 1.0
	 * @author Ralf Hortt
	 **/
	public function manage_edit_staff_columns( $columns )
	{

		$columns = array(
			'cb' => '<input type="checkbox" />',
			'thumbnail' => __( 'Thumbnail' ),
			'title' => __( 'Title' ),
			'phone' => __( 'Phone', 'cpt-staff' ),
			'mobile' => __( 'Mobile', 'cpt-staff' ),
			'fax' => __( 'Fax', 'cpt-staff' ),
			'email' => __( 'E-Mail', 'cpt-staff' ),
		);

		return $columns;

	} // end manage_edit_staff_columns



	/**
	 * Print custom columns
	 *
	 * @access public
	 * @param str $column Column name
	 * @param int $post_id Post ID
	 * @since 1.0
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

	} // manage_staff_posts_custom_column



	/**
	 * Update messages
	 *
	 * @access public
	 * @param array $messages Messages
	 * @return array Messages
	 * @since 1.0
	 * @author Ralf Hortt
	 **/
	public function post_updated_messages( $messages ) {

		global $post, $post_ID;

		$messages['staff'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __( 'Staff updated. <a href="%s">%s</a>', 'cpt-staff' ), esc_url( get_permalink($post_ID) ), __( 'View Staff', 'cpt-staff' ) ),
			2 => __( 'Custom field updated.' ),
			3 => __( 'Custom field deleted.' ),
			4 => __( 'Staff updated.', 'cpt-staff' ),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __( 'Staff restored to revision from %s', 'cpt-staff' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __( 'Staff published. <a href="%s">%s</a>', 'cpt-staff' ), esc_url( get_permalink($post_ID) ), __( 'View Staff', 'cpt-staff' ) ),
			7 => __( 'Staff saved.', 'cpt-staff' ),
			8 => sprintf( __( 'Staff submitted. <a target="_blank" href="%s">%s</a>', 'cpt-staff' ), esc_url( add_query_arg( 'preview', 'TRUE', get_permalink($post_ID) ) ), __( 'Preview Staff', 'cpt-staff' ) ),
			9 => sprintf( __( 'Staff scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">%s</a>', 'cpt-staff' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ), __( 'Preview Staff', 'cpt-staff' ) ),
			10 => sprintf( __( 'Staff draft updated. <a target="_blank" href="%s">%s</a>', 'cpt-staff' ), esc_url( add_query_arg( 'preview', 'TRUE', get_permalink($post_ID) ) ), __( 'Preview Staff', 'cpt-staff' ) ),
		);

		return $messages;

	} // end post_updated_messages



	/**
	 * Register post type
	 *
	 * @access public
	 * @since 1.0
	 * @author Ralf Hortt
	 */
	public function register_post_type()
	{

		$labels = array(
			'name' => _x( 'Staff', 'post type general name', 'cpt-staff' ),
			'singular_name' => _x( 'Staff', 'post type singular name', 'cpt-staff' ),
			'add_new' => _x( 'Add New', 'Staff', 'cpt-staff' ),
			'add_new_item' => __( 'Add New Staff', 'cpt-staff' ),
			'edit_item' => __( 'Edit Staff', 'cpt-staff' ),
			'new_item' => __( 'New Staff', 'cpt-staff' ),
			'view_item' => __( 'View Staff', 'cpt-staff' ),
			'search_items' => __( 'Search Staff', 'cpt-staff' ),
			'not_found' =>  __( 'No Staff found', 'cpt-staff' ),
			'not_found_in_trash' => __( 'No Staff found in Trash', 'cpt-staff' ),
			'parent_item_colon' => '',
			'menu_name' => __( 'Staff', 'cpt-staff' )
		);

		$args = array(
			'labels' => $labels,
			'public' => TRUE,
			'publicly_queryable' => TRUE,
			'show_ui' => TRUE,
			'show_in_menu' => TRUE,
			'query_var' => TRUE,
			'rewrite' => array( 'slug' => _x( 'staff', 'Post Type Slug', 'cpt-staff' )),
			'capability_type' => 'post',
			'has_archive' => TRUE,
			'hierarchical' => FALSE,
			'menu_position' => NULL,
			'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' )
		);

		register_post_type( 'staff', $args);

	} // end register_post_type



	/**
	 * Register taxonomy
	 *
	 * @access public
	 * @since 1.0
	 * @author Ralf Hortt
	 */
	public function register_taxonomy()
	{

		$labels = array(
			'name' => _x( 'Divisions', 'taxonomy general name', 'cpt-staff' ),
			'singular_name' => _x( 'Division', 'taxonomy singular name', 'cpt-staff' ),
			'search_items' =>  __( 'Search Divisions', 'cpt-staff' ),
			'all_items' => __( 'All Divisions', 'cpt-staff' ),
			'parent_item' => __( 'Parent Division', 'cpt-staff' ),
			'parent_item_colon' => __( 'Parent Division:', 'cpt-staff' ),
			'edit_item' => __( 'Edit Division', 'cpt-staff' ),
			'update_item' => __( 'Update Division', 'cpt-staff' ),
			'add_new_item' => __( 'Add New Division', 'cpt-staff' ),
			'new_item_name' => __( 'New Division Name', 'cpt-staff' ),
			'menu_name' => __( 'Divisions', 'cpt-staff' ),
		);

		register_taxonomy( 'division',array( 'staff' ), array(
			'hierarchical' => TRUE,
			'labels' => $labels,
			'show_ui' => TRUE,
			'show_admin_column' => TRUE,
			'query_var' => TRUE,
			'rewrite' => array( 'slug' => _x( 'division', 'Division Slug', 'cpt-staff' ) )
		));

	} // end register_taxonomy



	/**
	 * Shortcode [STAFF]
	 *
	 * @access public
	 * @param array $atts Attributes
	 * @return str Output
	 * @since 1.0
	 * @author Ralf Hortt
	 **/
	public function shortcode_staff( $atts )
	{

		extract( shortcode_atts( array(
			'division' => NULL,
			'ID' => NULL,
			'showposts' => -1,
			'template_file' => FALSE,

		), $atts ));

		$query = new WP_Query( 'post_status=publish&post_type=staff&order=ASC&orderby=menu_order&division=' . $division );

		if ( !$query->have_posts() )
			return;

		$output = '<div class="staff">';

		while ( $query->have_posts() ) : $query->the_post();

			if ( FALSE !== $template_file ) :
				get_template_part( 'partials/content', 'home' );
			else :
				$meta = get_post_meta( get_the_ID(), '_staff-meta', TRUE );

				$temp_output = '<div class="staff-single staff-' . get_the_ID() . ' clearfix">';
				$temp_output .= ( has_post_thumbnail() ) ? '<div class="staff-thumbnail"><div class="staff-mask">' . get_the_post_thumbnail() . '</div></div>' : '';
				$temp_output .= '<div class="staff-title">' . get_the_title() . '</div>';
				$temp_output .= ( '' != get_the_content() ) ? '<div class="staff-content">' . get_the_content() . '</div>' : '';
				$temp_output .= '<div class="staff-meta">';
				$temp_output .= ( $meta['title'] ) ? '<em>' . $meta['title'] . ' ' . strip_tags( get_the_term_list( get_the_ID(), 'division' ) ) . '</em><br>' : '';
				$temp_output .= ( $meta['phone'] ) ? '<strong> ' . __( 'Phone:', 'cpt-staff' ) . '</strong> ' . $meta['phone'] . '<br>' : '';
				$temp_output .= ( $meta['fax'] ) ? '<strong> ' . __( 'Fax:', 'cpt-staff' ) . '</strong> ' . $meta['fax'] . '<br>' : '';
				$temp_output .= ( $meta['mobile'] ) ? '<strong> ' . __( 'Mobile:', 'cpt-staff' ) . '</strong> ' . $meta['mobile'] . '<br>' : '';
				$temp_output .= ( $meta['email'] ) ? '<strong> ' . __( 'E-Mail:', 'cpt-staff' ) . '</strong> <a href="mailto:' . $meta['email'] . '">' . $meta['email'] . '</a><br>' : '';
				$temp_output .= '</div><!-- .staff-meta -->';
				$temp_output .= '</div><!-- .staff-' . get_the_ID() . ' -->';

				apply_filters( 'staff_shortcode', $temp_output, $meta );

				$output .= $temp_output;
			endif;

		endwhile;

		$output .= '</div><!-- .staff -->';

		return $output;

	} // end shortcode_staff



	/**
	 * Information meta box
	 *
	 * @access public
	 * @param obj $post Post object
	 * @since 1.0
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
				<th><label for="staff-gender"><?php _e( 'Gender:', 'cpt-staff' ); ?></label></th>
				<td>
					<label><input <?php checked( 'male', $meta['gender'] ) ?> type="radio" value="male" name="staff-gender" id="staff-gender-male"> <?php _e( 'Mr', 'cpt-staff' ); ?></label><br>
					<label><input <?php checked( 'female', $meta['gender'] ) ?> type="radio" value="female" name="staff-gender" id="staff-gender-female"> <?php _e( 'Ms', 'cpt-staff' ); ?></label>
				</td>
			</tr>
			<tr>
				<th><label for="staff-grade"><?php _e( 'Grade:', 'cpt-staff' ); ?></label></th>
				<td><input size="50" type="text" value="<?php echo $meta['grade'] ?>" name="staff-grade" id="staff-grade"></td>
			</tr>
			<tr>
				<th><label for="staff-first-name"><?php _e( 'First name:', 'cpt-staff' ); ?></label></th>
				<td><input size="50" type="text" value="<?php echo $meta['first-name'] ?>" name="staff-first-name" id="staff-first-name"></td>
			</tr>
			<tr>
				<th><label for="staff-last-name"><?php _e( 'Last name:', 'cpt-staff' ); ?></label></th>
				<td><input size="50" type="text" value="<?php echo $meta['last-name'] ?>" name="staff-last-name" id="staff-last-name"></td>
			</tr>
			<tr>
				<th><label for="staff-title"><?php _e( 'Role:', 'cpt-staff' ); ?></label></th>
				<td><input size="50" type="text" value="<?php echo $meta['title'] ?>" name="staff-title" id="staff-title"></td>
			</tr>
			<tr>
				<th><label for="staff-phone"><?php _e( 'Phone:', 'cpt-staff' ); ?></label></th>
				<td><input size="50" type="text" value="<?php echo $meta['phone'] ?>" name="staff-phone" id="staff-phone"></td>
			</tr>
			<tr>
				<th><label for="staff-fax"><?php _e( 'Fax:', 'cpt-staff' ); ?></label></th>
				<td><input size="50" type="text" value="<?php echo $meta['fax'] ?>" name="staff-fax" id="staff-fax"></td>
			</tr>
			<tr>
				<th><label for="staff-mobile"><?php _e( 'Mobile:', 'cpt-staff' ); ?></label></th>
				<td><input size="50" type="text" value="<?php echo $meta['mobile'] ?>" name="staff-mobile" id="staff-mobile"></td>
			</tr>
			<tr>
				<th><label for="staff-email"><?php _e( 'E-Mail:', 'cpt-staff' ); ?></label></th>
				<td><input size="50" type="text" value="<?php echo $meta['email'] ?>" name="staff-email" id="staff-email"></td>
			</tr>
			<?php do_action( 'staff-meta-after', $post, $meta ) ?>
		</table>
		<?php

		do_action( 'staff-meta-table-after', $post, $meta );

		wp_nonce_field( 'save-staff-meta', 'staff-meta-nonce' );

	} // end staff_meta



	/**
	 * Save Metabox
	 *
	 * @access public
	 * @param int $post_id Post ID
	 * @since 1.0
	 * @author Ralf Hortt
	 **/
	public function staff_save_metabox( $post_id )
	{

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( !wp_verify_nonce( $_POST['staff-meta-nonce'], 'save-staff-meta' ) )
			return;

		$meta = array(
			'gender' => sanitize_text_field( $_POST['staff-gender'] ),
			'first-name' => sanitize_text_field( $_POST['staff-first-name'] ),
			'last-name' => sanitize_text_field( $_POST['staff-last-name'] ),
			'grade' => sanitize_text_field( $_POST['staff-grade'] ),
			'grade' => sanitize_text_field( $_POST['staff-grade'] ),
			'title' => sanitize_text_field( $_POST['staff-title'] ),
			'phone' => sanitize_text_field( $_POST['staff-phone'] ),
			'mobile' => sanitize_text_field( $_POST['staff-mobile'] ),
			'fax' => sanitize_text_field( $_POST['staff-fax'] ),
			'email' => sanitize_text_field( $_POST['staff-email'] ),
		);

		if ( 'staff' == $_POST['post_type'] )
			update_post_meta( $post_id, '_staff-meta', apply_filters( 'save-staff-meta', $meta, $post_id ) );

	} // end staff_save_metabox



} // end Custom_Post_Type_Staff

new Custom_Post_Type_Staff;