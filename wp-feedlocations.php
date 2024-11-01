<?php
/*
Plugin Name: Feed Locations
Plugin URI: http://blog.slaven.net.au/wordpress-plugins/wordpress-feed-locations-plugin/
Description: Set the location of the RSS and Atom feeds in options.  Enables the use of services like FeedBurner without editing templates.
Author: Glenn Slaven
Version: 1.0
Author URI: http://blog.slaven.net.au/
*/

add_action ('admin_menu', 'wp_feed_location_changer_menu');
add_filter('feed_link', 'wp_feed_location_changer_filter', 10, 2);

function wp_feed_location_changer_menu() {
    if (function_exists('add_management_page')) {
		add_management_page('Feed Locations', 'Feed Locations', 8, __FILE__, 'wp_feed_location_changer_manage');
	}
}

function wp_feed_location_changer_filter($output, $feed) {
	$options = get_option('wp_feed_location_changer');

	if (!$feed && false != strpos($output, '/comments/')) {
	    $feed = 'comments_rss2';
	} elseif (!$feed) {
		$feed = 'rss2';
	}

	if (is_array($options) && strlen($options[$feed])) {
	    $output = $options[$feed];
	}
	return $output;
}

function wp_feed_location_changer_manage() {

	$feed_types = array(
		array('name' => 'RSS .92', 'value' => 'rss'),
		array('name' => 'RDF (aka RSS 1.0)', 'value' => 'rdf'),
		array('name' => 'RSS 2.0', 'value' => 'rss2'),
		array('name' => 'Atom 0.3', 'value' => 'atom'),
		array('name' => 'Comments RSS feed', 'value' => 'comments_rss2')
	);

	$options = get_option('wp_feed_location_changer');

	if ($_POST['wp_feed_location_changer']) {
		update_option('wp_feed_location_changer', $_POST['wp_feed_location_changer']);
		$options = get_option('wp_feed_location_changer');
	}
?>
<div class=wrap>
 <form method="post">
  <h2>Feed Locations</h2>
  <fieldset class="options">
  <p>Set the url for each of the feed versions.  If you leave a field blank it will use the default Wordpress settings.</p>
  <table width="100%" cellspacing="2" cellpadding="5" class="editform">
<?php
	foreach($feed_types as $ft) {
?>
  <tr>
   <th width="33%" scope="row" valign="top"><?=$ft['name']?>:</th>
   <td><input size="60" type="text" name="wp_feed_location_changer[<?=$ft['value']?>]" id="wp_feed_location_changer_<?=$ft['value']?>" value="<?=$options[$ft['value']]?>" /><?php if (!$options[$ft['value']]) { print "<br />Default: " . get_feed_link($ft['value']); } ?></td>
  </tr>
<?php
	}
?>
  </table>
  </fieldset>
  <div class="submit"><input type="submit" name="info_update" value="<?php _e('Update') ?> &raquo;" /></div>
 </form>
	<div style="background-color:rgb(238, 238, 238); border: 1px solid rgb(85, 85, 85); padding: 5px; margin-top: 10px;">
	<p>Did you find this plugin useful?  Please consider donating to help me continue developing it and other plugins.</p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="paypal@slaven.net.au">
<input type="hidden" name="item_name" value="Feed Locations Wordpress Plugin">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="currency_code" value="AUD">
<input type="hidden" name="tax" value="0">
<input type="hidden" name="bn" value="PP-DonationsBF">
<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but04.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
</form></div>
</div>
<?php
}
?>