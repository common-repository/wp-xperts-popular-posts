<?php
/**

 * do not delete this file
 * it is responsible for generating the markup of post
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class WX_PP_post_template
{

    public function __construct()
    {

    }

    /*
     * function responsible to compile the template
     * @postID   id of the current post
     * @WX_get_pp_options  options saved by plugin
     */
    public function WX_pp_get_post_template($postID, $WX_get_pp_options){
        $WX_pp_template     =   '';
        $WX_pp_template     .=  '<div class="wx-pp-posts-wrap">';
        $WX_pp_template     .=      $this->WX_pp_post_before($WX_get_pp_options);
        if(isset($WX_get_pp_options['WX-pp-thumbnail'])){
            $WX_pp_template     .=          $this->WX_pp_post_thumb($postID, $WX_get_pp_options);
        }
        $WX_pp_template     .=  '       <div class="wx-pp-detail">';
        $WX_pp_template     .=              $this->WX_pp_post_title($postID);
        if(isset($WX_get_pp_options['WX-pp-views']))
        {
            $WX_pp_template .=  $this->WX_pp_post_views($postID, $WX_get_pp_options);
        }

        if(isset($WX_get_pp_options['WX-pp-excerpt']))
        {
            $WX_pp_template .=  $this->WX_pp_post_excerpt($postID, $WX_get_pp_options);
        }

        $WX_pp_template     .=              $this->WX_pp_post_meta($postID, $WX_get_pp_options);
        $WX_pp_template     .=  '       </div>';
        $WX_pp_template     .=      $this->WX_pp_post_after();
        $WX_pp_template     .=  '</div>';

        return  $WX_pp_template;
    }


    public function WX_pp_post_before($WX_get_pp_options){
        return   '<div class="wx-pp-post '.$WX_get_pp_options['WX-pp-post-thumb-position'].'-thumbnail">';
    }
    public function WX_pp_post_after(){
        return   '<div class="clearfix"></div> </div>';
    }
    public function WX_pp_post_thumb($postID, $WX_get_pp_options){
        $image      =   wp_get_attachment_image_src( get_post_thumbnail_id( $postID ), $WX_get_pp_options['WX-pp-post-thumb-size'] );
        $return     =   '';
        $return     .=  '<div class="wx-pp-thumb">';
        $return     .=  '    <a href="#"><img src="'.esc_url($image[0]).'" alt="'.strip_tags(get_the_title($postID)).'"></a>';
        $return     .=  '</div>';

        return $return;
    }
    public function WX_pp_post_title($postID){
        return  '<h4 class="wx-pp-title"><a href="'.get_permalink($postID).'">'.esc_html(get_the_title($postID)).'</a></h4>';
    }
    public function WX_pp_post_views($postID, $WX_get_pp_options){
        $WX_views_count =   get_post_meta($postID, 'WX-views-count', true);
        if(isset($WX_get_pp_options['WX-pp-views-temp']) && !empty($WX_get_pp_options['WX-pp-views-temp']))
        {
            $views_text =   $WX_get_pp_options['WX-pp-views-temp'];
            $views_text =   str_replace('{views}', $WX_views_count, $views_text);
            return $views_text;
        }
        else
        {
            return  'Views: '.$WX_views_count;
        }


    }
    public function WX_pp_post_excerpt($postID, $WX_get_pp_options){
        $excerpt_len    =   $WX_get_pp_options['WX-pp-length'];
        $excerpt    =   get_the_excerpt($postID);
        $excerpt    =   mb_substr($excerpt, 0, $excerpt_len);
        return  '<p class="wx-pp-excerpt">'. $excerpt .'</p>';
    }
    public function WX_pp_post_meta($postID, $WX_get_pp_options){
        $return =   '';
        $return .=  '<div class="wx-pp-meta">';
        if(isset($WX_get_pp_options['WX-pp-author']))
        {
            $return .=      $this->WX_pp_post_author($postID);
        }
        if(isset($WX_get_pp_options['WX-pp-tags']))
        {
            $return .=  $this->WX_pp_post_time($postID);
        }
        if(isset($WX_get_pp_options['WX-pp-cats']))
        {
            $return .=       $this->WX_pp_post_cats($postID);
        }
        if(isset($WX_get_pp_options['WX-pp-tags']))
        {
            $return .=       $this->WX_pp_post_tags($postID);
        }

        $return .=  '</div>';

        return  $return;
    }
    public function WX_pp_post_author($postID){
        return  '<a href="'.get_author_posts_url(get_the_author_meta('ID')).'" class="wx-pp-author">'.get_the_author().'</a>';
    }
    public function WX_pp_post_time($postID){
        return  '<time class="wx-pp-time">'.get_the_time('F j, Y g:i a', $postID).'</time>';
    }
    public function WX_pp_post_cats($postID){

        $return     =   '';
        $WX_pp_cats    =   get_terms('category', $postID);

        $return     .=   '<ul class="wx-pp-cats">';
        $return     .=      '<strong>Categories: </strong>';
            foreach($WX_pp_cats as $WX_pp_cat)
            {
                $return     .=  '<li><a href="'.get_term_link($WX_pp_cat->term_id).'">'.$WX_pp_cat->name.'</a>, </li>';
            }
        $return     .=  '</ul>';
        return $return;
    }
    public function WX_pp_post_tags($postID){

        $return     =   '';
        $WX_pp_tags    =   get_terms('post_tag', $postID);

        $return     .=   '<ul class="wx-pp-tags">';
        $return     .=      '<strong>Tags: </strong>';
        foreach($WX_pp_tags as $WX_pp_tag)
        {
            $return     .=  '<li><a href="'.get_term_link($WX_pp_tag->term_id).'">'.$WX_pp_tag->name.'</a>, </li>';
        }
        $return     .=  '</ul>';
        return $return;
    }


}
