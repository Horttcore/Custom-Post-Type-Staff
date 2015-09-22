<?php
/*
Plugin Name: Custom Post Type Staff
Plugin URI: http://horttcore.de
Description: A custom post type for managing staff
Version: 2.0.2
Author: Ralf Hortt
Author URI: http://horttcore.de
License: GPL2
*/

require( 'classes/custom-post-type-staff.php' );
// require( 'classes/custom-post-type-staff.widget.php' );
require( 'inc/template-tags.php' );

if ( is_admin() )
	require( 'classes/custom-post-type-staff.admin.php' );
