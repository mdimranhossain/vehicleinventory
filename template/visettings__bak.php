<?php
/*
* visettings
* @Package: VehicleInventory
*/
require_once( ABSPATH . 'wp-load.php' );
require_once( ABSPATH . 'wp-admin/admin.php' );
require_once( ABSPATH . 'wp-admin/admin-header.php' );

 $viAutoload = dirname(__FILE__) . '/vendor/autoload.php';
if (file_exists($viAutoload)) {
    require_once $viAutoload;
}
use Inc\VehicleData;
$viData=new VehicleData();
$viVehicles=$viData->viVehicleList();
$viVehicles=json_decode($viVehicles);

function viurl(string $viLink){
	return plugins_url($viLink, dirname(__FILE__));
}
?>
<div id="settings">
	<h2>Vehicle Settings</h2>
	<div id="visettings">
  Options for Vehicle Inventory
	</div>
  <div id="uploads">

  <?php
    wp_enqueue_media();
    //global $post;

    // Get WordPress' media upload URL
    $upload_link = esc_url( get_upload_iframe_src( 'image',0 ) );

    // See if there's a media id already saved as post meta
    $your_img_id = get_post_meta('_your_img_id', true );

    // Get the image src
    $your_img_src = wp_get_attachment_image_src( $your_img_id, 'full' );

    // For convenience, see if the array is valid
    $you_have_img = is_array( $your_img_src );
    ?>

    <!-- Your image container, which can be manipulated with js -->
    <div class="custom-img-container">
        <?php if ( $you_have_img ) : ?>
            <img src="<?php echo $your_img_src[0] ?>" alt="" style="max-width:100%;" />
        <?php endif; ?>
    </div>

    <!-- Your add & remove image links -->
    <p class="hide-if-no-js">
        <a class="upload-custom-img <?php if ( $you_have_img  ) { echo 'hidden'; } ?>" 
          href="<?php echo $upload_link ?>">
            <?php _e('Set custom image') ?>
        </a>
        <a class="delete-custom-img <?php if ( ! $you_have_img  ) { echo 'hidden'; } ?>" 
          href="#">
            <?php _e('Remove this image') ?>
        </a>
    </p>

    <!-- A hidden input to set and post the chosen image id -->
    <input class="custom-img-id" name="custom-img-id" type="hidden" value="<?php echo esc_attr( $your_img_id ); ?>" />
  
  </div>
</div>
<script>
  jQuery(document).ready(function($){

     // Set all variables to be used in scope
  var frame,
      metaBox = $('#meta-box-id.postbox'), // Your meta box id here
      addImgLink = metaBox.find('.upload-custom-img'),
      delImgLink = metaBox.find( '.delete-custom-img'),
      imgContainer = metaBox.find( '.custom-img-container'),
      imgIdInput = metaBox.find( '.custom-img-id' );
  
  // ADD IMAGE LINK
  addImgLink.on( 'click', function( event ){
    
    event.preventDefault();
    
    // If the media frame already exists, reopen it.
    if ( frame ) {
      frame.open();
      return;
    }
    
    // Create a new media frame
    frame = wp.media({
      title: 'Select or Upload Media Of Your Chosen Persuasion',
      button: {
        text: 'Use this media'
      },
      multiple: false  // Set to true to allow multiple files to be selected
    });

    
    // When an image is selected in the media frame...
    frame.on( 'select', function() {
      
      // Get media attachment details from the frame state
      var attachment = frame.state().get('selection').first().toJSON();

      // Send the attachment URL to our custom image input field.
      imgContainer.append( '<img src="'+attachment.url+'" alt="" style="max-width:100%;"/>' );

      // Send the attachment id to our hidden input
      imgIdInput.val( attachment.id );

      // Hide the add image link
      addImgLink.addClass( 'hidden' );

      // Unhide the remove image link
      delImgLink.removeClass( 'hidden' );
    });

    // Finally, open the modal on click
    frame.open();
  });
  
  
  // DELETE IMAGE LINK
  delImgLink.on( 'click', function( event ){

    event.preventDefault();

    // Clear out the preview image
    imgContainer.html( '' );

    // Un-hide the add image link
    addImgLink.removeClass( 'hidden' );

    // Hide the delete image link
    delImgLink.addClass( 'hidden' );

    // Delete the image id from the hidden input
    imgIdInput.val( '' );

  });


  });
</script>
<div style="display: block; clear: both;"></div>