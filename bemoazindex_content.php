<?php

function getAZIndexContent($settings)
{
/*
	echo '<pre>';
	print_r($settings);
	echo '</pre>';
*/	

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	
	$args = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'paged' => $paged
	);
		
	if($settings["postcount"] != '')
		$args['showposts'] = $settings["postcount"];
	else
		$args['showposts'] = get_query_var('showposts');
	
	if($settings["category"] != '')
		$args['category_name'] = $settings["category"];

	if($settings["posttype"] != '')
		$args['post_type'] = $settings["posttype"];

	global $azindexquery,$wp_query;
	
	$azindexquery = new WP_Query( $args );
	
	// Pagination fix
	$temp_query = $wp_query;
	$wp_query   = NULL;
	$wp_query   = $azindexquery;	
	
	bemoazindex_load_plugin_template();
	
	// Reset main query object
	$wp_query = NULL;
	$wp_query = $temp_query;	
}


//Load the listing template
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

?>
