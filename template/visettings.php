<?php
/*
* visettings
* @Package: VehicleInventory
*/
//require_once( ABSPATH . 'wp-load.php' );
require_once( ABSPATH . 'wp-admin/admin.php' );


 $viAutoload = dirname(__FILE__) . '/vendor/autoload.php';
if (file_exists($viAutoload)) {
    require_once $viAutoload;
}
use Inc\Setting;
$setting = new Setting();

$slug = $setting->viInventorySlug();

function viurl(string $viLink){
	return plugins_url($viLink, dirname(__FILE__));
}
if(!empty($_REQUEST['vi_slug'])){
  $slug = trim($_REQUEST['vi_slug']);
  $setting->viUpdateSlug($slug);
  global $wp_rewrite;
  $permalink_structure = get_option( 'permalink_structure' );
  $wp_rewrite->set_permalink_structure( $permalink_structure );


  $using_index_permalinks = $wp_rewrite->using_index_permalinks();
  echo '<script>location.reload();</script>';

}

flush_rewrite_rules();

require_once( ABSPATH . 'wp-admin/admin-header.php' );
?>
<div id="settings">
	<h2>Vehicle Inventory Settings</h2>
	<div class="container">
    <div class="row">
      <div class="col-sm-12">
        <form id="visettings" action="" method="POST">
          <input type="hidden" name="user_id" value="">
          <div class="form-group">
            <label class="control-label" for="vi_slug">Inventory Slug</label>
            <div class="row">
              <input type="text" class="form-control col-sm-2" value="<?php echo esc_url(home_url()).'/';?>" disabled /><input type="text" class="form-control col-sm-3" id="vi_slug" name="vi_slug" value="<?php echo $slug;?>" />
              <button type="submit" id="saveslug" class="btn btn-primary btn-md">Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>
	</div>
</div>
<div style="display: block; clear: both;"></div>
<script>
		// jQuery(document).ready(function($){
		// 	$('#saveslug').on('click', function(e){
    //   e.preventDefault();
    //   var endpoint = "";
    //   $.ajax({
    //         url:endpoint,
    //         method: "POST",
    //         data: new FormData(document.getElementById('visettings')),
    //         contentType: false,
    //         cache: false,
    //         processData: false,
    //         dataType: "json",
    //         success: function(data) {
    //             console.log(data);
    //             var html = '';
                  
    //         }
    //     });
    //   });
		// });
		</script>