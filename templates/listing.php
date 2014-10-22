<?php
		$record_count = 0;
		if( $records->have_posts() ) 
		{
			while( $records->have_posts() ) 
			{
				$records->the_post();
				$content = get_the_content();
?>
				<h1><?php the_title(); ?></h1>
				
				<?php if(strpos($content,'azindex') === false ){ ?>
					<div class="content"><?php echo do_shortcode($content); ?></div>
				<?php } else { ?>
					<div class="content"><?php echo $content; ?></div>
<?php			}
			$record_count++;
			}
		}
		else 
		{
		?>
			<div class="content">No posts found.</div>
		<?php
		}
		
		?>
		<div class="bemoazindex-listing-nav">
		<?php	
			next_posts_link( 'Older Entries', $records->max_num_pages);?>
			&nbsp;
			<?php
			previous_posts_link( 'Newer Entries' );
		?>
		</div>
		<?php	
?>