<div class="partners-container">
	<?php

	if ( isset( $advised ) && $advised === true ) {

	    // FP UPDATED BY HARRY 03/11/22 Original:
	    /*
		$args = array(
			'post_type'      => 'platform',
			'posts_per_page' => - 1,
			'order'          => 'ASC',
			'orderby'        => 'title',
			'meta_query'     => array(
				array(
					'key'   => '_cplat_inv_management_type',
					'value' => 'advised'
				)
			)
		);
	    */

		$args = array(
			'post_type'      => 'platform',
			'posts_per_page' => - 1,
			'order'          => 'ASC',
			'orderby'        => 'title',
			'ignore_custom_sort' => TRUE,
			'meta_query'     => array(
				array(
					'key'   => '_cplat_inv_management_type',
					'value' => 'advised'
				)
			)
		);

	} elseif ( isset( $d2c ) && $d2c === true ) {

	    // FP UPDATED BY HARRY 03/11/22 Original:
	    /*
		$args = array(
			'post_type'      => 'platform',
			'posts_per_page' => - 1,
			'order'          => 'ASC',
			'orderby'        => 'title',
			'meta_query'     => array(
				array(
					'key'   => '_cplat_inv_management_type',
					'value' => 'd2c'
				)
			)
		);
	    */
		$args = array(
			'post_type'      => 'platform',
			'posts_per_page' => - 1,
			'order'          => 'ASC',
			'orderby'        => 'title',
			'ignore_custom_sort' => TRUE,
			'meta_query'     => array(
				array(
					'key'   => '_cplat_inv_management_type',
					'value' => 'd2c'
				)
			)
		);

	} else {
	    // FP UPDATED BY HARRY 03/11/22 Original: post_type=platform&posts_per_page=-1&order=ASC
		$args = 'post_type=platform&posts_per_page=-1&order=ASC&orderby=title&ignore_custom_sort=true';
	}
	$partners_query = new WP_Query( $args );


	while ( $partners_query->have_posts() ) : $partners_query->the_post();
	 	$_cplat_inv_type = get_post_meta( get_the_ID(), '_cplat_inv_management_type', true ); // advised OR d2c
		$p_type = ( $_cplat_inv_type == 'd2c' ? 'D2C' : 'ADV'  );
	 ?>
        <div class="row cplat-partner-item">
            <div class="col-lg-3">
				<?php the_post_thumbnail(); ?>
            </div>
            <div class="col-lg-9">
                <h2>
				<a href="<?php the_permalink(); ?>" data-vars-ga-category="<?php echo $p_type; ?> Platform Page" 
				data-vars-ga-action="<?php the_permalink(); ?>" 
				data-vars-ga-label="<?php the_title(); ?>">
					<?php the_title(); ?>
				</a>
				</h2>
				<?php the_content(); ?>
            </div>
        </div>


	<?php endwhile;
	wp_reset_query(); ?>
</div>