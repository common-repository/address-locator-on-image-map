<?php

/**
 * Fired During Plugin Activation
 * @since      1.0.0
 *
 * @package    address_locator_on_ Image_map
 * @subpackage address_locator_on_ Image_map/includes
 */
 
class ALIM_activator {
   public static function activate() {
		update_option('ALIM_LOCATOR_MAP', ALIM_LOCATOR_MAP);
        update_option('ALIM_LOCATOR_VERSION', ALIM_LOCATOR_VERSION);
	} 
}

