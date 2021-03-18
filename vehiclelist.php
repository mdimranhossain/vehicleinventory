<?php
require_once( ABSPATH . 'wp-load.php' );
require_once( ABSPATH . 'wp-admin/admin.php' );
require_once( ABSPATH . 'wp-admin/admin-header.php' );

	function vehicleList($url){
		$cURL = curl_init();
		curl_setopt($cURL, CURLOPT_URL, $url);
		curl_setopt($cURL, CURLOPT_HTTPGET, true);
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($cURL);
		curl_close($cURL);
		$vehicles=json_decode($result);
		return $vehicles;
	}

$url = 'https://jsonplaceholder.typicode.com/users';
$uds=vehicleList($url);

?>
<div id="placeholder">
	<h2>Vehicle List</h2>
	<div id="vehiclelist" class="table-responsive" style="width:100% max-width:1140px; margin:10px auto;">
		<table id="vehicles" class="display table table-bordered table-striped" style="width:100%" border="1">
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Vehiclename</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Website</th>
				</tr>
			</thead>
			<tbody>
			<?php
			if($uds){
				foreach($uds as $ud){
					echo '<tr><td><a class="dlink" dataid="'.$ud->id.'" href="#" data-toggle="modal" data-target="#vehicledetails">'.$ud->id.'</a></td><td><a class="dlink" dataid="'.$ud->id.'" href="#" data-toggle="modal" data-target="#vehicledetails">'.$ud->name.'</a></td><td><a class="dlink" dataid="'.$ud->id.'" href="#" data-toggle="modal" data-target="#vehicledetails">'.$ud->vehiclename.'</a></td><td>'.$ud->email.'</td><td>'.$ud->phone.'</td><td>'.$ud->website.'</td></tr>';
				}
			}
			?>
			</tbody>
			
			<tfoot>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Vehiclename</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Website</th>
				</tr>
			</tfoot>
		</table>
	</div>
	<div id="vehicledetails" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title">Vehicle Details</h2>
      </div>
      <div class="modal-body">
        <div id="details" class="table-responsive" style="width:100%; max-width:540; margin:0px auto;">
          
        </div>
      </div>
    </div>

  </div>
</div>
</div>
<script>
  jQuery(document).ready(function($){
	$('#vehicles').DataTable({
                responsive: true,
                });
      $(document).on('click','.dlink', function(e){
        e.preventDefault();
        var id=$(this).attr('dataid');
        
        $.ajax({
            dataType: "json",
            url: "https://jsonplaceholder.typicode.com/users/"+id,
          })
            .done(function(data){      
              var vehicle ='';
               vehicle +='<table class="table table-bordered table-striped" border="1">';
               vehicle +='<tr><td>ID:</td><td>' + data.id + '</td></tr>';
               vehicle +='<tr><td>Name:</td><td>' + data.name + '</td></tr>';
               vehicle +='<tr><td>VehicleName:</td><td>' + data.vehiclename + '</td></tr>';
               vehicle +='<tr><td>Email:</td><td>' + data.email + '</td></tr>';
               vehicle +='<tr><td>Phone:</td><td>' + data.phone + '</td></tr>';
               vehicle +='<tr><td>Website:</td><td>' + data.website + '</td></tr>';
               vehicle +='<tr><td>Address:</td><td>Street- ' + data.address.street + '<br>Suite- ' + data.address.suite + '<br>City- ' + data.address.city + '<br>ZipCode- ' + data.address.zipcode + '<br>Latitude- ' + data.address.geo.lat + '<br>' + 'Longitude- ' + data.address.geo.lng + '</td></tr>';
               vehicle +='<tr><td>Company:</td><td>Name- ' + data.company.name + '<br>catchPhrase- ' + data.company.catchPhrase + '<br>bs- ' + data.company.bs + '</td></tr>';
               vehicle +='</table>';
              $('#details').html(vehicle);
            });
        
        });
  });
</script>
<div style="display: block; clear: both;"></div>