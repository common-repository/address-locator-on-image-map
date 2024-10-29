<?php
/**
 * Fired For Shortcode
 *
 * @since      1.0.0
 *
 * @package    address_locator_on_ Image_map
 * @subpackage address_locator_on_ Image_map/public
 */
 
class ALIM_Shortcode {
    public function __construct() {
		 add_shortcode( 'map_view',  array( $this, 'alim_location'));
	}
	public function alim_location( $atts ){
		wp_enqueue_script('imgmap_script', plugins_url('/assets/js/custom.js', __FILE__), array('jquery'), '', true);  
		wp_enqueue_style('imgmap_style', plugins_url('/assets/css/imagemap.css', __FILE__));
		wp_localize_script('imgmap_script', 'ajaxurl', array('ajaxurl' => site_url().'/wp-admin/admin-ajax.php')); 
		echo do_action("alim_office_search_form").'<div class="filterdiv"></div>';
		$args = array(
			"post_type" => "office",
			"posts_per_page" => -1
		);
		if (!empty($_REQUEST["searchOfficeChainlink"])) {
			if (!empty($_REQUEST['company'])) {
				$args['p'] = $_REQUEST['company'];
			}
			//Based On Country
			if (!empty($_REQUEST['country'])) {
				$args['meta_query'] = array(
					array(
						'key' => 'location_country',
						'value' => $_REQUEST['country'],
					)
				);
			}
            //Based On State
			if (!empty($_REQUEST['state'])) {
				$args['meta_query'] = array(
					array(
						'key' => 'location_state',
						'value' => $_REQUEST['state'],
					)
				);
			}
			//Based On City
			if (!empty($_REQUEST['city'])) {
				$args['meta_query'] = array(
					array(
        				'key' => 'location_city',
        				'value' => $_REQUEST['city'],
        			)
				);
			}    
		}
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        query_posts($args);
        if (have_posts()) :
            echo '<div class="parent_div map-wrapper clearfix" style="position:relative;">';
        ?>
        <style>
        <?php echo get_option('st-settings_custom_css'); ?>
        </style>
		<img src="<?php echo ALIM_LOCATOR_MAP; ?>" style="width:100%;" />
		<?php    
		while (have_posts()) : the_post();
		global $post;	
		$xcordinate = get_post_meta(get_the_ID(), 'location_x_cordinate' ,true);
		$ycordinate = get_post_meta(get_the_ID(), 'location_y_cordinate' ,true);
		if( $xcordinate != '' && $ycordinate != '' ){	
		?>
		<div class="office-dots"> 
			<a class="<?php if(get_option('st_locator_pin')=='') {?>pins <?php } ?>" rel="post-<?php echo $post->ID;?>" style="left:<?php echo $xcordinate;?>%; top:<?php echo $ycordinate;?>%;" href="javascript:;" >
				<img src="<?php echo get_option('st_locator_pin'); ?>" >
			</a>
		</div>
		<div class="mid-america-div" id="post-<?php echo $post->ID;?>" ;="" style="left: <?php echo $xcordinate;?>%; top: <?php echo $ycordinate+3;?>%; 
display:none; background-color:<?php echo get_option( 'st-settings_bg_color');?>; border-color:<?php echo get_option('st-settings_border_color');?>; border:<?php echo get_option('st-settings_border_stroke');?>px solid <?php echo get_option('st-settings_border_color');?>; 
"> 
			<a href="javascript: void(0);" class="close">Close</a>
			<div class="office-text" style="background-color:<?php echo get_option( 'st-settings_bg_color');?>"> <span>OFFICES LOCATED IN:</span><br>
				<br>
				<?php echo alim_getmeta('location_address');?><br>
				<br>
			</div>
			<a style="background-color:<?php echo get_option( 'st-settings_act_color');?>" href="<?php echo get_post_meta(get_the_ID(), 'location_url' ,true);?>" class="button">WEBSITE</a> <i></i>
		</div>
		<?php
		}
		endwhile;
		echo '</div>';
		wp_reset_query();
		endif;
	}
}
$ALIM_Shortcode =new ALIM_Shortcode();
?>
