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

use Inc\Setting;

$setting = new Setting();

if(!empty($_REQUEST['vi_slug'])){
    $slug = trim($_REQUEST['vi_slug']);
    echo $setting->viUpdateSlug($slug);
}
