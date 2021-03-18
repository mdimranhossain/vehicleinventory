<?php
/*
* Init
* @Package: VehicleInventory
*/

declare(strict_types=1);

namespace Inc;

class Init
{
    private $viUrl;
    private $viPath;
    public function __construct()
    {
        $this->viUrl = plugins_url("", dirname(__FILE__));
        $this->viPath = dirname(__FILE__, 2);
    }

    public function start()
    {
        add_action('admin_enqueue_scripts', [$this, 'viAddDatatable']);
        add_action('admin_menu', [$this, 'viAddPage']);
        add_action('vivehicles', [$this, 'viVehicles']);
    }

    public function viAddPage()
    {
        add_menu_page(
            'Vehicle Inventory',
            'Vehicle Inventory',
            'read',
            'vivehicles',
            [$this, "viVehicles"],
            '',
            71
        );
    }

    public function viAddDatatable(string $hook)
    {
        if ($hook !== "toplevel_page_vivehicles") {
            return;
        }
        wp_enqueue_style('dt_bs_css', $this->viUrl . '/assets/datatables.min.css');
        wp_enqueue_style('vi-styles', $this->viUrl . '/assets/vi-styles.css');
        wp_enqueue_script('dt_bs_js', $this->viUrl . '/assets/datatables.min.js');
    }

    public function viVehicles()
    {
        include_once($this->viPath . '/template/vivehicles.php');
    }

    public function viActivate()
    {
        //Nothing to do here this case
    }

    public function viDeactivate()
    {
        //Nothing to do here this case
    }

    public static function viUninstall()
    {
        // Nothing to trigger here for this plugins
    }
}
