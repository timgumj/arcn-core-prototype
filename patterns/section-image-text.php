<?php
/**
 * Title: ARCN Image + Text
 * Slug: arcn-core-prototype/image-text
 * Categories: arcn-sections
 * Inserter: yes
 */
?>
<!-- wp:group {"align":"full","className":"arcn-section arcn-image-text","templateLock":"contentOnly","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull arcn-section arcn-image-text">
    <!-- wp:columns {"align":"wide","verticalAlignment":"center","className":"arcn-image-text__grid"} -->
    <div class="wp-block-columns alignwide are-vertically-aligned-center arcn-image-text__grid">
        <!-- wp:column {"verticalAlignment":"center"} -->
        <div class="wp-block-column is-vertically-aligned-center">
            <!-- wp:image {"sizeSlug":"large","className":"arcn-image-text__image"} -->
            <figure class="wp-block-image size-large arcn-image-text__image"><img alt=""/></figure>
            <!-- /wp:image -->
        </div>
        <!-- /wp:column -->
        <!-- wp:column {"verticalAlignment":"center"} -->
        <div class="wp-block-column is-vertically-aligned-center">
            <!-- wp:heading {"level":2} -->
            <h2 class="wp-block-heading">Add section heading</h2>
            <!-- /wp:heading -->
            <!-- wp:paragraph -->
            <p>Add section text.</p>
            <!-- /wp:paragraph -->
            <!-- wp:buttons -->
            <div class="wp-block-buttons">
                <!-- wp:button -->
                <div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Add link</a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->
