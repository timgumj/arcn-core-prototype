<?php

/**
 * Title: ARCN Contact Page
 * Slug: arcn-core-prototype/page-contact
 * Categories: arcn-pages
 * Description: Contact page with image, newsletter information and Contact Form 7 form.
 * Inserter: yes
 */

?>

<!-- wp:group {"align":"full","className":"arcn-contact-page","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull arcn-contact-page">

  <!-- wp:columns {"isStackedOnMobile":true,"className":"arcn-contact-page__columns"} -->
  <div class="wp-block-columns arcn-contact-page__columns">

    <!-- wp:column {"width":"33.333%","className":"arcn-contact-page__left"} -->
    <div class="wp-block-column arcn-contact-page__left" style="flex-basis:33.333%">

      <!-- wp:group {"className":"arcn-contact-page__image-panel","layout":{"type":"default"}} -->
      <div class="wp-block-group arcn-contact-page__image-panel">

        <!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"arcn-contact-page__image"} -->
        <figure class="wp-block-image size-full arcn-contact-page__image"><img src="https://austrianrestoredcitizenship.net/wp-content/uploads/2026/08/Albert-at-desk.-colorized_result.webp" alt="Albert Steiner, from a prestigious family and an inspector of steamships of the Danube, fled Vienna in 1940 and could only find work in the US as a security guard." /></figure>
        <!-- /wp:image -->

        <!-- wp:paragraph {"className":"arcn-contact-page__caption"} -->
        <p class="arcn-contact-page__caption">In Vienna, Albert Steiner had a respected position as the inspector of the steamships on the Danube. He managed to escape to the US in 1940, but never found work beyond that of a security guard. His great- and great-great-grandchildren now have Austrian restored citizenship (<em>Leslie Yarmo collection</em>)</p>
        <!-- /wp:paragraph -->

      </div>
      <!-- /wp:group -->


      <!-- wp:group {"className":"arcn-contact-page__newsletter","layout":{"type":"default"}} -->
      <div class="wp-block-group arcn-contact-page__newsletter">

        <!-- wp:paragraph {"className":"arcn-contact-page__newsletter-link"} -->
        <p class="arcn-contact-page__newsletter-link"><a href="https://97c6c9f6.sibforms.com/serve/MUIFAHeSOXy5eyNLHBplgAG3k7uZSVrXd9GZjPSIzIZcW9ALKqxm6IcfC1z36IaJYAQDkDDEb7adn-VBRemAQSqAF-OlhZkB0jhg8iDiUZcsm24_qHEvAEu2rNLl3P_9ruvGYnSz_8c8CdD9IM9ds5fcaXunqR73otBHfmfv1LxJIpnWPI3M5VouYUiPZVCkuCWSHLo4kwx7LbvsRA==" target="_blank" rel="noreferrer noopener">SUBSCRIBE TO NEWSLETTER ↗</a></p>
        <!-- /wp:paragraph -->

        <!-- wp:paragraph {"className":"arcn-contact-page__newsletter-text"} -->
        <p class="arcn-contact-page__newsletter-text">Sign up today to learn more about ARC Network and our upcoming events.</p>
        <!-- /wp:paragraph -->

      </div>
      <!-- /wp:group -->

    </div>
    <!-- /wp:column -->


    <!-- wp:column {"width":"66.667%","className":"arcn-contact-page__right"} -->
    <div class="wp-block-column arcn-contact-page__right" style="flex-basis:66.667%">

      <!-- wp:heading {"level":1,"className":"arcn-contact-page__title"} -->
      <h1 class="wp-block-heading arcn-contact-page__title">CONTACT US</h1>
      <!-- /wp:heading -->

      <!-- wp:paragraph {"className":"arcn-contact-page__intro"} -->
      <p class="arcn-contact-page__intro">We love to hear from you. Whether you have a question, comment, or feedback, don't hesitate to reach out.</p>
      <!-- /wp:paragraph -->


      <!-- wp:group {"className":"arcn-contact-form","layout":{"type":"default"}} -->
      <div class="wp-block-group arcn-contact-form">

        <!-- wp:shortcode -->
        [contact-form-7 id="177"]
        <!-- /wp:shortcode -->

      </div>
      <!-- /wp:group -->

    </div>
    <!-- /wp:column -->

  </div>
  <!-- /wp:columns -->

</div>
<!-- /wp:group -->