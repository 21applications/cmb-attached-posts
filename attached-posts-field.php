<?php
/**
 * Add a CMB custom field to allow for the selection of multiple posts
 * attached to a single page
 */
add_action( 'cmb_render_custom_attached_posts', 'cmb_render_custom_attached_posts_callback', 10, 2 );
function cmb_render_custom_attached_posts_callback( $field, $meta ) {

	$defaults = array( 'post_type' => array( 'post' ) );
	$field = wp_parse_args( $field, $defaults );
	
	// Grab our attached posts meta
	$attached = get_post_meta( get_the_ID(), '_attached_posts', true );

	// Setup our args
	$args = array(
		'post_type'			=> $field['post_type'],
		'posts_per_page'	=> -1,
		'orderby'			=> 'name',
		'order'				=> 'ASC',
	);

	// Get our posts
	$posts = get_posts( $args );

	// Build our dropdown
	if( empty( $meta ) && ! empty( $field['std'] ) )
		$meta = $field['std'];
	elseif ( empty( $meta ) && empty ( $field['std'] ) )
		$meta = array();

	// If there are no posts found, just stop
	if ( ! $posts )
		return;

	// Set our count class
	$count = 0;

	// Wrap our lists
	echo '<div id="posts-wrap">';

	// Open our retrieved, or found posts, list
	echo '<ul id="retrieved" class="connected">';

	// Loop through our posts as list items
	foreach ( $posts as $post ) {

		// Increase our count
		$count++;

		// Set our zebra stripes
		$zebra = $count % 2 == 0 ? 'even' : 'odd';

		// Set a class if our post is in our attached post meta
		$added = in_array( $post->ID, $attached ) ? ' added' : '';


		// Build our list item
		echo '<li id="', $post->ID ,'" class="' . $zebra . $added . '">', $post->post_title ,'<span class="sprite add-remove"></span></li>';

	}

	// Close our retrieved, or found, posts
	echo '</ul><!-- #retrieved -->';

	// Open our attached posts list
	echo '<ul id="attached" class="connected">';

	// If we have any posts saved already, display them
	echo custom_check_for_attached_posts( $field );

	// Close up shop
	echo '</ul><!-- #attached -->';
	echo '</div><!-- #posts-wrap -->';

	// Display our description if one exists
	echo '<p class="cmb_metabox_description">', $field['desc'], '</p>';

}


/**
 * Helper function to grab and filter our post meta
 */
function custom_check_for_attached_posts( $field ) {

	// Start with nothing
	$output = '';

	// Check to see if we have any meta values saved yet
	$attached = get_post_meta( get_the_ID(), $field['id'], true );

	// If we do, then we need to display them as items in our attached list
	if ( ! $attached ) {

		$output .= '<input type="hidden" name="' . $field['id'] . '">';

	} else {

		// Set our count to zero
		$count = 0;

		// Remove any empty values
		$attached = array_filter( $attached );

		// Loop through and build our existing display items
		foreach ( $attached as $post ) {

			// Increase our count
			$count++;

			// Set our zebra stripes
			$zebra = $count % 2 == 0 ? 'even' : 'odd';

			// Build our list item
			$output .= '<li id="' . $post . '" class="' . $zebra . '">' . get_the_title( $post ) . '<input type="hidden" value="' . $post . '" name="' . $field['id'] . '[]"><span class="sprite add-remove"></span></li>';
		}

	}

	return $output;

}
