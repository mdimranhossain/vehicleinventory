<?php
/**
 * Template Name: Inventory Details
 */
get_header(); 

$slug = get_query_var('bbn-inventory');
//echo $inventory;
global $wpdb;
$table = $wpdb->prefix.'inventory';
$query = $wpdb->prepare("SELECT * FROM {$table} WHERE slug='%s'", $slug);
$vehicle = $wpdb->get_row($query);
$vehicleTitle = $vehicle->make.' '.$vehicle->model.' '.$vehicle->additional;
//print_r($vehicle);
//echo "Best Built Inventory Details Page";
?>
<script>document.title = "<?php echo $vehicleTitle; ?>";</script>
<div class="container bg-white pt-2 pb-2">
	<div class="row">
		<div class="col-sm-8">
			<div class="row">
				<div class="col-sm-12 text-center">
					<h2><?php echo $vehicleTitle; ?></h2>
				</div>
				<div class="col-sm-12">
					<img src="<?php echo $vehicle->featuredImage; ?>" alt="" class="img-fluid" />
				</div>
				<div class="col-sm-12">
					<p><?php echo $vehicle->description; ?></p>
				</div>
				<div class="col-sm-12">
					<h4 class="pt-2 pb-2"><?php echo $vehicle->make.' '.$vehicle->model.' '.$vehicle->additional; ?></h4>
					<ul>
						<li>Condition: <?php echo $vehicle->vehicleCondition; ?></li>
						<li>Payload capacity: <?php echo $vehicle->payloadCapacity; ?></li>
						<li>Empty weight: <?php echo $vehicle->emptyWeight; ?></li>
						<li>Floor length: <?php echo stripslashes($vehicle->floorLength); ?></li>
						<li>Floor width: <?php echo stripslashes($vehicle->floorWidth); ?></li>
						<li>Side height: <?php echo stripslashes($vehicle->sideHeight); ?></li>
						<li>Body type: <?php echo $vehicle->bodyType; ?></li>
					</ul>
				</div>
				<div class="col-sm-12">
					<h5 class="pt-2 pb-2">Additional Information:</h5>
					<p><?php echo $vehicle->addtionalInfo; ?></p>
				</div>
				<div class="col-sm-12">
					<?php
						if($vehicle->gallery){ 
							$images = explode(',',$vehicle->gallery)
							?>
							<h3 class="pt-2 pb-2">Gallery:</h3>
							<ul class="gallery">
								<?php foreach( $images as $image ): ?>
									<li>
										<a href="<?php echo esc_url($image); ?>">
										<img src="<?php echo esc_url($image); ?>" alt="<?php echo $vehicle->make.' '.$vehicle->model; ?>" />
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
					<?php } ?>
				</div>
			</div> 
		</div>
		<div class="col-sm-4 sidebar">
			<div class="contact p-1 rounded">
			<h3 class="contact-title text-danger border-bottom-1">Contact Details</h3>
			<p>13425 Hwy 99 | Everett, WA 98204</p>
			<p><b>Phone:</b> <a href="tel:4257421102">(425) 742-1102</a></p>
			<p><b>Monday – Saturday:</b> 10:00AM -7:00PM<br> <b>Sunday:</b> 11:00AM-6:00PM</p>
			</div>
		</div>
	</div> 
</div>

<?php
get_footer();