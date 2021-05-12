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
    private $db;
    private $table;
    
    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->table = $this->db->prefix.'inventory';

        $this->viUrl = plugins_url("", dirname(__FILE__));
        $this->viPath = dirname(__FILE__, 2);
    }

    public function start()
    {
        add_action('admin_enqueue_scripts', [$this, 'viAdminAssets']);
        if (strpos($_SERVER['REQUEST_URI'], "bbn-inventory") !== false){
        add_action('wp_enqueue_scripts', [$this, 'viAssets']);
        }
        add_action('admin_menu', [$this, 'viAddPage']);
        add_action('vivehicles', [$this, 'viVehicles']);
        add_action( 'wp_ajax_viDeleteAttachment', [$this, 'viDeleteAttachment']);

        add_filter( 'query_vars', function( $query_vars ){
            $query_vars[] = 'bbn-inventory';
            return $query_vars;
        } );

        add_action( 'init',  function() {
            add_rewrite_rule('^bbn-inventory/?([^/]*)/?', 'index.php?bbn-inventory=$matches[1]', 'top');
        } );

        add_action( 'template_redirect', function(){
            $inventory = get_query_var( 'bbn-inventory' );
            if (strpos($_SERVER['REQUEST_URI'], "bbn-inventory") !== false && empty($inventory)){
                include $this->viPath . '/template/inventory.php';
                die;
            }elseif($inventory){
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

    public function viAdminAssets(string $hook)
    {
        wp_enqueue_style('dt_bs_css', $this->viUrl . '/assets/datatables.min.css');
        wp_enqueue_script('dt_bs_js', $this->viUrl . '/assets/datatables.min.js');
        wp_enqueue_style('fontawesome', $this->viUrl.'/assets/font-awesome/css/font-awesome.min.css');
        wp_enqueue_style('vi-styles', $this->viUrl . '/assets/vi-styles.css');
    }
    public function viAssets(string $hook)
    {
        wp_enqueue_style('fontawesome', $this->viUrl.'/assets/font-awesome/css/font-awesome.min.css');
        wp_enqueue_style('bootstrap-css', $this->viUrl.'/assets/Bootstrap-4-4.1.1/css/bootstrap.min.css');
        wp_enqueue_style('slider-css', $this->viUrl.'/assets/slider/slider.min.css');
        wp_enqueue_script('jquery-js', $this->viUrl.'/assets/jquery.min.js', array(), false, false);
        wp_enqueue_script('slider-js', $this->viUrl.'/assets/slider/slider.min.js', array(), false, true);
        wp_enqueue_script('bootstrap-js', $this->viUrl.'/assets/Bootstrap-4-4.1.1/js/bootstrap.min.js', array(), false, true);
        wp_enqueue_script('scripts-js', $this->viUrl.'/assets/scripts.js', array(), false, true);
        wp_enqueue_style('styles', $this->viUrl.'/assets/styles.css');
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
        global $vi_db_version;
        $vi_db_version = '1.0';
        global $vi_slug;
        $vi_slug = 'bbn-inventory';

        $table = $this->table;
        include_once($this->viPath . '/inc/Database.php');
        $this->db->query($create_table);

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	    dbDelta($create_table);

	    add_option( 'vi_db_version', $vi_db_version );
        add_option( 'vi_slug', $vi_slug );
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
