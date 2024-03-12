<div class="partners-container">
<?php
$partners_query = new WP_Query('post_type=partner&posts_per_page=-1');
    while ($partners_query->have_posts()) : $partners_query->the_post(); ?>
	<div class="row cplat-partner-item">
		<div class="col-lg-3">
			<?php the_post_thumbnail(); ?>
		</div>
		<div class="col-lg-9">
			<h2><?php the_title(); ?></h2>
			 <?php the_content(); ?>
		</div>
	</div>


<?php endwhile;  wp_reset_query(); ?>
</div>