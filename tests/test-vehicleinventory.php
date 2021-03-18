<?php
/**
 * Class VehicleInventoryTest
 *
 * @package VehicleInventory
 */

/**
 * Sample test case.
 */

  $autoload=dirname(__FILE__,2)."/vendor/autoload.php";
if(file_exists($autoload)){
	require_once($autoload);
}
use Inc\VehicleData;

class VehicleInventoryTest extends WP_UnitTestCase {

	var $target;

	public function setUp()
	    {
		parent::setUp();
		$this->target="https://jsonplaceholder.typicode.com/users/";
		$this->vehicles = new VehicleData($this->target);
	    }

		public function test_viVehicleList() {

			$list = json_decode($this->vehicles->viVehicleList());

			$this-> assertEquals(count($list), 10);
		}
		public function test_viVehicleDetails() {
			foreach(array(1, 2, 3) as $id) {
				$details = json_decode($this->users->viVehicleDetails($id));

				$this->assertEquals($id, $details->id);
			}
		}
}