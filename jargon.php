<?php

function cplat_jargon_shortcode() {
	$letter = $_GET['letter'] ? sanitize_text_field($_GET['letter']) : 'all';

	$letters = get_terms(
		array(
			'taxonomy' => 'jargon_letter',
			'hide_empty' => false
		)
	);

	$jargon_url = get_permalink( get_page_by_path('jargon-terms') );

	if ($letter !== 'all') {

		$tax_query = array(
			array(
				'taxonomy' => 'jargon_letter',
				'field' => 'slug',
				'terms' => array($letter)
			)
		);

	} else {
		$tax_query = array();
	}

	$args = array(
		'post_type' => 'jargon',
		'orderby'=> 'title',
		'order' => 'ASC',
		'posts_per_page' => -1,
		'tax_query' => $tax_query

	);
	$posts = new WP_Query($args);

	ob_start();
	include 'templates/jargon-terms.php';
	return ob_get_clean();
}
add_shortcode('jargon', 'cplat_jargon_shortcode');