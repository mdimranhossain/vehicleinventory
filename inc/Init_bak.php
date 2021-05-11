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
        add_action( 'wp_ajax_viDeleteAttachment', [$this, 'viDeleteAttachment']);

        add_filter( 'generate_rewrite_rules', function ( $wp_rewrite ){
            $wp_rewrite->rules = array_merge(
                ['all-inventory/(\s+)/?$' => 'index.php?inventory=$match[1]'],
                $wp_rewrite->rules
            );
        } );

        add_filter( 'query_vars', function( $query_vars ){
            $query_vars= ['inventory','details'];
            return $query_vars;
        } );

        add_action( 'template_redirect', function(){
            $inventory = intval( get_query_var( 'inventory' ) );
            $details = intval( get_query_var( 'details' ) );
            if ($inventory) {
                include $this->viPath . '/template/inventory.php';

                die;
            }elseif($details){
                include $this->viPath . '/template/details.php';
                die;
            }
        } );
    }

    public function viAddPage()
    {
        add_menu_page(
            'All Inventory',
            'All Inventory',
            'read',
            'vivehicles',
            [$this, "viVehicles"],
            '',
            71
        );
        add_submenu_page(
            'vivehicles',
            'Add New',
            'Add New',
            'manage_options',
            'viaddnew',
            [$this, "viAddNew"],
            71
        );
        add_submenu_page(
            'vivehicles',
            '',
            '',
            'manage_options',
            'viedit',
            [$this, "viEdit"],
            71
        );
        add_submenu_page(
            'vivehicles',
            'Settings',
            'Settings',
            'manage_options',
            'visettings',
            [$this, "viSettings"],
            71
        );
    }

    public function viAddDatatable(string $hook)
    {
        wp_enqueue_style('dt_bs_css', $this->viUrl . '/assets/datatables.min.css');
        wp_enqueue_style('vi-styles', $this->viUrl . '/assets/vi-styles.css');
        wp_enqueue_script('dt_bs_js', $this->viUrl . '/assets/datatables.min.js');
    }

    
    public function viDeleteAttachment() {
        $data['id'] = $_REQUEST['post_id'];
        $data['gallery'] = $_REQUEST['gallery'];
        $data['featured'] = $_REQUEST['featured'];
        $data['delete'] = [];
        $data['message'] = '';
        $data['files'] = [];

        if(!empty($data['gallery'])){
           
            $data['files'] = explode(',',$data['gallery']);
            foreach($data['files'] as $post_id){
              $data['delete'][$post_id] = wp_delete_attachment($post_id,true);
             // $data['delete'][$post_id] = $post_id;
            }
            if(!empty($data['delete'])){
                $data['message'] .= 'Attachment(s) Deleted';
            }      
        }
        echo json_encode($data);
        die();
    }

    public function viVehicles()
    {
        include_once($this->viPath . '/template/vivehicles.php');
    }
    public function viAddNew()
    {
        include_once($this->viPath . '/template/viaddnew.php');
    }
    public function viEdit()
    {
        include_once($this->viPath . '/template/viedit.php');
    }
    public function viSettings()
    {
        include_once($this->viPath . '/template/visettings.php');
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
