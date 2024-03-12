<div class="partners-container">
  <?php
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

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
      'posts_per_page' => 9,
      'order'          => 'ASC',
      'orderby'        => 'title',
      'ignore_custom_sort' => TRUE,
      'paged' => $paged,
      'meta_query'     => array(
        array(
          'key'   => '_cplat_inv_management_type',
          'value' => 'advised'
        )
      )
    );

    if($_GET['plat_name']) {
      $args['s'] = $_GET['plat_name'];
      $args['orderby'] = 'relevance';
      $args['order'] = 'DESC';
    };

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
      'posts_per_page' => 9,
      'order'          => 'ASC',
      'orderby'        => 'title',
      'ignore_custom_sort' => TRUE,
      'paged' => $paged,
      'meta_query'     => array(
        array(
          'key'   => '_cplat_inv_management_type',
          'value' => 'd2c'
        )
      )
    );

    if($_GET['plat_name']) {
      $args['s'] = $_GET['plat_name'];
      $args['orderby'] = 'relevance';
      $args['order'] = 'DESC';
    };

  } else {
      // FP UPDATED BY HARRY 03/11/22 Original: post_type=platform&posts_per_page=-1&order=ASC
    $args = 'post_type=platform&posts_per_page=-1&order=ASC&orderby=title&ignore_custom_sort=true';
  }
  $partners_query = new WP_Query( $args );
?>
<div class="container">
    <div class="row">
      <div class="d-block w-50 mb-5">
        <?php
        $platforms_plural_singular = 'platforms';
        if($partners_query->found_posts == 1) {
          $platforms_plural_singular = 'platform';  
        }
        if($_GET['plat_name']) {
          echo '<div class="d-inline-block">';
            echo '<p class="d-inline-block">' . $partners_query->found_posts . ' ' . $platforms_plural_singular . ' found for "' . $_GET['plat_name'] . '". <a href="/diy-platforms/" style="font-weight: 600;" class="font-weight-bold d-inline-block text-underline hover-underline-link">Clear search</a></p>';
          echo '</div>';
        };
        ?>
      </div>
      <div class="platforms-search__wrap">
        <form action="/diy-platforms/" method="GET" class="platforms-search__form mb-5">
          <div class="d-flex">
            <input type="text" name="plat_name" placeholder="Search for platforms..." />
            <input class="btn btn-dark-green" type="submit" value="Search" />
          </div>
        </form>
      </div>
          <?php
          while ( $partners_query->have_posts() ) : $partners_query->the_post();
          $_cplat_inv_type = get_post_meta( get_the_ID(), '_cplat_inv_management_type', true ); // advised OR d2c
          $p_type = ( $_cplat_inv_type == 'd2c' ? 'D2C' : 'ADV'  );
          $rating_service  = get_post_meta( get_the_ID(), '_cplat_rating_service', true );
          ?>

          <div class="col-lg-4 d-flex justify-content-center">
            <a href="<?php the_permalink(); ?>" data-vars-ga-category="<?php echo $p_type; ?> Platform Page" data-vars-ga-action="<?php the_permalink(); ?>" data-vars-ga-label="<?php the_title(); ?>">
            <div class="posts">
              <div class="post-image">
                <?php the_post_thumbnail(); ?>
                </div>
                <div class="post-card">
                  <div class="post-content platform-post-card-inner-wrap">
                    <div>
                      <h4 class="post-heading my-3"><?php the_title(); ?></h4>
                      <div>
                          <?php if(get_field('guide_excerpt')) { 
                              echo get_field('guide_excerpt');
                          } else {
                            echo '<p>';echo wp_trim_words( get_the_content(), 19, '...' );echo '</p>';
                          } ?>
                      </div>
                    </div>
                    <div>
                      <?php if($p_type !== "ADV") { ?>
                        <div class="rating-<?php echo $rating_service; ?>">
                          <p class="overall-rating-text">Overall rating</p>
                          <div class="rating-bullets">
                            <div class="bullet"></div>
                            <div class="bullet"></div>
                            <div class="bullet"></div>
                            <div class="bullet"></div>
                            <div class="bullet"></div>
                          </div>  
                        </div>
                      <?php } ?>
                      <div class="d-flex justify-content-space-between align-items-center">
                        <div class="chevron"><svg class="header-menu__submenu-column-link--arrow" width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.82197 0L18 8.03042L9.82197 16L8.28208 14.398L13.7341 9.1052L0 9.12547L0 6.95564L13.7341 6.95564L8.21965 1.54119L9.82197 0Z" fill="#404041"/></svg></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </a>
          </div>
          <?php endwhile; ?>

      </div>
      <div class="row">
        <div class="col-12">
          <div class="pagination d-block text-center">
          <?php 
            echo paginate_links( array(
                'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                'total'        => $partners_query->max_num_pages,
                'current'      => max( 1, get_query_var( 'paged' ) ),
                'format'       => '?paged=%#%',
                'show_all'     => false,
                'type'         => 'plain',
                'end_size'     => 2,
                'mid_size'     => 1,
                'prev_next'    => true,
                'prev_text'    => sprintf( '<i></i> %1$s', __( 'Newer Posts', 'text-domain' ) ),
                'next_text'    => sprintf( '%1$s <i></i>', __( 'Older Posts', 'text-domain' ) ),
                'add_args'     => false,
                'add_fragment' => '',
            ) );
          ?>
          </div>
        </div>
    <?php wp_reset_postdata(); ?>
  </div>
</div>