<div class="az-bar">
<ul class="clearfix">
	<li class="<?php echo $letter === 'all' ? 'selected' : ''; ?>"><a href="<?php echo esc_url($jargon_url); ?>">All</a></li>
    <?php foreach ($letters as $letter_term) :
        $letter_url = add_query_arg( 'letter', $letter_term->slug, $jargon_url);
    ?>
	<li class="<?php echo $letter === $letter_term->slug ? 'selected' : ''; ?>"><a href="<?php echo esc_url( $letter_url ) ?>"><?php echo $letter_term->name; ?></a></li>
<?php endforeach; ?>
</ul>

</div>
<?php
$jargons = $posts->get_posts();
foreach ($jargons as $jargon) :
$excerpt =  get_post_meta($jargon->ID, 'jargon_excerpt', true);
$description =  get_post_meta($jargon->ID, 'jargon_full', true);
?>
<div class="jargon-term">
    <!--RSPL Task#96 - Remove MORE link if jargon-item have no further information-->
    <!--<span class="jargon-title"><?php /*echo $jargon->post_title; */?></span> - <span class="jargon-excerpt"><?php /*echo $excerpt */?></span> ... <a href="#" class="jargon-show-full">More</a>-->
    <span class="jargon-title"><?php echo $jargon->post_title; ?></span> - <span class="jargon-excerpt"><?php echo $excerpt ?></span>
    <?php if ( isset( $description ) && !empty( $description ) ) { ?>
        ... <a href="#" class="jargon-show-full">More</a>
        <p><?php echo $description; ?></p>
    <?php } ?>
</div>
<?php endforeach; ?>