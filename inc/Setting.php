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

    public function viUpdateSlug($slug): string
    {
        $data['slug'] = strtolower($slug);
        update_option('vi_slug', $data['slug']);
        flush_rewrite_rules(false);
        $data['vi_slug'] = get_option('vi_slug');

        return json_encode($data);
    }

    public function viInventorySlug(): string
    {

        $slug= get_option('vi_slug');
        return $slug;
    }
}