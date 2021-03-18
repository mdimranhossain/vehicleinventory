<?php
/*
* VehicleData
* @Package: VehicleInventory
*/

declare(strict_types=1);

namespace Inc;

class VehicleData
{
    private $viRequestUrl;
    private $viCache;
    private $viDataId;

    public function __construct()
    {
        $this->viRequestUrl = "https://jsonplaceholder.typicode.com/users/";
        $viCacheFile = dirname(__FILE__, 2) . "/cache";
        if (!file_exists($viCacheFile)) {
            mkdir($viCacheFile, 0755, true);
        }
        $this->viCache = $viCacheFile . "/vehiclelist.json";
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

    public function viVehicleDetails(string $id): string
    {
        $this->viDataId = intval($id);
        $this->viCache = dirname(__FILE__, 2) . "/cache/" . $id . '.json';
        $this->viRequestUrl .= "/" . $id;
        return $this->viVehicleResult();
    }
}