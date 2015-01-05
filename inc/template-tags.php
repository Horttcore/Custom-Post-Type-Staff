<?php
/**
 * Get staff meta
 *
 * @param str Key to retrieve; salutation | first-name | last-name | grade | role | title | phone | mobile | fax | email
 * @param int $post_id Post ID
 * @return str Key to retrieve
 * @since 2.0
 * @author Ralf Hortt <me@horttcore.de>
 **/
function get_staff_meta( $key, $post_id = FALSE )
{

	$post_id = ( FALSE !== $post_id ) ? $post_id : get_the_ID();

	get_post_meta( $post_id, '_staff-meta', TRUE );

	return $meta[$key];

} // END get_staff_meta



/**
 * Echo staff meta
 *
 * @param str Key to retrieve; salutation | first-name | last-name | grade | role | title | phone | mobile | fax | email
 * @param int $post_id Post ID
 * @return void
 * @since 2.0
 * @author Ralf Hortt <me@horttcore.de>
 **/
function the_staff_meta( $key, $post_id = FALSE )
{

	$post_id = ( FALSE !== $post_id ) ? $post_id : get_the_ID();

	echo get_staff_meta( $key, $post_id );

} // END the_staff_meta
