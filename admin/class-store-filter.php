<?php
class ALIM_OfficeSearchFilter{

    public function __construct()
    {
        add_action('alim_office_search_form', array(
            &$this,
            'alim_formDisplay'
        ));
        //State
        add_action('wp_ajax_office_state', array(
            &$this,
            'alim_office_state'
        ));
        add_action('wp_ajax_nopriv_office_state', array(
            &$this,
            'alim_office_state'
        ));
        
        //City
        add_action('wp_ajax_office_city', array(
            &$this,
            'alim_office_city'
        ));
        add_action('wp_ajax_nopriv_office_city', array(
            &$this,
            'alim_office_city'
        ));
        
        //Address
        add_action('wp_ajax_office_address', array(
            &$this,
            'alim_office_address'
        ));
        add_action('wp_ajax_nopriv_office_address', array(
            &$this,
            'alim_office_address'
        ));
    }
	
	public function alim_office_address(){
        $args = array(
            "post_type" => "office",
            "posts_per_page" => -1
        );    
        $args['meta_query'] = array(
            array(
                'key' => 'location_city',
                'value' => $_REQUEST['country']
            )
        );
        $the_query = new WP_Query($args);
        $offices   = array();
	?>
    <option value="">By Address</option>
	<?php
	
        if ($the_query->have_posts()) {
			while ($the_query->have_posts()) {
				$the_query->the_post();
                $ofice_id = get_the_ID();
			?>
			<option value="<?php echo esc_attr($ofice_id); ?>"><?php	echo esc_attr(get_the_title()); ?></option>
    		<?php
            }
        }
        wp_reset_query();
        //Return $Offices;
        echo json_encode($offices);
        die();
        //[{v:'02905',f:'Providence'},1,'Providence']
    }
	public  function alim_office_state(){
        global $wpdb;
        $con     = $_POST['country'];
        $sqlCity = "SELECT  {$wpdb->prefix}posts.ID " . "FROM {$wpdb->prefix}posts  " . "INNER JOIN {$wpdb->prefix}postmeta ON ({$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id) " . "WHERE {$wpdb->prefix}posts.post_type = 'office'  AND {$wpdb->prefix}posts.post_status = 'publish' " . "AND ({$wpdb->prefix}postmeta.meta_key = 'location_country' AND {$wpdb->prefix}postmeta.meta_value='{$_REQUEST['country']}' )" . "GROUP BY {$wpdb->prefix}posts.ID ORDER BY {$wpdb->prefix}posts.post_date DESC";
        $resultsCity = $wpdb->get_results($sqlCity);
	?> 
	<option value="">By State</option>
        <?php
        $cityArr = array();
        foreach ($resultsCity as $city):
			$cityArr[] = esc_attr(get_post_meta($city->ID, 'location_state', true));
        endforeach;
        $cityArrU = array_unique($cityArr);
        sort($cityArrU);
        foreach ($cityArrU as $city):
		if (!empty($city)):
	?>
	<option value="<?php echo esc_attr($city); ?>"><?php echo esc_attr($city); ?></option>
	<?php endif; endforeach; die();}
	public function alim_office_city(){
        global $wpdb;
        $con = $_POST['country'];
        $sqlCity = "SELECT  {$wpdb->prefix}posts.ID " . "FROM {$wpdb->prefix}posts  " . "INNER JOIN {$wpdb->prefix}postmeta ON ({$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id) " . "WHERE {$wpdb->prefix}posts.post_type = 'office'  AND {$wpdb->prefix}posts.post_status = 'publish' " . "AND ({$wpdb->prefix}postmeta.meta_key = 'location_state' AND {$wpdb->prefix}postmeta.meta_value='{$_REQUEST['country']}' )" . "GROUP BY {$wpdb->prefix}posts.ID ORDER BY {$wpdb->prefix}posts.post_date DESC";
        $resultsCity = $wpdb->get_results($sqlCity);
	?>  
	<option value="">By City</option>
	<?php
		$cityArr = array();
        foreach ($resultsCity as $city):
			$cityArr[] = get_post_meta($city->ID, 'location_city', true);
        endforeach;
        $cityArrU = array_unique($cityArr);
        sort($cityArrU);
        foreach ($cityArrU as $city):
		if (!empty($city)):
	?>
	<option value="<?php echo esc_attr($city); ?>"><?php echo esc_attr($city); ?></option>
		<?php
		endif;
        endforeach;
        die();
    }
	public function alim_formDisplay()
    {
		?>
		<h2>Search for an <span> office</span> below</h2>
			<form method="post" action="" id="formSearchOffice" class="clearfix">
				<input type="hidden" name="searchOfficeChainlink" value="search" />
				<?php
				$country = get_option('st_locator_country');
				if ($country == 0) {
				$country = "display:none;";
				}
				?>
				<div style="<?php	echo $country;	?>" class="user-data select ">
					<select id="selectcontry"  name="country" >
						<option value="">By Country</option>
						<?php $this->alim_setcountry(); ?>
					</select>
				</div>
             <?php
				$st_locator_state = get_option('st_locator_state');
				if ($st_locator_state == 0) {
					$st_locator_state = "display:none;";
				}
			?>
            <div class="user-data select  " style="<?php echo esc_attr($st_locator_state); ?>">
                <select id="selectState"  name="state" onchange="showcity(this.value)"> 
					<option value="">By State</option>
					<?php $this->alim_setState(); ?>
                </select>
            </div>
            <?php
				$st_locator_city = get_option('st_locator_city');
				if ($st_locator_city == 0) {
					$st_locator_city = "display:none;";
				}	
			?>
            <div class="user-data select select-2 " style="<?php echo esc_attr($st_locator_city); ?>">
                <select id="selectCity" name="city" onchange="showaddress(this.value)">
                    <option value="">By City</option>
                    <?php $this->alim_setCity(); ?>
                </select>
            </div>
            <?php
				$st_locator_address = get_option('st_locator_address');
				if ($st_locator_address == 0) {
					$st_locator_address = "display:none;";
				}
			?>
            <div class="user-data select select-3"  style="<?php echo esc_attr($st_locator_address); ?>">
                <select id="selectCompany" name="company">
                    <option value="">By Company</option>
                    <?php $this->alim_setCompany(); ?>
                </select>
            </div>
            <div class="user-data submit-btn">
                <input id="buttonReset" type="submit" value="Search"  />
            </div>
            <span id='filterLoader'>Loading ...</span>
        </form>
        <?php
	}
    function alim_setcountry(){
        global $wpdb;
        $sqlState = "SELECT  {$wpdb->prefix}posts.ID FROM {$wpdb->prefix}posts  INNER JOIN {$wpdb->prefix}postmeta ON ({$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id) 
                WHERE {$wpdb->prefix}posts.post_type = 'office'  AND {$wpdb->prefix}posts.post_status = 'publish'
                AND {$wpdb->prefix}postmeta.meta_key = 'location_country'  GROUP BY {$wpdb->prefix}posts.ID ORDER BY {$wpdb->prefix}posts.post_date DESC";   
        $resultsState     = $wpdb->get_results($sqlState);
        $location_country = (isset($_REQUEST['country'])) ? $_REQUEST['country'] : "";
        $this->alim_setOption($resultsState, 'location_country', $location_country);
    }
	public  function alim_setState(){
        global $wpdb;
        $country  = $_REQUEST['country'];
        $sqlState = "SELECT  {$wpdb->prefix}posts.ID FROM {$wpdb->prefix}posts  INNER JOIN {$wpdb->prefix}postmeta ON ({$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id) 
                WHERE {$wpdb->prefix}posts.post_type = 'office'  AND {$wpdb->prefix}posts.post_status = 'publish'
                AND ({$wpdb->prefix}postmeta.meta_key = 'location_country' AND {$wpdb->prefix}postmeta.meta_value='{$_REQUEST['country']}' )
               GROUP BY {$wpdb->prefix}posts.ID ORDER BY {$wpdb->prefix}posts.post_date DESC";
        
        $resultsState = $wpdb->get_results($sqlState);
        $state        = (isset($_REQUEST['state'])) ? $_REQUEST['state'] : "";
        if (get_option("st_locator_country") != '1' || $_REQUEST['country'] != '') {
            echo $this->alim_setOption($resultsState, 'location_state', $state);
        }
    }
	public  function alim_setCity()
    {
        global $wpdb;
        if (isset($_REQUEST['state']) && $_REQUEST['state'] != "") {
            $sqlCity = "SELECT  {$wpdb->prefix}posts.ID " . "FROM {$wpdb->prefix}posts  " . "INNER JOIN {$wpdb->prefix}postmeta ON ({$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id) " . "WHERE {$wpdb->prefix}posts.post_type = 'office'  AND {$wpdb->prefix}posts.post_status = 'publish' " /* . "AND {$wpdb->prefix}postmeta.meta_key = 'city_value_key' " */ . "AND ({$wpdb->prefix}postmeta.meta_key = 'location_state' AND {$wpdb->prefix}postmeta.meta_value='{$_REQUEST['state']}' )" . "GROUP BY {$wpdb->prefix}posts.ID ORDER BY {$wpdb->prefix}posts.post_date DESC";
            $resultsCity = $wpdb->get_results($sqlCity);
            $city = (isset($_REQUEST['city'])) ? $_REQUEST['city'] : "";
            if (get_option("st_locator_state") != '1' || $_REQUEST['state'] != '') {
                echo $this->alim_setOption($resultsCity, 'location_city', $city);
            }
        } else {
            global $wpdb;
            $sqlState = "SELECT  {$wpdb->prefix}posts.ID FROM {$wpdb->prefix}posts  INNER JOIN {$wpdb->prefix}postmeta ON ({$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id) 
                WHERE {$wpdb->prefix}posts.post_type = 'office'  AND {$wpdb->prefix}posts.post_status = 'publish'
                AND {$wpdb->prefix}postmeta.meta_key = 'location_city'  GROUP BY {$wpdb->prefix}posts.ID ORDER BY {$wpdb->prefix}posts.post_date DESC";  
            $resultsState = $wpdb->get_results($sqlState);
            $state        = (isset($_REQUEST['state'])) ? $_REQUEST['state'] : "";
            if (get_option("st_locator_state") != '1' || $_REQUEST['state'] != '') {
                echo $this->alim_setOption($resultsState, 'location_city', $state);
            }
        }
    }
	public  function alim_setCompany()
    {
        global $wpdb;
        $sqlCompany = "SELECT  {$wpdb->prefix}posts.ID FROM " . " {$wpdb->prefix}posts  INNER JOIN {$wpdb->prefix}postmeta ON ({$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id) " . " WHERE {$wpdb->prefix}posts.post_type = 'office'  AND {$wpdb->prefix}posts.post_status = 'publish' ";
        if ((isset($_REQUEST['city']) && $_REQUEST['city'] != "") && (isset($_REQUEST['city']) && $_REQUEST['city'] != "")) {
            $sqlCompany .= "AND ({$wpdb->prefix}postmeta.meta_key = 'location_city' AND CAST({$wpdb->prefix}postmeta.meta_value AS CHAR)='{$_REQUEST['city']}' ) ";
        } elseif (isset($_REQUEST['state']) && $_REQUEST['state'] != "") {
            $sqlCompany .= "AND ({$wpdb->prefix}postmeta.meta_key = 'location_state' AND CAST({$wpdb->prefix}postmeta.meta_value AS CHAR)='{$_REQUEST['state']}' ) ";
        }
        $sqlCompany .= " GROUP BY {$wpdb->prefix}posts.ID ORDER BY {$wpdb->prefix}posts.post_date DESC";
        $resultsCompany = $wpdb->get_results($sqlCompany);
        $company = (isset($_REQUEST['company'])) ? $_REQUEST['company'] : "";
        echo $this->alim_setOptionCompany($resultsCompany, 'company', $company);
    }
	public function alim_setOption($results, $meta_key, $request)
    {
        $html = "";
        if (count($results) > 0) {
            $arr = array();
            foreach ($results as $result) {
                $value = get_post_meta($result->ID, $meta_key, true);
                if (!empty($value)) {
                    $arr[] = $value;
                }
            }
            if (count($arr) > 0) {
                $arr = array_unique($arr);
                sort($arr);
                foreach ($arr as $ar) {
                    print_r($ar);
                    $select = ($request == $ar) ? 'selected="selected"' : '';
                    $html .= '<option value="' . esc_attr($ar) . '" ' . esc_attr($select) . ' >' . esc_attr($ar) . '</option>';
                }
            }
        }
        echo $html;
        //Return $html;
    }
	public function alim_setOptionCompany($results, $meta_key, $request)
    {
        $html = "";
        if (count($results) > 0) {
            $arr = array();
            foreach ($results as $result) {
                $value = get_the_title($result->ID); //get_field($meta_key, $result);
                if (!empty($value)) {
                    $select = ($request == $result->ID) ? 'selected="selected"' : '';
                    $html .= '<option value="' . esc_attr($result->ID) . '" ' . esc_attr($select) . ' >' . esc_attr($value) . '</option>';
                    //$arr[$result->ID] = $value;
                }
            }
        }
        return $html;
    }
    // Filter form ends here
}
$ALIM_OfficeSearchFilter = new ALIM_OfficeSearchFilter();