<?php

if (!defined('ABSPATH') || !is_admin())
{
  header('HTTP/1.1 403 Forbidden');
    exit('HTTP/1.1 403 Forbidden');
}

function minor_edits_admin_action_links($links)
{
  return array_merge(
    array(
      'settings' => '<a href="' . admin_url('options-general.php?page=minor-edits') . '">' . esc_html__('Settings', 'minor-edits') . '</a>'
    ),
    $links);
}
add_filter('plugin_action_links_' . plugin_basename('minor-edits/minor-edits.php'),
           'minor_edits_admin_action_links');

function minor_edits_add_options_submenu_page()
{
  add_submenu_page(
    'options-general.php',     // append to Settings sub-menu
    'Minor Edits Options',     // title
    'Minor Edits',             // menu label
    'manage_options',          // required role
    'minor-edits',             // options-general.php?page=minor-edits
    'minor_edits_options_page' // display page callback
  );
}
add_action('admin_menu', 'minor_edits_add_options_submenu_page');

function minor_edits_options_page()
{
  if (isset($_POST) &&
      isset($_POST['minor_edits_min_diff_title']) &&
      isset($_POST['minor_edits_min_diff_text']))
  {
    update_option('minor_edits_min_diff_title', $_POST['minor_edits_min_diff_title']);
    update_option('minor_edits_min_diff_text', $_POST['minor_edits_min_diff_text']);
    $saved_options = TRUE;
  } ?>
  <div class="wrap" style="max-width:52em">
    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    <p>This plugin will prevent minor modifications to your posts from updating their date of last modification.</p>
    <form method="post" action="options-general.php?page=minor-edits">
      <fieldset>
      <legend>Number of characters different to be considered a signigicant change</legend>
      <label>
        <span>In titles:</span>
        <input type="text" name="minor_edits_min_diff_title" id="minor_edits_min_diff_title" value="<?php print(esc_html(intval(get_option('minor_edits_min_diff_title', '5')))); ?>">
      </label>
      <label>
        <span>In the main text:</span>
        <input type="text" name="minor_edits_min_diff_text" id="minor_edits_min_diff_text" value="<?php print(esc_html(intval(get_option('minor_edits_min_diff_text', '50')))); ?>">
      </label>
      </fieldset>
      <style>fieldset legend{font-weight:bold}fieldset label{display:block}fieldset label span{display:inline-block;width:8em},fieldset input[type="text"]{width:5em}</style>
      <input type="submit" value="Save" class="button-primary">
      <?php if (isset($saved_options) && $saved_options) { ?><p style="color:green;display:inline;margin-left: 16px">Saved.</p> <?php } ?>
    </form>
    <hr/>
    <h3>Filtering the comparison texts</h3>
    <p>You can modify the old and new texts before they’re compared for differences. This allows you to strip out advertisement injection code, link changes, or other specific tidbits before text comparisons are done.</p>
    <p>See the file <samp>HACKING</samp> in the plugin installation directory for code samples and instructions.</p>
  </div>
<?php
}
