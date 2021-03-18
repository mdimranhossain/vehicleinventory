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
use Inc\EndpointVehicle;
class Vehiclelist_Test extends WP_UnitTestCase {
	var $endpoint;
	public function setUp(){
		parent::setUp();
		$this->endpoint="https://jsonplaceholder.typicode.com/users/";
		$this->vehicles = new EndpointVehicle($this->endpoint);
	    }
	public function test_GetAllVehicle() {
		$allvehicle=json_decode($this->vehicles->GetAll());
		$this->assertEquals( count($allvehicle),10 );
	}
	public function test_SingleVehicle() {
		foreach(array(1,2,3) as $id){
		$singlevehicle=json_decode($this->vehicles->GetbyId($id));
		$this->assertEquals($id,$singlevehicle->id);
		}
		
	}
}