<?php
/**
 * Setting Page Ajax Request
 * @since      1.0.0
 *
 * @package    address_locator_on_ Image_map
 */
function alim_getmeta( $value ) {
    global $post;
    $field = get_post_meta( $post->ID, $value, true );
    if ( ! empty( $field ) ) {
        return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
    } else {
        return false;
    }
}
function alim_admin_tabs( $current = 'homepage' ) {
    $tabs = array( 'general' => 'General Setting', 'filters' => 'Filters Setting', 'design' => 'Design Option' );
    $links = array();
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='admin.php?page=store-setting&tab=$tab'>$name</a>";
    }
    echo '</h2>';
}
add_action('wp_ajax_map_image', 'alim_mapimage'); // executed when logged in
add_action('wp_ajax_map_image', 'alim_mapimage');

function alim_mapimage() {
	$data=$_POST;	
	if (!isset($_POST['filter_nonce']) || !wp_verify_nonce($_POST['filter_nonce'], '_filter_nonce'))
     {
     	echo 'error';
     }else{

    if(isset($data['country'])){
		update_option( 'st_locator_country', esc_attr($data['country']) );
	}
	if(isset($data['state'])){
		update_option( 'st_locator_state', esc_attr($data['state']) );
	}
	if(isset($data['city'])){
		update_option( 'st_locator_city', esc_attr($data['city']) );
	}
	if(isset($data['address'])){
		update_option( 'st_locator_address', esc_attr($data['address']) );
	}
	if(isset($data['image'])){
		update_option( 'ALIM_LOCATOR_MAP', esc_attr($data['image']) );
	}
	echo 'done';
     }      

	die();
}
add_action('wp_ajax_pin_setting', 'alim_pinsetting'); //Executed when logged in
add_action('wp_ajax_pin_setting', 'alim_pinsetting');
function alim_pinsetting() {
	$data=$_POST;
	 if (!isset($_POST['disply_nonce']) || !wp_verify_nonce($_POST['disply_nonce'], '_disply_nonce'))
            {
            	echo 'error';
            }else{

	if($_GET['action']=='pin_setting'){
		if(isset($data['st-settings_custom_css'])){
			update_option( 'st-settings_custom_css', esc_attr($data['st-settings_custom_css']) ); 
		}
		if(isset($data['st-settings_act_color'])){
			update_option( 'st-settings_act_color', esc_attr($data['st-settings_act_color']) ); 
		}
		if(isset($data['st-settings_bg_color'])){
			update_option( 'st-settings_bg_color', esc_attr($data['st-settings_bg_color']) );
		}
		if(isset($data['st-settings_border_color'])){
			update_option( 'st-settings_border_color', esc_attr($data['st-settings_border_color']) ); 
		}
		if(isset($data['st-settings_border_stroke'])){
			update_option( 'st-settings_border_stroke', esc_attr($data['st-settings_border_stroke']) ); 
		}
		if(isset($data['st-settings_height'])){
			update_option( 'st-settings_height', esc_attr($data['st-settings_height']) ); 
		}
		if(isset($data['st-settings_ina_color'])){
			update_option( 'st-settings_ina_color', esc_attr($data['st-settings_ina_color']) ); 
		}
		if(isset($data['path'])){
			update_option( 'st_locator_pin', esc_attr($data['path']) );
		}
		echo 'done';
	}
	die();
	}
}