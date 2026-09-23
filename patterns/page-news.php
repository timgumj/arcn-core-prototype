<?php

/**
 * Title: ARCN News Page
 * Slug: arcn-core-prototype/page-news
 * Categories: arcn-pages
 * Description: News page with featured image and a dynamic WordPress post list.
 * Inserter: yes
 */

?>

<!-- wp:group {"align":"full","className":"arcn-news-page","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull arcn-news-page">

  <!-- wp:group {"className":"arcn-news-feature","layout":{"type":"default"}} -->
  <div class="wp-block-group arcn-news-feature">

    <!-- wp:heading {"level":1,"className":"arcn-news-feature__title"} -->
    <h1 class="wp-block-heading arcn-news-feature__title">NEWS ABOUT ARC AND AUSTRIA</h1>
    <!-- /wp:heading -->

    <!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"arcn-news-feature__image"} -->
    <figure class="wp-block-image size-full arcn-news-feature__image">
      <img
        src="https://austrianrestoredcitizenship.net/wp-content/uploads/2026/08/IMG_9509-scaled_result-scaled.webp"
        alt="Café Prückel, Vienna" />
    </figure>
    <!-- /wp:image -->

    <!-- wp:paragraph {"className":"arcn-news-feature__caption"} -->
    <p class="arcn-news-feature__caption">Cafe Prückel, Vienna, was frequented by many ancestors of our 58c community. (Leslie Yarmo collection.)</p>
    <!-- /wp:paragraph -->

  </div>
  <!-- /wp:group -->


  <!-- wp:group {"className":"arcn-news-posts","layout":{"type":"default"}} -->
  <div class="wp-block-group arcn-news-posts">

    <!-- wp:query {"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"className":"arcn-news-query","layout":{"type":"default"}} -->
    <div class="wp-block-query arcn-news-query">

      <!-- wp:post-template {"className":"arcn-news-list","layout":{"type":"default"}} -->

      <!-- wp:group {"className":"arcn-news-card","layout":{"type":"default"}} -->
      <div class="wp-block-group arcn-news-card">

        <!-- wp:post-title {"isLink":true,"className":"arcn-news-card__title"} /-->

        <!-- wp:post-excerpt {"moreText":"READ MORE...","excerptLength":35,"className":"arcn-news-card__excerpt"} /-->

      </div>
      <!-- /wp:group -->

      <!-- /wp:post-template -->


      <!-- wp:query-pagination {"paginationArrow":"none","className":"arcn-news-pagination","layout":{"type":"flex","justifyContent":"left"}} -->

      <!-- wp:query-pagination-previous {"label":"Previous"} /-->

      <!-- wp:query-pagination-numbers /-->

      <!-- wp:query-pagination-next {"label":"Next"} /-->

      <!-- /wp:query-pagination -->

      <!-- wp:query-no-results -->

      <!-- wp:paragraph -->
      <p>No news posts have been published yet.</p>
      <!-- /wp:paragraph -->

      <!-- /wp:query-no-results -->

    </div>
    <!-- /wp:query -->

  </div>
  <!-- /wp:group -->

</div>
<!-- /wp:group -->