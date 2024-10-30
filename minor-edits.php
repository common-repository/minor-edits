<?php
/*
Plugin Name: Minor Edits
Plugin URI:  https://www.ctrl.blog/topic/wordpress
Description: Don’t update a post’s “modified” timestamp for insignificant edits.
Version:     1.0.2
Author:      Geeky Software
Author URI:  https://www.ctrl.blog/topic/wordpress
License:     GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

if (!defined('ABSPATH'))
{
  header('HTTP/1.1 403 Forbidden');
    exit('HTTP/1.1 403 Forbidden');
}

if (is_admin())
{
  require_once(dirname(__file__) . '/admin.php');
}


function minor_edits_revert_post_modified($post_new, $postarr)
{

  // No modification date if post isn’t published yet. Ensures fast
  // response times when editing drafts and scheduled posts, and when
  // publishing post for the first time.
  if ($post_new['post_status'] !== 'publish')
  {
    return $post_new;
  }

  $post_old = get_post($post_new['post_ID'], ARRAY_A);

  // Verify that posts have content and enforce upper character/memory limit
  if (isset($post_new['post_content']) &&
      isset($post_old['post_content']) &&
      max(strlen($post_new['post_content']),
          strlen($post_old['post_content'])) >= 40000)
  {
    return $post_new;
  }

  // Verify that post name (slug), status, and date haven’t changed
  if ($post_new['post_name']     == $post_old['post_name']     &&
      $post_new['post_date']     == $post_old['post_date']     &&
      $post_new['post_date_gmt'] == $post_old['post_date_gmt'] &&
      $post_new['post_status']   == $post_old['post_status']   &&
      isset($post_new['post_modified'])                        &&
      isset($post_new['post_modified_gmt'])                    &&
      isset($post_old['post_modified'])                        &&
      isset($post_old['post_modified_gmt']))
  {

    // Modification date before publishing date
    if ($post_new['post_date_gmt'] > $post_new['post_modified_gmt'] ||
        $post_new['post_date_gmt'] > $post_old['post_modified_gmt'])
    {
      return $post_new;
    }

    $min_changed_title_chars = intval(get_option('minor_edits_min_diff_title', '5'));
    $min_changed_btext_chars = intval(get_option('minor_edits_min_diff_text', '50'));

    // Check the differences between the new and old post title and texts
    if (minor_edits_text_differences(
          $postarr['post_title'],
          $post_old['post_title']) <= $min_changed_title_chars &&
        minor_edits_text_differences(
          wp_unslash($post_new['post_excerpt'] . $post_new['post_content']),
                     $post_old['post_excerpt'] . $post_old['post_content']) <= $min_changed_btext_chars)
    {

      $post_new['post_modified']     = $post_old['post_modified'];
      $post_new['post_modified_gmt'] = $post_old['post_modified_gmt'];

      do_action('minor_edits_post_status_minor_update', $post_new, $post_old);
    }
    else
    {
      do_action('minor_edits_post_status_significant_update', $post_new, $post_old);
  } }

  return $post_new;
}

add_action('wp_insert_post_data', 'minor_edits_revert_post_modified', 10, 2);

/*
  PHP’s levenshtein() is limited to only 255 characters, so use this
  little concoction instead.
*/
function minor_edits_text_differences($str1, $str2)
{

  // filter to allow modifications of strings prior to comparison (to filter out URLs, etc.)
  $filtered_texts = apply_filters('minor_edits_text_diff_filter', array($str1, $str2));
  $str1 = $filtered_texts[0];
  $str2 = $filtered_texts[1];

  $longest_str = max(strlen($str1),
                     strlen($str2));

  $str_similar_char = similar_text($str1, $str2);

  return ($longest_str - $str_similar_char);
}

