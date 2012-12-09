<?php
/*
Plugin Name: Custom Post Type Staff
Plugin URI: http://horttcore.de.de
Description: Custom Post Type Staff
Version: 0.1
Author: Ralf Hortt
Author URI: http://horttcorte.de
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
	 * @return void
	 * @author Ralf Hortt
	 **/
	function __construct()
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
	}



	/**
	 * Register Metaboxes
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function add_meta_boxes()
	{
		add_meta_box( 'staff-meta', __( 'Information', 'cpt-staff' ), array( $this, 'staff_meta' ), 'staff', 'normal' );
	}



	/**
	 * Add custom columns
	 *
	 * @access public
	 * @return void
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
			'division' => __( 'Division', 'cpt-staff' )
		);

		return $columns;
	}



	/**
	 * Print custom columns
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function manage_staff_posts_custom_column( $column, $post_id )
	{
		global $post;

		switch( $column ) :

			case 'thumbnail' :
				if ( !has_post_thumbnail( $post_id )) :
					$meta = get_post_meta( $post_id, '_staff-meta', TRUE );
					echo '<img src="http://www.gravatar.com/avatar/' . md5( $meta['email'] ) . '?d=mm" />';
				else :
					echo get_the_post_thumbnail( $post_id, 'thumbnail' );
				endif;
				break;

			case 'phone' :
				$meta = get_post_meta( $post_id, '_staff-meta', TRUE );
				echo $meta['phone'];
				break;

			case 'mobile' :
				$meta = get_post_meta( $post_id, '_staff-meta', TRUE );
				echo $meta['mobile'];
				break;

			case 'fax' :
				$meta = get_post_meta( $post_id, '_staff-meta', TRUE );
				echo $meta['fax'];
				break;

			case 'email' :
				$meta = get_post_meta( $post_id, '_staff-meta', TRUE );
				echo '<a href="mailto:' . $meta['email'] . '">' . $meta['email'] . '</a>';
				break;

			case 'division' :
				$terms = get_the_term_list( $post_id, 'division' );
				echo strip_tags( $terms );
				break;

			default :
				break;

		endswitch;
	}



	/**
	 * Update messages
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function post_updated_messages( $messages ) {
		global $post, $post_ID;

		$messages['staff'] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __('Staff updated. <a href="%s">View Staff</a>', 'cpt-staff'), esc_url( get_permalink($post_ID) ) ),
			2 => __('Custom field updated.', 'cpt-staff'),
			3 => __('Custom field deleted.', 'cpt-staff'),
			4 => __('Staff updated.', 'cpt-staff'),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __('Staff restored to revision from %s', 'cpt-staff'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __('Staff published. <a href="%s">View Staff</a>', 'cpt-staff'), esc_url( get_permalink($post_ID) ) ),
			7 => __('Staff saved.', 'cpt-staff'),
			8 => sprintf( __('Staff submitted. <a target="_blank" href="%s">Preview Staff</a>', 'cpt-staff'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9 => sprintf( __('Staff scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Staff</a>', 'cpt-staff'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( __('Staff draft updated. <a target="_blank" href="%s">Preview Staff</a>', 'cpt-staff'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);

		return $messages;
	}



	/**
	 *
	 * POST TYPES
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 */
	public function register_post_type() 
	{
		$labels = array(
			'name' => _x('Staff', 'post type general name', 'cpt-staff'),
			'singular_name' => _x('Staff', 'post type singular name', 'cpt-staff'),
			'add_new' => _x('Add New', 'Staff', 'cpt-staff'),
			'add_new_item' => __('Add New Staff', 'cpt-staff'),
			'edit_item' => __('Edit Staff', 'cpt-staff'),
			'new_item' => __('New Staff', 'cpt-staff'),
			'view_item' => __('View Staff', 'cpt-staff'),
			'search_items' => __('Search Staff', 'cpt-staff'),
			'not_found' =>  __('No Staff found', 'cpt-staff'),
			'not_found_in_trash' => __('No Staff found in Trash', 'cpt-staff'), 
			'parent_item_colon' => '',
			'menu_name' => __('Staff', 'cpt-staff')
		);
		
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'show_in_menu' => true, 
			'query_var' => true,
			'rewrite' => array( 'slug' => _x('staff', 'Post Type Slug', 'cpt-staff')),
			'capability_type' => 'post',
			'has_archive' => true, 
			'hierarchical' => true,
			'menu_position' => null,
			'supports' => array('title', 'editor', 'thumbnail', 'page-attributes')
		); 
		
		register_post_type( 'staff', $args);
	}



	/**
	 *
	 * CUSTOM TAXONOMY
	 *
	 * @access public
	 * @return void
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

		register_taxonomy('division',array('staff'), array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => _x('division', 'Division Slug', 'cpt-staff') )
		));
	}



	/**
	 * Shortcode [STAFF]
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function shortcode_staff( $atts )
	{
		extract( shortcode_atts( array(
			'division' => null,
			'ID' => null,
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
	}


	/**
	 * Title Metabox
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function staff_meta( $post )
	{
		$meta = get_post_meta( $post->ID, '_staff-meta', TRUE );
		?>
		<table class="form-table">
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
		</table>
		<?php
	}



	/**
	 * Save Metabox
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function staff_save_metabox( $post_id )
	{
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return;

		$meta = array(
			'title' => $_POST['staff-title'],
			'phone' => $_POST['staff-phone'],
			'mobile' => $_POST['staff-mobile'],
			'fax' => $_POST['staff-fax'],
			'email' => $_POST['staff-email']
		);
		
		if ( 'staff' == $_POST['post_type'] )
			update_post_meta( $post_id, '_staff-meta', $meta );
	}



}

new Custom_Post_Type_Staff;