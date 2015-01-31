<?php
/**
 * Get staff meta
 *
 * @param str Key to retrieve; salutation | first-name | last-name | grade | role | title | room | phone | mobile | fax | email | url
 * @param int $post_id Post ID
 * @return str Key to retrieve
 * @since 2.0
 * @author Ralf Hortt <me@horttcore.de>
 **/
function get_staff_meta( $key, $post_id = FALSE )
{

	$post_id = ( FALSE !== $post_id ) ? $post_id : get_the_ID();

	$meta = get_post_meta( $post_id, '_staff-meta', TRUE );

	if ( !$meta || !isset( $meta[$key] ) || '' === $meta[$key] )
		return FALSE;

	return $meta[$key];

} // END get_staff_meta



/**
 * Echo staff meta
 *
 * @param str Key to retrieve; salutation | first-name | last-name | grade | role | title | room | phone | mobile | fax | email | url
 * @param int $post_id Post ID
 * @return void
 * @since 2.0
 * @author Ralf Hortt <me@horttcore.de>
 **/
function the_staff_meta( $key, $post_id = FALSE )
{

	$post_id = ( FALSE !== $post_id ) ? $post_id : get_the_ID();
	$meta = get_staff_meta( $key, $post_id );
	if ( FALSE !== $meta )
		echo $meta;

} // END the_staff_meta



/**
 * Has staff meta
 *
 * @param str Key to retrieve; salutation | first-name | last-name | grade | role | title | room | phone | mobile | fax | email | url
 * @param int $post_id Post ID
 * @return void
 * @since 2.0.1
 * @author Ralf Hortt <me@horttcore.de>
 **/
function has_staff_meta( $key, $post_id = FALSE )
{

	$post_id = ( FALSE !== $post_id ) ? $post_id : get_the_ID();
	$meta = get_staff_meta( $key, $post_id );

	return ( FALSE === $meta ) ? FALSE : TRUE;

} // END has_staff_meta
