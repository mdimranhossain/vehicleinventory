<?php
/*
 * Endpoint
* @Package: VehicleInventory
*/

declare(strict_types=1);
$viAutoload = dirname(__FILE__) . '/vendor/autoload.php';
if (file_exists($viAutoload)) {
    require_once $viAutoload;
}

use Inc\VehicleData;

$viVehicleData = new VehicleData();

if (!empty($_GET['id'])) {
    echo $viVehicleData->viVehicleDetails($_GET['id']);
}
