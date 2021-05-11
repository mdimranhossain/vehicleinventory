<?php
/*
* Vehicle
* @Package: VehicleInventory
*/

declare(strict_types=1);

namespace Inc;

class Vehicle
{
    private $table;
    private $settingTable;

    public function __construct()
    {
        // global $wpdb;
        // $this->table = $wpdb->prefix.'inventory';
        // $this->settingTable = $wpdb->prefix.'inventory_settings';
    }

    public function viList(): string
    {
        global $wpdb;
        $table = $wpdb->prefix.'inventory';
        $query = $wpdb->prepare("SELECT * FROM {$table} WHERE %d", 1);
        $vehicles = $wpdb->get_results($query);
        return json_encode($vehicles);
        //return $query;
    }

    public function viCreate(): string
    {
        global $wpdb;
        $table = $wpdb->prefix.'inventory';

        $input = $_POST;

        $data['insert'] = [
            'make' => $input['make'],
            'model' => $input['model'],
            'additional' => $input['additional'],
            'slug' => $input['slug'],
            'salePrice' => $input['salePrice'],
            'msrp' => $input['msrp'],
            'description' => $input['description'],
            'vehicleCondition' => $input['vehicleCondition'],
            'payloadCapacity' => $input['payloadCapacity'],
            'emptyWeight' => $input['emptyWeight'],
            'floorLength' => $input['floorLength'],
            'floorWidth' => $input['floorWidth'],
            'sideHeight' => $input['sideHeight'],
            'bodyType' => $input['bodyType'],
            'addtionalInfo' => $input['addtionalInfo'],
            'featuredImage' => $input['featuredImage'],
            'gallery' => $input['gallery'],
            'status' => $input['status'],
            'createdBy' => $input['createdBy'],
            'createdAt' => $input['createdAt']
        ];
        $format = array('%s','%s','%s','%d','%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%d','%s','%s');
        $data['success'] = $wpdb->insert($table,$data['insert'],$format);

        if($data['success']){
            $data['message'] = "Vehicle Added Successfully.";
        }else{
            $data['error'] = "Something Went Wrong!!!".$wpdb->last_error;
        }

        $data['insertid'] = $wpdb->insert_id;

        return json_encode($data);
    }

    // Vehicle Slug
    public function viSlug(): string
    {
        global $wpdb;
        $table = $wpdb->prefix.'inventory';

        $data['make'] = '';
        $data['model'] = '';
        $data['additional'] = '';
        $data['slug'] = '';
        if(!empty($_REQUEST['make'])){
            $data['make'] = trim($_REQUEST['make']);
            $data['slug'] = str_replace(' ', '-', $data['make']);
        }
        if(!empty($_REQUEST['model'])){
            $data['model'] = trim($_REQUEST['model']);
            $data['slug'] .= '-'.str_replace(' ', '-', $data['model']);
        }
        if(!empty($_REQUEST['additional'])){
            $data['additional'] = trim($_REQUEST['additional']);
            $data['slug'] .= '-'.str_replace(' ', '-', $data['additional']);
        }

       // $data['slug'] = $input['make'].'-'.$input['model'].'-'.$input['additional'];
 
       // $data['count'] = $wpdb->get_results("SELECT count(slug) as total FROM ".$table." WHERE slug LIKE '%".$data['slug']."%'");

       $data['count'] = $wpdb->get_results("SELECT * FROM ".$table." WHERE slug LIKE '%".$data['slug']."%'");

        if(count($data['count'])>0){
            $data['slug'] = $data['slug'].count($data['count'])+1;
        }
        $data['slug'] = strtolower($data['slug']);
       return json_encode($data);
    }


    // Vehicle Update

    public function viUpdate(): string
    {
        global $wpdb;
        $table = $wpdb->prefix.'inventory';

        $input = $_POST;
        $id = $input['id'];
        $data['update'] = [
            'make' => $input['make'],
            'model' => $input['model'],
            'additional' => $input['additional'],
            'slug' => $input['slug'],
            'salePrice' => $input['salePrice'],
            'msrp' => $input['msrp'],
            'description' => $input['description'],
            'vehicleCondition' => $input['vehicleCondition'],
            'payloadCapacity' => $input['payloadCapacity'],
            'emptyWeight' => $input['emptyWeight'],
            'floorLength' => $input['floorLength'],
            'floorWidth' => $input['floorWidth'],
            'sideHeight' => $input['sideHeight'],
            'bodyType' => $input['bodyType'],
            'addtionalInfo' => $input['addtionalInfo'],
            'featuredImage' => $input['featuredImage'],
            'gallery' => $input['gallery'],
            'status' => $input['status'],
            'updatedBy' => $input['createdBy'],
            'updatedAt' => $input['createdAt']
        ];

        $where = [ 'id' => $id ];
        $data['success'] = $wpdb->update($table, $data['update'], $where);

        if($data['success']){
            $data['message'] = "Vehicle Updated Successfully.";
        }else{
            $data['error'] = "Something Went Wrong!!!".$wpdb->last_error;
        }

       return json_encode($data);
    }

    public function viDetails(string $id)
    {
        global $wpdb;
        $table = $wpdb->prefix.'inventory';
        $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id));
        return $result;
    }

    public function viDelete(): string
    {
        global $wpdb;
        $table = $wpdb->prefix.'inventory';

        $id = $_REQUEST['post_id'];
        $data['vehicle'] = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id));
        $data['delete'] = $wpdb->delete($table, ['id' => $id]);

        $data['message'] = '';
        if($data['delete']){
            $data['message'] = 'Vehicle Deleted!!!';
        }
        echo json_encode($data);
    }

    public function viVehicleResult(): string
    {
        $cUrl = curl_init();
        curl_setopt($cUrl, CURLOPT_URL, $this->viRequestUrl);
        curl_setopt($cUrl, CURLOPT_HTTPGET, true);
        curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, true);
        $viResult = curl_exec($cUrl);
        $status = curl_getinfo($cUrl, CURLINFO_HTTP_CODE);
        curl_close($cUrl);

        if ($status === 200 && !empty($viResult)) {
            file_put_contents($this->viCache, $viResult);
            return $viResult;
        }

        if (file_exists($this->viCache)) {
            return file_get_contents($this->viCache);
        }

        $this->viCache = dirname(__FILE__, 2) . "/cache/vehiclelist.json";
        if (file_exists($this->viCache)) {
            $allvehicles = json_decode(file_get_contents($this->viCache), true);
            if (isset($allvehicles[$this->id])) {
                return json_encode($allvehicles[$this->id]);
            }
        }
        return "[]";
    }

    public function viVehicleList(): string
    {
        return $this->viVehicleResult();
    }
}