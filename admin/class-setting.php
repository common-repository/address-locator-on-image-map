<?php
class ALIM_Setting
{
    public function __construct()
    {
        // Backend controllers.
        add_action('admin_menu', array(
            $this,
            'alim_stting_page'
        ));
        
    }
    
    public function alim_stting_page()
    {
        $defaultp=add_submenu_page('edit.php?post_type=office', 'Setting', 'Setting', 'manage_options', 'store-setting', array(
            $this,
            'alim_setting_callback'
        ));
        add_action($defaultp, array(
            $this,
            'alim_map_includes'
        ));
    }
   
    function alim_map_includes() {

    /** Register */
	wp_register_script( 'jscolor', plugins_url( 'assets/js/jscolor.js' , __FILE__ ) );
	wp_register_script( 'st_jssetting', plugins_url( 'assets/js/settings.js' , __FILE__ ) );
	wp_enqueue_script( 'jscolor' );
	wp_enqueue_script( 'st_jssetting' );
 	wp_enqueue_script('media-upload'); //Provides all the functions needed to upload, validate and give format to files.
    wp_enqueue_script('thickbox'); //Responsible for managing the modal window.
  	wp_enqueue_style( 'farbtastic' );
    wp_enqueue_script( 'farbtastic' );
  	wp_enqueue_script('imgmap_script', plugins_url('/assets/js/custom.js', __FILE__), array('jquery'), '', true);  
	wp_enqueue_style('imgmap_style', plugins_url('/assets/css/imagemap.css', __FILE__));
	wp_enqueue_style('thickbox'); //Provides the styles needed for this window.
	wp_localize_script('my-ajax-request', 'MyAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php')
        ));
	
   }
    
    public function alim_setting_callback()
    {
        
        $settings = get_option("st_theme_settings");
       

// Gernal setting Option
?>

<div class="wrap">
  <h2>Store Locator Settings</h2>
  <?php
        
        if (isset($_GET['tab']))
            alim_admin_tabs($_GET['tab']);
        else
            alim_admin_tabs('general');
        if (isset($_GET['tab'])) {
            $tab = $_GET['tab'];
        }
?>
  <div id="poststuff">
    <?php
        wp_nonce_field("ilc-settings-page");
        
        if ($_GET['page'] == 'store-setting') {
            
            if (isset($_GET['tab']))
                $tab = $_GET['tab'];
            else
                $tab = 'general';
            
            
            switch ($tab) {
                case 'general':
                    
?>
    <h3>General Settings</h3>
    <div class="msg">Map Image successfully uploaded</div>
    <table id="default-settings-table" class="stuffbox" width="100%">
      <tr>
        <td><p><strong>Upload your Map Image from here</strong></p>
          <form class="ink_image" id="ink_image" method="post" action="#">
            <input type="text" name="path" class="image_path" value="<?php
                    echo ALIM_LOCATOR_MAP;
?>" id="image_path">
            <input type="button" value="Upload Image" class="button-primary" id="upload_image"/>
            Upload your Image from here.
            <div id="show_upload_preview"> <br>
              <br>
              <?php
                    if (!empty($img_path)) {
?>
              <img src="<?php echo $img_path; ?>">
             
              <input type="submit" name="remove" value="Remove Image" class="button-secondary" id="remove_image"/>
              <?php
                    }
?>
            </div>
               <?php   wp_nonce_field('_filter_nonce', 'filter_nonce');?>
            <input type="submit" name="submit" class="save_path button-primary" id="submit_button" value="Save Setting">   <img src="<?php echo  plugin_dir_url(__FILE__) . 'assets/images/ajax-loader.gif' ?>" class="loading" > 
          </form>
          <br>
          <br></td>
      </tr>
    </table>
    <?php
                    break;
                case 'filters':
// Filter setting Option
?>
    <h3>Default Map Settings </h3>
    <div class="msg">Settings successfully uploaded</div>
    <table width="100%" id="default-settings-table" class="stuffbox">
      <tr valign="top">
        <td nowrap="nowrap" scope="row"><strong>
          <form class="ink_filter" id="ink_filter" method="post" action="#">
            <p>Values will be pre-selected when creating a new map.</p>
            <table class="form-table">
            <tr>
              <th><label for="st_ga">Country:</label></th>
              <td><input type="checkbox" id="country" <?php
                    if (get_option("st_locator_country") == '1') {
?>checked <?php
                    }
?> name="country" ></td>
            </tr>
            <tr>
              <th><label for="st_ga">State:</label></th>
              <td><input type="checkbox" id="state" <?php
                    if (get_option("st_locator_state") == '1') {
?>checked <?php
                    }
?> name="state" ></td>
            </tr>
            <tr>
              <th><label for="st_ga">City:</label></th>
              <td><input type="checkbox" id="city" <?php
                    if (get_option("st_locator_city") == '1') {
?>checked <?php
                    }
?> name="city" ></td>
            </tr>
            <tr>
              <th><label for="st_ga">Address:</label></th>
              <td><input type="checkbox" id="address"   <?php
                    if (get_option("st_locator_address") == '1') {
?>checked <?php
                    }
?> name="address" ></td>
            </tr>
            <tr>
              <th><label for="st_ga">
               <?php   wp_nonce_field('_filter_nonce', 'filter_nonce');?>
                  <input type="submit" name="submit" class="save_path button-primary" id="submit_button" value="Save Setting">
                  <img src="<?php echo  plugin_dir_url(__FILE__) . 'assets/images/ajax-loader.gif' ?>" class="loading" >
                </label></th>
              <td>
          </form></td>
      </tr>
    </table>
    </form>
    </td>
    </tr>
    <tr valign="top">
      <td scope="row">&nbsp;</td>
    </tr>
    </table>
    <?php
                    break;
                case 'design':

// Pin setting Option				


?>
    <form class="pin_setting" id="pin_setting" method="post" action="#">
      <div class="iwm-wrap">
        <h3>Display Settings</h3>
         <p> Edit the default settings for the maps. <br />
          When creating a map, you can choose to use the default visual settings or create custom ones.<br />
        </p>
        <table width="100%" border="0" cellspacing="10"  cellpadding="10">
            <tr>
          
          <td width="25%">
            <h3> Default Visual Settings </h3>
             <div class="msg">Settings successfully updated</div>
            <table width="100%" cellpadding="2" cellspacing="2" class="stuffbox" id="default-settings-table">
             <tr valign="top">
                <td width="10%" nowrap="nowrap" scope="row">&nbsp;</td>
                <td width="20%">&nbsp;</td>
              </tr>
              <tr valign="top">
                <td width="10%" nowrap="nowrap" scope="row"><strong>Background Color</strong></td>
                <td width="20%"><input type="text" name="st-settings_bg_color" class="color {hash:true, adjust:false}" value="<?php echo get_option('st-settings_bg_color'); ?>" onchange="drawVisualization();" /></td>
              </tr>
              <tr valign="top">
                <td width="10%" nowrap="nowrap" scope="row"><strong>Border Color</strong></td>
                <td width="20%"><input type="text" name="st-settings_border_color" class="color {hash:true, adjust:false}" value="<?php echo get_option('st-settings_border_color'); ?>" onchange="drawVisualization();" /></td>
              </tr>
              <tr valign="top">
                <td width="10%" nowrap="nowrap"><strong>Border Width (px)</strong></td>
                <td width="20%"><input name="st-settings_border_stroke" value="<?php echo get_option('st-settings_border_stroke'); ?>" size="5" onchange="drawVisualization();" type="number" min="0" max="100" /></td>
              </tr>
                <tr valign="top">
                <td width="10%" nowrap="nowrap" scope="row"><strong>Link Region Color</strong></td>
                <td width="20%"><input type="text" name="st-settings_act_color" class="color {hash:true, adjust:false}" value="<?php echo get_option('st-settings_act_color'); ?>" onchange="drawVisualization();" /></td>
              </tr>
               <tr valign="top">
                <td width="10%" nowrap="nowrap" scope="row">&nbsp;</td>
                <td width="20%">&nbsp;</td>
              </tr>                         
              <tr valign="top">
                <td width="10%" nowrap="nowrap" scope="row"><strong>Upload your PIN Image from here:</strong></td>
                <td width="20%"><input type="text" name="path" class="image_path" value="<?php echo get_option( 'st_locator_pin' ); ?>" id="image_path">
                  <input type="button" value="Upload Image" class="button-primary" id="upload_image"/>
                  <br>
                  Upload your Image from here. </td>
              </tr>
              <tr valign="top">
                <td width="10%" nowrap="nowrap" scope="row">&nbsp;</td>
                <td width="20%">&nbsp;</td>
              </tr>
               
              
            </table>
            <p class="submit">
            <?php   wp_nonce_field('_disply_nonce', 'disply_nonce');?>
              <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />   <img src="<?php echo  plugin_dir_url(__FILE__) . 'assets/images/ajax-loader.gif' ?>" class="loading" >
            </p></td>
            <td width="75%" valign="top"><!-- <h3>Default Settings Preview</h3>          <div id="visualization"></div> -->
              
              <h3>Custom Styles</h3>
              <table id="default-settings-table" class="stuffbox" width="100%">
                <tr>
                  <td><p><strong>Custom CSS</strong></p>
                    <p>
                      <textarea name="st-settings_custom_css" id="iwm_custom_css"><?php echo get_option('st-settings_custom_css');  ?></textarea>
                      Include this CSS in pages where maps are displayed.<br>
                      <span class="howto">If you want to include custom css together with your maps you can include the css here. </span> </p></td>
                </tr>
              </table></td>
          </tr>
        </table>
       
        <p>&nbsp; </p>
      </div>
    </form>
    <?php
                    break;
            }
            
        }
?>
  </div>
</div>
<?php
    }
}
$ALIM_Setting = new ALIM_Setting();


?>
