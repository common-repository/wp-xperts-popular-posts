<?php
/**
 *Plugin Name: WP-xPerts Popular Posts
 * Plugin URI: #
 * Description: Wp Popular Posts is most powerful plugin to display popular posts on your website. WX popular posts widget is created, so site admin can use it easily
 * Version: 1.2
 * Author: Sajid Hussain
 * Author URI: #
 * Text Domain: wx-popular-posts
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'WX_PP_PATH_INCLUDES', dirname( __FILE__ ) . '/inc' );
define( 'WX_PP_PATH_CSS', plugin_dir_url( __FILE__ ) . 'css/' );
define('WX_PP_TEXT_DOMAIN', 'wx-popular-posts');



/**
 * plugin base class
 */
class WX_popular_posts
{
    public $WX_pp_settings;
    public function __construct()
    {
        $WX_get_pp_options  = get_option('WX_pp_options');
        if(is_serialized($WX_get_pp_options))
        {
            $this->WX_pp_settings  =   unserialize($WX_get_pp_options);
        }

        //enqqueue front-end script and style
        add_action( 'wp_enqueue_scripts', array($this, 'WX_pp_front_styles') );
        add_action( 'wp_enqueue_scripts', array($this, 'WX_pp_front_scripts') );

        //create settings page
        add_action('admin_menu', array($this, 'WX_pp_admin_page'));

        //created widget
        add_action( 'widgets_init', array($this, 'WX_pp_widgets') );

        //created a dynamic counter
        add_action('pre_get_posts', array($this, 'WX_pp_counter'));

        //settings link from plugins page
        add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'WX_add_settings_link') );

        //script and styles on admin side
        add_action( 'admin_enqueue_scripts', array( $this, 'WX_pp_admin_styles' ) );
    }
    public function WX_add_settings_link($links)
    {
        $WX_links = array(
            '<a href="' . admin_url( 'options-general.php?page=wx-popular-posts' ) . '">Settings</a>',
        );
        return array_merge( $links, $WX_links );
    }

    public function WX_pp_counter($query)
    {
        if($query->is_single())
        {
            $WX_get_pp_options  = get_option('WX_pp_options');
            if(is_serialized($WX_get_pp_options))
            {
                $WX_get_pp_options  =   unserialize($WX_get_pp_options);
            }
            $post_type  =   $WX_get_pp_options['WX-pp-post-type'];
            $quer_vars  =   $query->query_vars;
            $post_obj   =   get_page_by_path($quer_vars['name'], OBJECT, $post_type);

            if($post_obj)
            {
                $post_id    =   $post_obj->ID;
                $initial_counter    =   get_post_meta($post_id, 'WX-views-count', true);
                if($initial_counter)
                {
                    $WX_views_count     =   $initial_counter;
                }
                else{
                    $WX_views_count     =   0;
                }
                update_post_meta($post_id, 'WX-views-count', $WX_views_count+1);
            }

        }


    }
    public function WX_pp_widgets()
    {
        require_once WX_PP_PATH_INCLUDES.'/wx-pp-widget.php';
    }


    public  function WX_pp_front_styles()
    {
        wp_enqueue_style( 'wx-pp-styles',  WX_PP_PATH_CSS. 'wx-pp-styles.css', false );
    }
    public function WX_pp_admin_styles( $hook ) {
        wp_register_style( 'wx-pp-admin-styles', plugins_url( '/css/wx-pp-styles-admin.css', __FILE__ ), array(), '1.0', 'screen' );

        wp_enqueue_style( 'wx-pp-admin-styles' );
    }
    public  function wx_pp_front_scripts()
    {

    }

    public function wx_pp_admin_page()
    {
        add_options_page(
            'Popular Posts',
            'Popular Posts',
            'manage_options',
            'wx-popular-posts',
            array(
                $this,
                'WX_popular_posts'
            )
        );
    }
    public function WX_popular_posts()
{

    echo '<div class="wrap">';
    _e( '<h1>Popular Posts Settings</h1>', 'wx-popular-posts' );
    require_once WX_PP_PATH_INCLUDES.'/wx-pp-settings.php';
    echo '</div>';
}


}


$WX_obj =   new WX_popular_posts;