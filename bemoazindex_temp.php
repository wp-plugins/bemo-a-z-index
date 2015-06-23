<?php
global $azindex_shortcode_settings;

//$azindex_shortcode_settings = array();
/*
Plugin Name: BEMO A-Z Index
Plugin URI: http://www.bemoore.com/bemo-a-z-index-pro/
Description: This is a simple plugin that provides an A-Z index of the posts displayed on a particular page based on the post title.
Version: 0.1.6
Author: Bob Moore (BeMoore Software)
Author URI: http://www.bemoore.com
License: GPL2
*/

/*  
Copyright 2013-2015  Bob Moore  (email : bob.moore@bemoore.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function azindex_init() {
   /* Register our stylesheet. */
   wp_register_style( 'azindex_stylesheet', plugins_url('bemoazindex.css', __FILE__) );
   wp_enqueue_style('azindex_stylesheet');
}
add_action('wp_enqueue_scripts', 'azindex_init');   

function azindex_filter($where, &$wp_query){
    global $wpdb;
    if($search_term = $wp_query->get( 'azindex_title_filter' ))
    {
        $search_term = "'" . $wpdb->esc_like($search_term) . "%'";
        $azindex_title_filter_relation = (strtoupper($wp_query->get( 'azindex_filter_relation'))=='OR' ? 'OR' : 'AND');
        $where .= ' '.$azindex_title_filter_relation.' ' . $wpdb->posts . '.post_title LIKE '.$search_term;
    }
    return $where;
}

function azindex($atts)
{

	//Load the css file 
		
//	echo '<pre>'.print_r($azindex_shortcode_settings).'</pre>';
	
	
	echo azindex_get_index($atts);
/*
	global $azindexquery;

	$azindex = get_query_var('azindex');
	$post_type = get_query_var('posttype') ? get_query_var('posttype') : 'post';
	$filter = get_query_var('filter') ? get_query_var('filter') : 'title';
	
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'orderby'     => 'title', 
		'order'       => 'ASC'
	);
	
	
	if($azindex != "") //A letter selected, filter by filter type ...
	{
		$filter_name = 'azindex_'.$filter.'_filter';
		
		add_filter('posts_where','azindex_filter',10,2);
		
		$args[$filter_name] = $azindex;
		$args['azindex_filter_relation'] = 'AND';

		$azindexquery = new WP_Query( $args );

		remove_filter('posts_where','azindex_filter',10,2);	
	}
	else //All
		$azindexquery = new WP_Query( $args );
	
	//Now we load the listing template ....
	bemoazindex_load_plugin_template( );
*/ 
}

add_shortcode('azindex', 'azindex');


function azindex_get_simple_index($azindex, $filter)
{
	$retval = '';
	
	for($i=0;$i<26;$i++)
	{
		$letter[$i] = chr($i + 65);
		$href = '';
		
		$href = add_query_arg( array('azindex' => $letter[$i], 'filter' => $filter ) );
		
		if($azindex == "")	//Not selected -> link
			$retval .= '<div><a href="'.$href.'">'.$letter[$i].'</a></div>';
		else if($azindex == $letter[$i])
			$retval .= '<div class="selected" >'.$letter[$i].'</div>';
		else
			$retval .= '<div><a href="'.$href.'">'.$letter[$i].'</a></div>';
	}
	
	return $retval;
}

function azindex_get_predefined_index($azindex, $predefined, $filter)
{
	$retval = '';
	$indexes = explode(",",$predefined);
	
	for($i=0;$i<count($indexes);$i++)
	{
		$href = add_query_arg( array('azindex' => $indexes[$i], 'filter' => $filter , 'index' => $predefined ) );

		if($azindex == "")	//Not selected -> link
			$retval .= '<div><a href="'.$href.'">'.$indexes[$i].'</a></div>';
		else if($azindex == $indexes[$i])
			$retval .= '<div class="selected" >'.$indexes[$i].'</div>';
		else
			$retval .= '<div><a href="'.$href.'">'.$indexes[$i].'</a></div>';
	}
	
	return $retval;
}	


function azindex_get_index($atts)
{
	$retval = '';
	$filter = isset($atts['filter']) ? $atts['filter'] : 'title';
	$index = isset($atts['index']) ? $atts['index'] : '';
	$azindex = get_query_var('azindex');
	
	$retval .= '<div class="bemoazindex" >';
	
	if($index == '')
		$retval .= azindex_get_simple_index($azindex,$filter);
	else
		$retval .= azindex_get_predefined_index($azindex, $index,$filter);

	
	if($azindex == "")	//Not selected -> link
		$retval .= '<div>ALL</div>';
	else
	{
		$href = $_SERVER["REQUEST_URI"] ;
		$href = remove_query_arg('azindex',$href);
		$retval .= '<div><a href="'.$href.'">ALL</a></div>';
	}

	$retval .= '</div>';

	return $retval;
}

function add_bemoazindex_query_vars( $vars )
{
  $vars[] = "azindex";
  return $vars;
}
add_filter( 'query_vars', 'add_bemoazindex_query_vars' );

//Load the listing template
/*
function bemoazindex_load_plugin_template( $template = 'listing' )
{
	if ( $overridden_template = locate_template( $template . '.php' ) ) 
	{
	   // locate_template() returns path to file
	   // if either the child theme or the parent theme have overridden the template
	   load_template( $overridden_template );
	 } 
	 else 
	 {
		 $plugin_template_path = dirname( __FILE__ ) . '/templates/'.$template . '.php';
		locate_template( $plugin_template_path );
	   // If neither the child nor parent theme have overridden the template,
	   // we load the template from the 'templates' sub-directory of the directory this file is in
	   load_template( dirname( __FILE__ ) . '/templates/'.$template . '.php' );
	 }	
}
*/

add_filter( 'posts_where' , 'azindex_posts_where' );
 
function azindex_posts_where( $where ) 
{
 //gets the global query var object
	global $wp_query;
	global $wpdb;

	$azindex = get_query_var('azindex');
	$filter = get_query_var('filter') != "" ? get_query_var('filter') : 'title';
	
	if($azindex != "")
	{
		if(strlen($azindex) == 1)
			$where .= " AND {$wpdb->posts}.post_".$filter." LIKE '".$azindex."%'";
		else
			 $where .= " AND {$wpdb->posts}.post_".$filter." REGEXP '^[".$azindex."]'";
	}
	

	
	return $where;
}
?>
