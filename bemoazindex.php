<?php
/*
Plugin Name: BEMO A-Z Index
Plugin URI: http://www.bemoore.com/bemo-a-z-index-pro/
Description: This is a simple plugin that provides an A-Z index of the posts displayed on a particular page based on the post title.
Version: 0.1.4
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
require_once('class.BEMOAZIndex.php');	//Specific to pro version

//To stop all queries being affected.
function bemoazindexApplyQueryFilter($wp_query)
{
	global $azindex;
	
	//echo 'AZindex is :'.$azindex;

	//For a category listing, this is always 0
	if($wp_query->is_archive)
	{	
		if(BEMOAZIndex::$callcount == 0)
			return true;
	}

	//Else ... must be an azindex one
	//TODO: better error checking here.
	if(is_array($wp_query->query_vars['tax_query']))
		return true;

	return false;
}


//Add WHERE addition to query 
function bemoazindex_posts_where( $where, &$wp_query) 
{
	// TODO: Optimise this
	if(!bemoazindexApplyQueryFilter($wp_query))
	{
		BEMOAZIndex::$callcount++;
		return $where;
	}

	global $wpdb;
	
	global $azindex;
	global $azfilter;
	global $azcategory;
	global $azposttype;
	global $aztemplate;
	global $azpostcount;
	global $azorderby;
	global $azignoreprefixes;
	
	if(!isset($azorderby))
		$azorderby = get_query_var('azorderby');	
	if(!isset($azindex))
		$azindex = get_query_var('azindex');	
	if(!isset($azfilter))
		$azfilter = get_query_var('azfilter');	
	if(!isset($azcategory))
		$azcategory = get_query_var('azcategory');	
	if(!isset($azposttype))
		$azposttype = get_query_var('azposttype');	
	if(!isset($aztemplate))
		$aztemplate = get_query_var('aztemplate');	
	if(!isset($azpostcount))
		$azpostcount = get_query_var('azpostcount');	
	
	if(!isset($azignoreprefixes))
		$azignoreprefixes = get_query_var('azignoreprefixes');	
	
	BEMOAZIndex::initialize();
	BEMOAZIndex::$callcount++;
	
	if(isset($azindex))
	{
		BEMOAZIndex::setIndex($azindex);	
	}
	if(isset($azorderby))
	{
		BEMOAZIndex::setOrderBy($azorderby);	
	}
	if(isset($azignoreprefixes))
	{
		BEMOAZIndex::setIgnorePrefixes($azignoreprefixes);	
	}
	if(isset($azfilter))
	{
		BEMOAZIndex::setFilter($azfilter);
	}
	if(isset($azcategory))
	{
		BEMOAZIndex::setCategory($azcategory);
	}
	if(isset($azposttype))
	{
		BEMOAZIndex::setPostType($azposttype,$wp_query);
	}
	if(isset($azpostcount))
	{
		BEMOAZIndex::setPostCount($azpostcount,$wp_query);
	}
	if(isset($aztemplate))	
	{
		BEMOAZIndex::setTemplate($aztemplate);
	}	
	
	if(isset($azindex))	
	{
		$where = BEMOAZIndex::getWhere($where,$wpdb,$wp_query);
	}
	
    return $where;
}
add_filter( 'posts_where' , 'bemoazindex_posts_where',10,2 );

function bemoazindex_posts_orderby($orderby, &$wp_query) {
	
	if(!bemoazindexApplyQueryFilter($wp_query))
		return $orderby;
	
	global $wpdb;
	
	$orderby = BEMOAZIndex::getOrderBy($orderby,$wpdb,$wp_query);
	
	return $orderby;
}
add_filter('posts_orderby', 'bemoazindex_posts_orderby',11,2);

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
add_filter( 'query_vars', 'bemoazindex_add_query_vars', 1, 1 );

function bemoazindex_add_query_vars($vars){
	$vars[] = "azindex";
	$vars[] = "azorderby";
	$vars[] = "azignoreprefixes";
	$vars[] = "azfilter";
	$vars[] = "azcategory";
	$vars[] = "azposttype";
	$vars[] = "azpostcount";
	$vars[] = "aztemplate";
    return $vars;
}

/********************************************************************/
/* Include the widget												*/
/********************************************************************/
include('class.WPBemoazindexWidget.php');
include('class.WPBemoazindexOutputWidget.php');

/********************************************************************/
/* Include the custom posts code									*/
/********************************************************************/
include('bemoazindex_custom_posts.php');

/********************************************************************/
/* MAIN CODE - specific to version									*/
/********************************************************************/
function bemoazindex_get_index( $attr )
{
	BEMOAZIndex::initialize();
	
	main_body_filter($index,$attr);
	
	$retval = '';
	
	if(isset($attr['index']))	//Predefined index
		$retval .= BEMOAZIndex::get_predefined_index($attr['index']);
	else						//Simple index
		$retval .= BEMOAZIndex::get_simple_index();
	
	return $retval;
}

function bemoazindex_page_nav()
{
	BEMOAZIndex::initialize();	
	BEMOAZIndex::pageNav();
}

function bemoazindex_get_output( $attr )
{
	$aztemplate = get_query_var('aztemplate');	
	
	BEMOAZIndex::initialize();	
	
	main_body_filter($index,$attr);
	
	if(isset($attr['template']) && $attr['template'] != '')
		BEMOAZIndex::setTemplate($attr['template']);
	else if($aztemplate != '')
		BEMOAZIndex::setTemplate($aztemplate);
	
	//Outputs the bottom part (if any)			
	return BEMOAZIndex::getOutput();				
}

function main_body_filter(&$index,$attr)
{
	$azindex = get_query_var('azindex');	
	$azorderby = get_query_var('azorderby');	
	$azignoreprefixes = get_query_var('azignoreprefixes');	
	$azcategory = get_query_var('azcategory');	
	$azposttype = get_query_var('azposttype');	
	$azpostcount = get_query_var('azpostcount');
	
	if($azindex != '')
		BEMOAZIndex::setIndex($azindex);	

	if($azorderby != '')
		BEMOAZIndex::setOrderBy($azorderby);	

	if($azignoreprefixes != '')
		BEMOAZIndex::setIgnorePrefixes($azignoreprefixes);	
		
	if(isset($attr['filter']))
		BEMOAZIndex::setFilter($attr['filter']);

	if(isset($attr['orderby']))
		BEMOAZIndex::setOrderBy($attr['orderby']);

	if(isset($attr['ignoreprefixes']))
		BEMOAZIndex::setIgnorePrefixes($attr['ignoreprefixes']);

	if(isset($attr['category']))
		BEMOAZIndex::setCategory($attr['category']);
	else if($azcategory != '')
		BEMOAZIndex::setCategory($azcategory);

	if(isset($attr['posttype']) )
		BEMOAZIndex::setPostType($attr['posttype']);
	else if($azposttype != '')
		BEMOAZIndex::setPostType($azposttype);

	if(isset($attr['postcount']) )
		BEMOAZIndex::setPostCount($attr['postcount']);
	else if($azpostcount != '')
		BEMOAZIndex::setPostCount($azpostcount);	
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
	global $azorderby;
	global $azcategory;
	global $azposttype;
	global $azpostcount;
	global $azignoreprefixes;
	
	$tmp = get_query_var('azindex');
	
	if($tmp != "")
		$azindex = $tmp;

	$tmp = get_query_var('azignoreprefixes');
	
	if($tmp != "")
		$azignoreprefixes = $tmp;

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
		
	$tmp = get_query_var('azorderby');
	
	if($tmp != "")
		$azorderby = $tmp;				
		
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