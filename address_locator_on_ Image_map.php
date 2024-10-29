<?php
/*
* Plugin Name: Address Locator on Image Map
* Description:Address Locator on Image Map is a plugin for locating address on Image map with filter options
* Author: theemon
* Author URI: theemon.com/
* Plugin URI: theemon.com/
* Version: 1.0.0
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
* Address Locator on Image Map is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Address Locator on Image Map is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Address Locator on Image Map. If not, see http://www.gnu.org/licenses/gpl-2.0.html.
*/


define('ALIM_LOCATOR_VERSION', '1.0.0');
define('ALIM_LOCATOR_PLUGIN_URL', plugins_url('', __FILE__));
define("ALIM_PATH", plugin_dir_path( __FILE__ ));
define('ALIM_LOCATOR_MAP', plugin_dir_url(__FILE__) . 'admin/assets/images/map.png'); 
define('ALIM_LOCATOR_SITE_URL', get_site_url());
define('ALIM_LOCATOR_SLUG', 'store_locater_with_image_map');
define('ALIM_POST_TYPE', 'office'); 
//include classes and functions

require_once ( ALIM_PATH . 'admin/class-cpt.php' );
require_once ( ALIM_PATH . 'admin/class-store-filter.php' );
require_once ( ALIM_PATH . 'admin/class-setting.php' );
require_once ( ALIM_PATH . 'public/class-shortcode.php' );
require_once ( ALIM_PATH . 'coustom_function.php' );

require_once ( ALIM_PATH . 'includes/class-alimactivator.php' );
/** This action is documented in includes/class-wb-activator.php */
register_activation_hook(__FILE__, array('ALIM_activator', 'activate')); 

/**
 * The code that runs during plugin deactivation.
 */
require_once ( ALIM_PATH . 'includes/class-alimadeactivator.php' );
/** This action is documented in includes/class-wb-deactivator.php */
register_deactivation_hook(__FILE__, array('ALIM_deactivator', 'deactivate'));

?>
