<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(isset($_POST['WX-pp-settings'])){

    if ( ! wp_verify_nonce( $_POST['WX_nonce_settings'], plugin_basename( __FILE__ ) ) ) {
        die( 'Security check failed' );
    }
    else
    {
        $WX_pp_options_arr  =   array();
        if(!intval($_POST['WX-pp-post-num']))
        {
            $WX_error   =   'Number of posts must be number';
        }
        else if(! intval($_POST['WX-pp-length']))
        {
            $WX_error   =   'Excerpt length must be number';
        }
        else
        {
            foreach ($_POST as $key => $val):
                $WX_pp_options_arr[$key]    =   esc_html(sanitize_text_field($val));
            endforeach;
            $WX_pp_options_arr_serialized   =   serialize($WX_pp_options_arr);
            update_option('WX_pp_options', $WX_pp_options_arr_serialized);
            $WX_success     =   'Settings update successfully';
        }
    }
}
$post_types = get_post_types(
    array(
        'public'    =>  true,
        '_builtin' => false
    )
);
$WX_get_pp_options  = get_option('WX_pp_options');
if(is_serialized($WX_get_pp_options))
{
    $WX_get_pp_options  =   unserialize($WX_get_pp_options);
}
//print_r($WX_get_pp_options);
$return     =  '';
if(isset($WX_error))
{
    $return     .=  '<div class="notice notice-error"><p>'.$WX_error.'</p></div>';
}
if(isset($WX_success))
{
    $return     .=  '<div class="notice notice-success"><p>'.$WX_success.'</p></div>';
}
require_once 'wx-pp-info.php';
$return     .=  '<div class="wx-settings-outer-wrap">';
$return     .=  '<div class="wx-plugin-settings">';
$return     .=  '<form action="'.menu_page_url( 'wx-popular-posts', false ).'" name="WX-pp-setting" id="WX-pp-setting" method="post">';
$return		.=	'<table class="form-table"><tbody>';
$return     .=  '<input type="hidden" name="WX-pp-settings">';
$return     .=  wp_nonce_field( plugin_basename( __FILE__ ), 'WX_nonce_settings',true,false);
$return		.=	'<tr><th scope="row">';
$return		.=	'	<label for="WX-pp-post-num">'.__('Number of Posts',WX_PP_TEXT_DOMAIN).'</label></th>';
$return		.=	'	<td><input name="WX-pp-post-num" type="text" id="WX-pp-post-num" value="'.$WX_get_pp_options['WX-pp-post-num'].'" class="regular-text">';
$return		.=	'	<p class="description">'.__('total number of most popular posts to show (default: 5)',WX_PP_TEXT_DOMAIN).'</p></td>';
$return		.=	'</tr>';
$return		.=	'<tr><th scope="row">';
$return		.=	'	<label for="WX-pp-post-type">'.__('Post Type',WX_PP_TEXT_DOMAIN).'</label></th>';
$return		.=	'	<td><select name="WX-pp-post-type" id="WX-pp-post-type">';
$return     .=  '       <option value="post"';
            if(isset($WX_get_pp_options['WX-pp-post-type']) && $WX_get_pp_options['WX-pp-post-type'] == 'post')
            {
                $return .=  'selected="selected"';
            }
$return     .=  '        >Post</option>';

            foreach($post_types as $post_type)
            {
                $return     .=  '<option value="'.$post_type.'"';
                if(isset($WX_get_pp_options['WX-pp-post-type']) && $WX_get_pp_options['WX-pp-post-type'] == $post_type)
                {
                    $return .=  'selected="selected"';
                }
                $return     .=  '>'.$post_type.'</option>';
            }
$return     .=  '        </select>';
$return		.=	'	<p class="description">'.__('select post type (default: post)',WX_PP_TEXT_DOMAIN).'</p></td>';
$return		.=	'</tr>';
$return		.=	'<tr><th scope="row">';
$return		.=	'	<label>'.__('Post Meta',WX_PP_TEXT_DOMAIN).'</label></th>';
$return		.=	'	<td><input name="WX-pp-author" type="checkbox" id="WX-pp-author"';
if(isset($WX_get_pp_options['WX-pp-author']))
{
    $return .=  'checked="checked"';
}
$return     .=  '> '.__('Post Author', WX_PP_TEXT_DOMAIN).'<br>';
$return     .=  '       <input name="WX-pp-date" type="checkbox" id="WX-pp-date"';
if(isset($WX_get_pp_options['WX-pp-date']))
{
    $return .=  'checked="checked"';
}
$return     .=  '> '.__('Post Date', WX_PP_TEXT_DOMAIN).'<br>';
$return     .=  '       <input name="WX-pp-tags" type="checkbox" id="WX-pp-tags"';
if(isset($WX_get_pp_options['WX-pp-tags']))
{
    $return .=  'checked="checked"';
}
$return     .=  '> '.__('Post Tags', WX_PP_TEXT_DOMAIN).'<br>';
$return     .=  '       <input name="WX-pp-cats" type="checkbox" id="WX-pp-cats"';
if(isset($WX_get_pp_options['WX-pp-cats']))
{
    $return .=  'checked="checked"';
}
$return     .=  '> '.__('Post Categories', WX_PP_TEXT_DOMAIN).'<br>';
$return     .=  '       <input name="WX-pp-views" type="checkbox" id="WX-pp-views"';
if(isset($WX_get_pp_options['WX-pp-views']))
{
    $return .=  'checked="checked"';
}
$return     .=  '> '.__('Number of Views', WX_PP_TEXT_DOMAIN).'<br>';
$return		.=	'	</td>';
$return		.=	'</tr>';
$return		.=	'<tr><th scope="row">';
$return		.=	'	<label for="WX-pp-views-temp">'.__('Views Text',WX_PP_TEXT_DOMAIN).'</label></th>';
$return		.=	'	<td><input name="WX-pp-views-temp" type="text" id="WX-pp-views-temp" value="'.@$WX_get_pp_options['WX-pp-views-temp'].'" class="regular-text">';
$return		.=	'	<p class="description">'.__('how to display number of views e.g Views:10. use {views} to get number of views. default ( Views: {views})',WX_PP_TEXT_DOMAIN).'</p></td>';
$return		.=	'</tr>';
$return		.=	'<tr><th scope="row">';
$return		.=	'	<label>'.__('Post Excerpt',WX_PP_TEXT_DOMAIN).'</label></th>';
$return		.=	'	<td><input name="WX-pp-excerpt" type="checkbox" id="WX-pp-excerpt"';
if(isset($WX_get_pp_options['WX-pp-excerpt']))
{
    $return .=  'checked="checked"';
}
$return     .=  '> '.__('Show Excerpt', WX_PP_TEXT_DOMAIN).'<br>';
$return     .=  '       <input name="WX-pp-length" type="text" id="WX-pp-length" placeholder="length" value="'.$WX_get_pp_options['WX-pp-length'].'">';
$return		.=	'	    <p class="description">'.__('choose to show excerpt and give character length (default: No)',WX_PP_TEXT_DOMAIN).'</p></td>';
$return		.=	'</tr>';
$return		.=	'<tr><th scope="row">';
$return		.=	'	<label form="WX-pp-thumbnail">'.__('Post Thumbnail',WX_PP_TEXT_DOMAIN).'</label></th>';
$return		.=	'	<td><input name="WX-pp-thumbnail" type="checkbox" id="WX-pp-thumbnail"';

if(isset($WX_get_pp_options['WX-pp-thumbnail']))
{
    $return .=  'checked="checked"';
}

$return     .=  '> '.__('Show Thumbnail', WX_PP_TEXT_DOMAIN).'</td>';
$return		.=	'</tr>';
$return		.=	'<tr><th scope="row">';
$return		.=	'	<label for="WX-pp-post-thumb-size">'.__('Thumbnail Size',WX_PP_TEXT_DOMAIN).'</label></th>';
$return		.=	'	<td><select name="WX-pp-post-thumb-size" id="WX-pp-post-thumb-size">';
$return     .=  '   <option value="small"';
if($WX_get_pp_options['WX-pp-post-thumb-size'] == 'small')
{
    $return .=  'selected="selected"';
}
$return     .=  '>Small</option><option value="medium"';
if($WX_get_pp_options['WX-pp-post-thumb-size'] == 'medium')
{
    $return .=  'selected="selected"';
}
$return     .=  '>Medium</option><option value="Large"';
if($WX_get_pp_options['WX-pp-post-thumb-size'] == 'large')
{
    $return .=  'selected="selected"';
}
$return     .=  '>Large</option>';
$return		.=	'	</select></><p class="description">'.__('Select image size (default: small)',WX_PP_TEXT_DOMAIN).'</p></td>';
$return		.=	'</tr>';
$return		.=	'<tr><th scope="row">';
$return		.=	'	<label for="WX-pp-post-thumb-position">'.__('Thumbnail Size',WX_PP_TEXT_DOMAIN).'</label></th>';
$return		.=	'	<td><select name="WX-pp-post-thumb-position" id="WX-pp-post-thumb-position">';
$return     .=  '   <option value="top"';
if($WX_get_pp_options['WX-pp-post-thumb-position'] == 'top')
{
    $return .=  'selected="selected"';
}
$return     .=  '>Top</option><option value="left"';
if($WX_get_pp_options['WX-pp-post-thumb-position'] == 'left')
{
    $return .=  'selected="selected"';
}
$return     .=  '>Left</option>';

$return		.=	'	</select></><p class="description">'.__('Select thumbnail position (default: top)',WX_PP_TEXT_DOMAIN).'</p></td>';
$return		.=	'</tr>';

$return		.=	'	<td>'.get_submit_button( 'Save' ).'</td>';
$return		.=	'</tr>';

$return     .=  '</tbody></table>';
$return     .=  '</form>';
$return     .=  '</div>';
$return     .=  '<div class="wx-plugin-info">';
$return     .=  $thisischeck;
$return     .=  '</div><div class="clear-fix"></div> ';
$return     .=  '</div>';

echo $return;