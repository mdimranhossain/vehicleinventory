<?php
/*
* Setting
* @Package: VehicleInventory
*/

declare(strict_types=1);

namespace Inc;

class Setting
{   private $db;
    private $table;
    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->table = $this->db->prefix.'inventory';
    }

    public function viUpdateInventoryOptions($data): string
    {
        $slug = strtolower($data['slug']);
        update_option('vi_slug', $slug);

        $emailfriend = strtolower($data['emailfriend']);
        update_option('vi_emailfriend', $emailfriend);

        $availability = strtolower($data['availability']);
        update_option('vi_availability', $availability);

        $address = strtolower($data['address']);
        update_option('vi_address', $address);

        $phone = strtolower($data['phone']);
        update_option('vi_phone', $phone);

        $weekday = strtolower($data['weekday']);
        update_option('vi_weekday', $weekday);

        $weekend = strtolower($data['weekend']);
        update_option('vi_weekend', $weekend);
        
        $data['slug'] = get_option('vi_slug');
        $data['emailfriend'] = get_option('vi_emailfriend');
        $data['availability'] = get_option('vi_availability');
        $data['address'] = get_option('vi_address');
        $data['phone'] = get_option('vi_phone');
        $data['weekday'] = get_option('vi_weekday');
        $data['weekend'] = get_option('vi_weekend');
        return json_encode($data);
    }

    public function viInventoryOptions(): array
    {
        $data['slug'] = get_option('vi_slug');
        $data['emailfriend'] = get_option('vi_emailfriend');
        $data['availability'] = get_option('vi_availability');
        $data['address'] = get_option('vi_address');
        $data['phone'] = get_option('vi_phone');
        $data['weekday'] = get_option('vi_weekday');
        $data['weekend'] = get_option('vi_weekend');
        return $data;
    }

    // public function viUpdateSlug($slug): string
    // {
    //     $data['slug'] = strtolower($slug);
    //     update_option('vi_slug', $data['slug']);
        
    //     $data['vi_slug'] = get_option('vi_slug');
    //     return json_encode($data);
    // }

    // public function viInventorySlug(): string
    // {
    //     $slug= get_option('vi_slug');
    //     return $slug;
    // }
}