<?php
/*
Plugin Name: Expander
Version: 0.2.1
Plugin URI: http://getbutterfly.com/wordpress-plugins/wordpress-expander/
Description: Text expander plugin. Just like popcorn. Click and pop.
Author: Ciprian Popescu
Author URI: http://getbutterfly.com/

Copyright 2012, 2013 Ciprian Popescu (email: getbutterfly@gmail.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

/*
 * Usage: [wpex more="Read more" less="Read less"]hidden text[/wpex]
 */

define('WPEX_PLUGIN_URL', WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)));
define('WPEX_PLUGIN_PATH', WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)));
define('WPEX_VERSION', '0.2.1');

function wpex_register_shortcodes() {
   add_shortcode('wpex', 'wpex_main');
}

function wpex_main($atts, $content = null) {
	extract(shortcode_atts(array(
		'more' => 'Read more',
		'less' => 'Read less'
	), $atts));

	mt_srand((double)microtime() * 1000000);
	$rnum = mt_rand();

	$new_string = '<a onclick="wpex_toggle(' . $rnum . ', \'' . $more . '\', \'' . $less . '\'); return false;" class="wpex-link" id="wpexlink' . $rnum . '" href="#">' . $more . '</a>' . "\n";
	$new_string .= '<div class="wpex_div" id="wpex' . $rnum . '" style="display: none;">' . $content . '</div>';

	return wpautop($new_string);
}

function wpex_javascript() {
	echo '<script>
	function expand(param) {
		param.style.display = (param.style.display == "none") ? "block" : "none";
	}
	function wpex_toggle(id, more, less) {
		el = document.getElementById("wpexlink" + id);
		el.innerHTML = (el.innerHTML == more) ? less : more;
		expand(document.getElementById("wpex" + id));
	}
	</script>';
}

add_action('wp_head', 'wpex_javascript');
add_action('init', 'wpex_register_shortcodes');
?>
