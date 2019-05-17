<?php
/**
 * Get staff meta.
 *
 * @param string Key to retrieve; salutation | first-name | last-name | grade | role | title | room | phone | mobile | fax | email | url
 * @param int $post_id Post ID
 *
 * @return string Key to retrieve
 *
 * @since 2.0
 *
 * @author Ralf Hortt <me@horttcore.de>
 **/
function get_staff_meta($key, $post_id = false)
{
    $post_id = (false !== $post_id) ? $post_id : get_the_ID();

    $meta = get_post_meta($post_id, '_staff-meta', true);

    if (!$meta || !isset($meta[$key]) || '' === $meta[$key]) {
        return false;
    }

    return $meta[$key];
} // END get_staff_meta

/**
 * Echo staff meta.
 *
 * @param string Key to retrieve; salutation | first-name | last-name | grade | role | title | room | phone | mobile | fax | email | url
 * @param int $post_id Post ID
 *
 * @return void
 *
 * @since 2.0
 *
 * @author Ralf Hortt <me@horttcore.de>
 **/
function the_staff_meta($key, $before = '', $after = '', $post_id = false)
{
    $post_id = (false !== $post_id) ? $post_id : get_the_ID();
    $meta = get_staff_meta($key, $post_id);
    if (false !== $meta) {
        echo $before.$meta.$after;
    }
} // END the_staff_meta

/**
 * Has staff meta.
 *
 * @param string Key to retrieve; salutation | first-name | last-name | grade | role | title | room | phone | mobile | fax | email | url
 * @param int $post_id Post ID
 *
 * @return void
 *
 * @since 2.0.1
 *
 * @author Ralf Hortt <me@horttcore.de>
 **/
function has_staff_meta($key, $post_id = false)
{
    $post_id = (false !== $post_id) ? $post_id : get_the_ID();
    $meta = get_staff_meta($key, $post_id);

    return (false === $meta) ? false : true;
} // END has_staff_meta
