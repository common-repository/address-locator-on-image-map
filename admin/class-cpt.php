<?php
/************************************************
 *  custom post type and Meta box for Store Locator
 ************************************************/
class ALIM_Backend
{
    public function __construct(){
        // Back End Controllers.
        add_action('init', array(
            $this,
            'alim_cpt_office'
        ));
        add_action('add_meta_boxes', array(
            $this,
            'alim_add_meta_box'
        ));
        add_action('save_post', array(
            $this,
            'alim_location_save'
        ));
    }
    
	//Register Post type     
    public function alim_cpt_office(){
		register_post_type(ALIM_POST_TYPE, array(
            'labels' => array(
				'name' => __('Stores'),
				'singular_name' => __('Store'),
				'add_new' => __('Add new Store '),
				'all_items' => __('All Store '),
				'add_new_item' => __('Add new Store '),
				'edit_item' => __('Edit Store '),
				'new_item' => __('New Store '),
				'view_item' => __('View Store '),
				'search_items' => __('Search Store '),
				'not_found' => __('Store Name not '),
				'not_found_in_trash' => __('Image map not found in trash')
			),
            'public' => true,
            'menu_icon' =>  plugin_dir_url(__FILE__) . 'assets/images/imagemap_icon.png',
            'exclude_from_search' => true,
            'has_archive' => true,
            'supports' => array(
                'title'
            )
        ));
    }
	
    //Add Meta box 
    public function alim_add_meta_box(){
        add_meta_box('location-location', __('Location', 'location'), array(
            $this,
            'alim_html'
        ), 'office', 'normal', 'default');
    }
	
    /* Create the metabox for Store */
    public function alim_html($post){
        wp_nonce_field('_location_nonce', 'location_nonce');
        $countrys = include('countries.php');
        wp_localize_script('my-ajax-request', 'MyAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php')
        ));
       	wp_enqueue_script('imgmap_script', plugins_url('/assets/js/custom.js', __FILE__), array('jquery'), '', true);  
		wp_enqueue_style('imgmap_style',plugins_url('/assets/css/imagemap.css', __FILE__));
?>
<p>
	<label for="location_Country"><?php _e('Country', 'location'); ?></label>
	<br>
	<select name="location_country" id="location_country" >
		<option value="">Please Select</option>
		<?php
			foreach ($countrys as $key => $value) {
            
			?>
			<option value="<?php echo $key; ?>" <?php
			if (alim_getmeta('location_country') == $key) { ?> selected="selected"<?php  } ?>><?php echo $value; ?></option>
			<?php
			}
		?>
  </select>
</p>
<p>
	<label for="location_State"><?php	_e('State', 'location'); ?></label>
	<br>
	<input type="text" name="location_state" id="location_state" value="<?php echo esc_html(alim_getmeta('location_state')); ?>">
</p>
<p>
	<label for="location_City"><?php _e('City', 'location'); ?></label>
	<br>
	<input type="text" name="location_city" id="location_city" value="<?php echo esc_html(alim_getmeta('location_city')); ?>">
</p>
<p>
	<label for="location_Address"><?php _e('Address', 'location'); ?> </label>
	<br>
	<textarea name="location_address" id="location_address" ><?php echo esc_textarea(alim_getmeta('location_address'));?></textarea>
</p>
<p>
	<label for="location_url"><?php _e('Url', 'url'); ?></label>
	<br>
	<input type="text" name="location_url" id="location_url" value="<?php	echo esc_url(alim_getmeta('location_url'));	?>">
</p>
<p>
	<label for="location_x_cordinate"><?php	_e('x_cordinate', 'location'); ?></label>
  <br>
  <input type="text" name="location_x_cordinate" id="location_x_cordinate" value="<?php	echo esc_html(alim_getmeta('location_x_cordinate'));	?>">
</p>
<p>
	<label for="location_y_cordinate"><?php	_e('y_cordinate', 'location');	?>	</label>
	<br>
	<input type="text" name="location_y_cordinate" id="location_y_cordinate" value="<?php	echo esc_html(alim_getmeta('location_y_cordinate'));	?>">
</p>
<div id="results" style="font-weight:bold;">Click on the image to get co-ordinates</div>
<div class="imagemap" style="width:100%;">
	<?php
		if (alim_getmeta('location_x_cordinate') != '' && alim_getmeta('location_y_cordinate') != '') {
	?>
	<div id="store-map" style="position:relative; ">
		<img src="<?php	echo ALIM_LOCATOR_MAP;?>" style="width:100%; height:100%;" > 
		<img id="marker" class="marker" src="<?php	echo  plugin_dir_url(__FILE__) . 'assets/images/imagemap_icon.png'?>" style="position:absolute; display: inline; left: <?php
            echo esc_html(alim_getmeta('location_x_cordinate'));
?>%; top: <?php
		echo esc_html(alim_getmeta('location_y_cordinate'));
?>%;"> 
	</div>
  <?php
	}	else {
	?>
	<div id="store-map"  style="position:relative; width:100%;"> 
		<img src="<?php	echo ALIM_LOCATOR_MAP;	?>" width="100%"> 
		<img src="<?php	echo plugin_dir_url(__FILE__) . 'assets/images/marker.png';	?>" style="position:absolute"  class="marker" id="marker"  /> 
	</div>
	<?php
		}
	?>
</div>
<?php
	}
	// Save Meta values 
    public function alim_location_save($post_id){

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;
        if (!isset($_POST['location_nonce']) || !wp_verify_nonce($_POST['location_nonce'], '_location_nonce'))
            return;
        if (!current_user_can('edit_post', $post_id))
            return;   
        if (isset($_POST['location_x_cordinate']))
            update_post_meta($post_id, 'location_x_cordinate', esc_attr($_POST['location_x_cordinate']));
        if (isset($_POST['location_y_cordinate']))
            update_post_meta($post_id, 'location_y_cordinate', esc_attr($_POST['location_y_cordinate']));
        if (isset($_POST['location_country']))
            update_post_meta($post_id, 'location_country', esc_attr($_POST['location_country']));
        if (isset($_POST['location_state']))
            update_post_meta($post_id, 'location_state', esc_attr($_POST['location_state']));
        if (isset($_POST['location_city']))
            update_post_meta($post_id, 'location_city', esc_attr($_POST['location_city']));
        if (isset($_POST['location_address']))
            update_post_meta($post_id, 'location_address', esc_textarea($_POST['location_address']));
        if (isset($_POST['location_url']))
			update_post_meta($post_id, 'location_url', esc_url($_POST['location_url']));
        
    }
}
$ALIM_Backend = new ALIM_Backend();
?>