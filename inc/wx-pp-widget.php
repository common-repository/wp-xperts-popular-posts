<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once WX_PP_PATH_INCLUDES.'/wx-pp-post-functions.php';


register_widget('WX_create_pp_widget');

class WX_create_pp_widget extends WP_Widget {
    public function __construct()
    {

        parent::__construct(
            'WX_create_pp_widget',
            __( 'WP-xPerts Popular Posts', WX_PP_TEXT_DOMAIN ),
            array(
                'classname'   => 'WX_create_pp_widget',
                'description' => __( 'Popular posts widgegt by wp-xperts', WX_PP_TEXT_DOMAIN )
            )
        );


    }
    public function widget( $args, $instance )
    {
        $title = apply_filters( 'widget_title', $instance['title'] );
        echo $args['before_widget'];

        if ( ! empty( $title ) )
        echo $args['before_title'] . $title . $args['after_title'];

        //get widget settings;
        $WX_get_pp_options  = get_option('WX_pp_options');
        if(is_serialized($WX_get_pp_options))
        {
            $WX_get_pp_options  =   unserialize($WX_get_pp_options);
        }
        $WX_pp_template_obj     =   new WX_PP_post_template();
        if($WX_get_pp_options['WX-pp-post-type']!='')
        {
            $wx_post_type     =   $WX_get_pp_options['WX-pp-post-type'];
        }
        else
        {
            $wx_post_type     =   'post';
        }
        if($WX_get_pp_options['WX-pp-post-num']!='')
        {
            $wx_pp_num     =   $WX_get_pp_options['WX-pp-post-num'];
        }
        else
        {
            $wx_pp_num     =   5;
        }
        $WX_pp_posts_args   =   array(
            'posts_per_page'    =>  $wx_pp_num,
            'post_type'         =>  $wx_post_type,
            'meta_key'          =>  'WX-views-count',
            'orderby'           =>  'meta_value_num'
        );
        $WX_pp_posts    =   new WP_Query($WX_pp_posts_args);
        if($WX_pp_posts->have_posts()): while($WX_pp_posts->have_posts()): $WX_pp_posts->the_post();
            global $post;
            echo $WX_pp_template_obj->WX_pp_get_post_template($post->ID, $WX_get_pp_options);
        endwhile; wp_reset_postdata(); endif;
        echo $args['after_widget'];


    }
    //widget backend form
    public function form( $instance )
    {
        $defaults = array(
            'title' => __('Popular Posts', WX_PP_TEXT_DOMAIN),
        );
        $instance = wp_parse_args( (array) $instance, $defaults );

        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wx_pp_widget'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
        </p>
        <p>
            You can configure widget setting on <a href="<?php menu_page_url( 'wx-popular-posts' ); ?>">settings</a> page
        </p>
        <?php
    }
    // Updating widget
    public function update( $new_instance, $old_instance )
    {
        $instance	=	$old_instance;
        $instance['title']	=	strip_tags($new_instance['title']);

        return $instance;
    }
}