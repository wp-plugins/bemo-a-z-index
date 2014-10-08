<?php
/*
Plugin Name: BEMO A-Z Index
Plugin URI: http://www.bemoore.com/bemo-a-z-index-pro/
Description: This is a simple plugin that provides an A-Z index of the posts displayed on a particular page based on the post title.
Version: 0.0.9
Author: Bob Moore (BeMoore Software)
Author URI: http://www.bemoore.com
License: GPL2
*/

/*  
Copyright 2013-2014  Bob Moore  (email : bob.moore@bemoore.com)

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
require_once('class.BEMOAZIndexPro.php');	//Specific to pro version

//Add WHERE addition to query 
function bemoazindex_posts_where( $where, &$wp_query) {
    global $wpdb;
	global $azindex;
	global $azfilter;
	global $azcategory;
	global $azposttype;
	global $aztemplate;
	global $azpostcount;
	
	$index = new BEMOAZIndexPro();	
	
	if(isset($azindex))
		$index->setIndex($azindex);	
	
	if(isset($azfilter))
		$index->setFilter($azfilter);

	if(isset($azcategory))
		$index->setCategory($azcategory);

	if(isset($azposttype))
		$index->setPostType($azposttype);

	if(isset($azpostcount))
		$index->setPostCount($azpostcount);

	if(isset($aztemplate))	
		$index->setTemplate($aztemplate);
		
	if(isset($azindex))	
		$where = $index->getWhere($where,$wpdb,$wp_query);

		
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
	$vars[] = "azcategory";
	$vars[] = "azposttype";
	$vars[] = "azpostcount";
	$vars[] = "aztemplate";
    return $vars;
}

/********************************************************************/
/* Include the widget (pro only)											*/
/********************************************************************/
include('class.WPBemoazindexWidget.php');
include('class.WPBemoazindexOutputWidget.php');

/********************************************************************/
/* Include the custom posts code (pro only)										*/
/********************************************************************/
include('bemoazindex_custom_posts.php');

/********************************************************************/
/* MAIN CODE - specific to version														*/
/********************************************************************/
function bemoazindex_get_index( $attr )
{
	$index = new BEMOAZIndexPro();	
	
	main_body_filter($index,$attr);
	
	if(isset($attr['index']))	//Predefined index
		$retval .= $index->get_predefined_index($attr['index']);
	else						//Simple index
		$retval .= $index->get_simple_index();
	
	return $retval;
}

function bemoazindex_get_output( $attr )
{
	$aztemplate = get_query_var('aztemplate');	
	
	$index = new BEMOAZIndexPro();	
	
	main_body_filter($index,$attr);
	
	if(isset($attr['template']))
		$index->setTemplate($attr['template']);
	else if($aztemplate != '')
		$index->setTemplate($aztemplate);
	
	//Outputs the bottom part (if any)			
	return $index->getOutput();				
}

function main_body_filter(&$index,$attr)
{
	//var_dump($attr);
	$azindex = get_query_var('azindex');	
	$azcategory = get_query_var('azcategory');	
	$azposttype = get_query_var('azposttype');	
	$azpostcount = get_query_var('azpostcount');	
	
	if($azindex != '')
		$index->setIndex($azindex);	

	if(isset($attr['filter']))
		$index->setFilter($attr['filter']);

	if(isset($attr['category']))
		$index->setCategory($attr['category']);
	else if($azcategory != '')
		$index->setCategory($azcategory);

	if(isset($attr['posttype']) )
		$index->setPostType($attr['posttype']);
	else if($azposttype != '')
		$index->setPostType($azposttype);

	if(isset($attr['postcount']) )
		$index->setPostCount($attr['postcount']);
	else if($azpostcount != '')
		$index->setPostCount($azpostcount);	
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
	global $azcategory;
	global $azposttype;
	global $azpostcount;
	
	$tmp = get_query_var('azindex');
	
	if($tmp != "")
		$azindex = $tmp;

	$tmp = get_query_var('azfilter');
	
	if($tmp != "")
		$azfilter = $tmp;

	$tmp = get_query_var('azcategory');
	
	if($tmp != "")
		$azcategory = $tmp;
		
	$tmp = get_query_var('azposttype');
	
	if($tmp != "")
		$azposttype = $tmp;		

	$tmp = get_query_var('azpostcount');
	
	if($tmp != "")
		$azpostcount = $tmp;		
		
}

/********************************************************************/
/* SHORTCODES														*/
/********************************************************************/
//Register the AZ Index shortcode [azindex]
//$azindex = '';
//$azfilter = '';
add_shortcode('azindex','bemoazindex_get_index_filter');
function bemoazindex_get_index_filter($attr){
	return bemoazindex_get_index($attr); 
}

add_shortcode('azindexoutput','bemoazindex_get_output_filter');
function bemoazindex_get_output_filter($attr){
	return bemoazindex_get_output($attr); 
}
?>