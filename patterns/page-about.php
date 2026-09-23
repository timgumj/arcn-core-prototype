<?php

/**
 * Title: ARCN About Page
 * Slug: arcn-core-prototype/page-about
 * Categories: arcn-pages
 * Description: About page using the standard ARCN hero and homepage introduction section.
 * Inserter: yes
 */

?>

<!-- wp:group {"align":"full","className":"arcn-standard-page arcn-about-page","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull arcn-standard-page arcn-about-page">

  <!-- wp:group {"align":"wide","className":"arcn-page-intro","lock":{"move":true,"remove":true},"layout":{"type":"default"}} -->
  <div class="wp-block-group alignwide arcn-page-intro">

    <!-- wp:columns {"className":"arcn-page-intro__columns","lock":{"move":true,"remove":true}} -->
    <div class="wp-block-columns arcn-page-intro__columns">

      <!-- wp:column {"width":"30%","className":"arcn-page-intro__copy","lock":{"move":true,"remove":true}} -->
      <div class="wp-block-column arcn-page-intro__copy" style="flex-basis:30%">

        <!-- wp:heading {"level":1,"className":"arcn-page-title","lock":{"move":true,"remove":true}} -->
        <h1 class="wp-block-heading arcn-page-title">About ARCN</h1>
        <!-- /wp:heading -->

        <!-- wp:paragraph {"className":"arcn-page-intro__text","lock":{"move":true,"remove":true}} -->
        <p class="arcn-page-intro__text">Add the introductory text for the About page here.</p>
        <!-- /wp:paragraph -->

      </div>
      <!-- /wp:column -->

      <!-- wp:column {"width":"70%","className":"arcn-page-intro__media","lock":{"move":true,"remove":true}} -->
      <div class="wp-block-column arcn-page-intro__media" style="flex-basis:70%">

        <!-- wp:group {"className":"arcn-hero-image-slot","layout":{"type":"default"}} -->
        <div class="wp-block-group arcn-hero-image-slot"></div>
        <!-- /wp:group -->

        <!-- wp:paragraph {"className":"arcn-image-caption"} -->
        <p class="arcn-image-caption">Add image caption or photo credit.</p>
        <!-- /wp:paragraph -->

      </div>
      <!-- /wp:column -->

    </div>
    <!-- /wp:columns -->

  </div>
  <!-- /wp:group -->


  <!-- wp:group {"className":"arcn-home-welcome arcn-about-content","layout":{"type":"constrained"}} -->
  <div class="wp-block-group arcn-home-welcome arcn-about-content">

    <!-- wp:group {"className":"arcn-home-welcome__inner arcn-about-content__inner","layout":{"type":"constrained"}} -->
    <div class="wp-block-group arcn-home-welcome__inner arcn-about-content__inner">

      <!-- wp:heading {"level":2,"className":"arcn-home-welcome__title"} -->
      <h2 class="wp-block-heading arcn-home-welcome__title">About the <strong>Austrian Restored Citizenship Network</strong></h2>
      <!-- /wp:heading -->

      <!-- wp:paragraph -->
      <p>Add the About page content here.</p>
      <!-- /wp:paragraph -->

      <!-- wp:paragraph -->
      <p>Add another paragraph here.</p>
      <!-- /wp:paragraph -->

    </div>
    <!-- /wp:group -->

  </div>
  <!-- /wp:group -->

</div>
<!-- /wp:group -->