<?php
/**
 * Class Vehiclelist_Test
 *
 * @package Vehiclelist
 */

/**
 * Vehiclelist test case.
 */
 $autoload_file=dirname(__FILE__,2)."/vendor/autoload.php";
if(file_exists($autoload_file)){
	require_once($autoload_file);
}
use Inc\Vehicle;
class Vehiclelist_Test extends WP_UnitTestCase {
	var $endpoint;
	public function setUp(){
		parent::setUp();
		$this->endpoint="https://jsonplaceholder.typicode.com/users/";
		$this->vehicles = new Vehicle($this->endpoint);
	    }
	public function test_GetAllVehicle() {
		$allvehicle=json_decode($this->vehicles->viList());
		$this->assertEquals( count($allvehicle),10 );
	}
	public function test_SingleVehicle() {
		foreach(array(1,2,3) as $id){
		$singlevehicle=$this->vehicles->viDetails($id);
		$this->assertEquals($id,$singlevehicle->id);
		}
		
	}
}