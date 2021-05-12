<?php
/*
* Vehicle
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
        global $wp_rewrite;
        
        $data['new'] = trim($slug);
        update_option('vi_slug', $data['new']);
        $wp_rewrite->flush_rules(false);
        $data['vi_slug'] = get_option('vi_slug');

        return json_encode($data);
    }
}