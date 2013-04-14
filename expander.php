<?php
/*
Plugin Name: Expander
Version: 0.1.1
Plugin URI: http://getbutterfly.com/
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
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/*
 * Usage: [wpex Read more]hidden text[/wpex]
 */

define('WPEX_PLUGIN_URL', WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)));
define('WPEX_PLUGIN_PATH', WP_PLUGIN_DIR . '/' . dirname(plugin_basename(__FILE__)));

function wpex_str_replace_once($needle, $replace, $haystack) {
	$pos = strpos($haystack, $needle);
	if($pos === false)
		return $haystack;

	return substr_replace($haystack, $replace, $pos, strlen($needle));
}

function wpex_process($content) {
	$offset = 0;
	$stag = '[wpex ';
	$etag = '[/wpex]';
	while(stripos($content, $stag, $offset)) {
		// string to replace
		$s = stripos($content, $stag, $offset);	
		$e = stripos($content, $etag, $s) + strlen($etag); 

		// inside data
		$ds = stripos($content, ']', $s) + 1;
		$de = $e - strlen($etag);

		// style tag
		$ss = $s + strlen($stag);
		$se = $ds - 1;

		$sstring = substr($content, $s, $e - $s);
		$sdesc = substr($content, $ss, $se - $ss); 
		$sdata = substr($content, $ds, $de - $ds);

		mt_srand((double)microtime() * 1000000);
		$rnum = mt_rand();

		$new_string = '<a class="wpex-link" style="display: none;" id="wpexlink' . $rnum . '" href="javascript:expand(document.getElementById(\'wpex' . $rnum . '\'))">' . $sdesc . '</a>' . "\n";
		$new_string .= '<div class="wpex_div" id="wpex' . $rnum . '">';
		$new_string .= '<script>expand(document.getElementById(\'wpex' . $rnum . '\')); expand(document.getElementById(\'wpexlink' . $rnum . '\'))</script>';

		$sdata = preg_replace('`^<br />`sim', '', $sdata);

		$content = wpex_str_replace_once($sstring, $new_string . $sdata . '</div>', $content);

		$offset = $s + 1;
	}

	return $content;
}

function wpex_javascript() {
	echo '<script>function expand(param) { param.style.display = (param.style.display == "none") ? "" : "none"; }</script>';
}

add_action('wp_head', 'wpex_javascript');
add_filter('the_content', 'wpex_process');
?>
