<?php
/*
Plugin Name: BEMO A-Z Index
Plugin URI: http://www.bemoore.com/bemo-a-z-index/
Description: This is a simple plugin that provides an A-Z index of the posts displayed on a particular page based on the post title.
Version: 0.0.7
Author: Bob Moore (BeMoore Software)
Author URI: http://www.bemoore.com
License: GPL2
*/

/*  
Copyright 2013  Bob Moore  (email : bob.moore@bemoore.com)

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
/********************************************************************/
/* Include the core code												*/
/********************************************************************/
include('class.BEMOAZIndex.php');	//Specific to this version

//Add WHERE addition to query 
function bemoazindex_posts_where( $where, &$wp_query) {
    global $wpdb;
	global $azindex;
	global $azfilter;
	
	$index = new BEMOAZIndex();	
	
	if(isset($azfilter))
		$index->setFilter($azfilter);
		
	if(isset($azindex))	
		$where = $index->getWhere($where,$azindex,$wpdb,$wp_query);

    return $where;
}
add_filter( 'posts_where' , 'bemoazindex_posts_where',10,2 );

//Add stylesheet
/**
 * Enqueue plugin style-file
 */
function bemoazindex_add_stylesheet() {
	// Respects SSL, Style.css is relative to the current file
	wp_register_style( 'bemoazindex-style', plugins_url('bemoazindex.css', __FILE__) );
	wp_enqueue_style( 'bemoazindex-style' );
}
add_action( 'wp_enqueue_scripts', 'bemoazindex_add_stylesheet' );

/********************************************************************/
/* FILTERS															*/
/********************************************************************/
// Add the query var bemoazindex so WP won't drop it
add_filter( 'query_vars', 'bemoazindex_add_query_vars');

function bemoazindex_add_query_vars($vars){
    $vars[] = "azindex";
	$vars[] = "azfilter";
    return $vars;
}

/********************************************************************/
/* MAIN CODE - specific to version														*/
/********************************************************************/
function bemoazindex_get_index( $attr )
{
	$selected = get_query_var('azindex');
	
	if($selected == '')
		$selected = -1;
	
	$index = new BEMOAZIndex();	

	if(isset($attr['filter']))
		$index->setFilter($attr['filter']);
				
	if(isset($attr['index']))	//Predefined index
		$index->get_predefined_index($attr['index'],$selected);
	else						//Simple index
		$index->get_simple_index($selected);
}

/********************************************************************/
/* ACTIONS															*/
/********************************************************************/
//Pick up the shortcode
add_action('init', 'set_index_vars');
function set_index_vars()
{
	global $azindex;
	global $azfilter;
	
	$tmp = get_query_var('azindex');
	
	if($tmp != "")
		$azindex = $tmp;

	$tmp = get_query_var('azfilter');
	
	if($tmp != "")
		$azfilter = $tmp;
}

/********************************************************************/
/* SHORTCODES														*/
/********************************************************************/
//Register the AZ Index shortcode [azindex]
$azindex = '';
$azfilter = '';

add_shortcode('azindex','bemoazindex_get_filter');
function bemoazindex_get_filter($attr){

	echo '<div class="bemoazindex clearfix">';
	bemoazindex_get_index($attr); 
	echo '</div>';
}
?>