<?php
/**
 * Plugin Name:       Library Book Search
 * Description:       This is simple book search plugin
 * Version:           1.0
 * Author:            Naveenkumar C
 * Author URI:        https://profiles.wordpress.org/cnaveenkumar
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       librarybooksearch
 * Domain Path:       /languages
 */
 
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, public-facing site hooks and widget.
 */
require plugin_dir_path( __FILE__ ) .'includes/class-librarybooksearch.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 */
function Book_Search_run() {

	$plugin = new librarybooksearch();
	$plugin->run();

}
Book_Search_run();