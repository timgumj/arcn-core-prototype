<?php

/**
 * ARCN Hero Slider
 *
 * Provides:
 *
 * 1. Native Gutenberg block:
 *    arcn/hero-slider
 *
 * 2. Backwards-compatible shortcode:
 *    [arcn_hero_slider]
 *
 * The Gutenberg editor only exposes:
 *
 * - Image URL
 * - Image source
 *
 * for four slides.
 */

if (! defined('ABSPATH')) {
  exit;
}


/* =========================================================
   ATTACHMENT HELPERS
   ========================================================= */

/**
 * Try to find a Media Library attachment ID
 * from an image URL.
 *
 * @param string $image_url Image URL.
 * @return int
 */
function arcn_core_slider_get_attachment_id($image_url)
{

  $image_url =
    trim(
      (string) $image_url
    );


  if ('' === $image_url) {
    return 0;
  }


  /*
	 * Remove query strings and fragments.
	 */
  $clean_url =
    preg_replace(
      '/[?#].*$/',
      '',
      $image_url
    );


  if (
    ! is_string($clean_url) ||
    '' === $clean_url
  ) {
    return 0;
  }


  /*
	 * Direct attachment lookup.
	 */
  $attachment_id =
    attachment_url_to_postid(
      $clean_url
    );


  if ($attachment_id) {

    return absint(
      $attachment_id
    );
  }


  /*
	 * WordPress may return a generated image size URL
	 * such as:
	 *
	 * image-1024x768.webp
	 *
	 * Try the original file URL as well.
	 */
  $original_url =
    preg_replace(
      '/-\d+x\d+(?=\.(?:jpe?g|png|gif|webp|avif)$)/i',
      '',
      $clean_url
    );


  if (
    is_string($original_url) &&
    $original_url !== $clean_url
  ) {

    $attachment_id =
      attachment_url_to_postid(
        $original_url
      );
  }


  return absint(
    $attachment_id
  );
}


/**
 * Automatically determine slider image alt text.
 *
 * Editors do not need a separate alt-text field
 * inside the slider block.
 *
 * Priority:
 *
 * 1. WordPress Media Library alt text
 * 2. Slide source text
 *
 * @param string $image_url Image URL.
 * @param string $source    Slide source.
 * @return string
 */
function arcn_core_slider_get_alt_text(
  $image_url,
  $source = ''
) {

  $attachment_id =
    arcn_core_slider_get_attachment_id(
      $image_url
    );


  if ($attachment_id) {

    $attachment_alt =
      get_post_meta(
        $attachment_id,
        '_wp_attachment_image_alt',
        true
      );


    $attachment_alt =
      trim(
        wp_strip_all_tags(
          (string) $attachment_alt
        )
      );


    if ('' !== $attachment_alt) {
      return $attachment_alt;
    }
  }


  /*
	 * Fallback to source text.
	 */
  return sanitize_text_field(
    (string) $source
  );
}


/* =========================================================
   IMAGE MARKUP
   ========================================================= */

/**
 * Main slide image.
 *
 * @param string $image_url      Image URL.
 * @param string $alt_text       Alt text.
 * @param bool   $is_first_slide Whether this is the first slide.
 * @return string
 */
function arcn_core_slider_main_image_markup(
  $image_url,
  $alt_text,
  $is_first_slide = false
) {

  $image_url =
    esc_url_raw(
      trim(
        (string) $image_url
      )
    );


  $alt_text =
    sanitize_text_field(
      (string) $alt_text
    );


  if ('' === $image_url) {
    return '';
  }


  $attachment_id =
    arcn_core_slider_get_attachment_id(
      $image_url
    );


  $image_attributes =
    array(
      'class' =>
      'arcn-slider-image',

      'alt' =>
      $alt_text,

      'decoding' =>
      'async',

      'draggable' =>
      'false',

      'sizes' =>
      '100vw',
    );


  if ($is_first_slide) {

    $image_attributes['loading'] =
      'eager';

    $image_attributes['fetchpriority'] =
      'high';
  } else {

    $image_attributes['loading'] =
      'lazy';

    $image_attributes['fetchpriority'] =
      'low';
  }


  if ($attachment_id) {

    return wp_get_attachment_image(
      $attachment_id,
      'full',
      false,
      $image_attributes
    );
  }


  return sprintf(
    '<img src="%1$s" alt="%2$s" class="arcn-slider-image" loading="%3$s" fetchpriority="%4$s" decoding="async" draggable="false" sizes="100vw">',
    esc_url(
      $image_url
    ),
    esc_attr(
      $alt_text
    ),
    $is_first_slide
      ? 'eager'
      : 'lazy',
    $is_first_slide
      ? 'high'
      : 'low'
  );
}


/**
 * Slider thumbnail image.
 *
 * @param string $image_url Image URL.
 * @return string
 */
function arcn_core_slider_thumbnail_markup(
  $image_url
) {

  $image_url =
    esc_url_raw(
      trim(
        (string) $image_url
      )
    );


  if ('' === $image_url) {
    return '';
  }


  $attachment_id =
    arcn_core_slider_get_attachment_id(
      $image_url
    );


  $image_attributes =
    array(
      'class' =>
      'arcn-slider-thumbnail-image',

      'alt' =>
      '',

      'aria-hidden' =>
      'true',

      'loading' =>
      'lazy',

      'fetchpriority' =>
      'low',

      'decoding' =>
      'async',

      'draggable' =>
      'false',

      'sizes' =>
      '110px',
    );


  if ($attachment_id) {

    return wp_get_attachment_image(
      $attachment_id,
      'medium',
      false,
      $image_attributes
    );
  }


  return sprintf(
    '<img src="%1$s" alt="" class="arcn-slider-thumbnail-image" aria-hidden="true" loading="lazy" fetchpriority="low" decoding="async" draggable="false">',
    esc_url(
      $image_url
    )
  );
}


/* =========================================================
   SLIDER RENDERER
   ========================================================= */

/**
 * Render complete hero slider markup.
 *
 * Both the Gutenberg block and the legacy shortcode
 * use this same frontend renderer.
 *
 * @param array $slides Slide data.
 * @param int   $delay  Autoplay delay.
 * @return string
 */
function arcn_core_render_hero_slider(
  $slides,
  $delay = 5500
) {

  if (
    empty($slides) ||
    ! is_array($slides)
  ) {
    return '';
  }


  $clean_slides =
    array();


  foreach (
    $slides as $slide
  ) {

    if (
      ! is_array($slide)
    ) {
      continue;
    }


    $image =
      isset(
        $slide['image']
      )
      ? esc_url_raw(
        trim(
          (string)
          $slide['image']
        )
      )
      : '';


    if ('' === $image) {
      continue;
    }


    $source =
      isset(
        $slide['source']
      )
      ? sanitize_text_field(
        (string)
        $slide['source']
      )
      : '';


    $alt =
      isset(
        $slide['alt']
      ) &&
      '' !== trim(
        (string)
        $slide['alt']
      )
      ? sanitize_text_field(
        (string)
        $slide['alt']
      )
      : arcn_core_slider_get_alt_text(
        $image,
        $source
      );


    $clean_slides[] =
      array(
        'image' =>
        $image,

        'source' =>
        $source,

        'alt' =>
        $alt,
      );
  }


  if (
    empty($clean_slides)
  ) {
    return '';
  }


  $slide_count =
    count(
      $clean_slides
    );


  $autoplay_delay =
    max(
      3000,
      absint(
        $delay
      )
    );


  $slider_id =
    wp_unique_id(
      'arcn-slider-'
    );


  ob_start();
?>

  <div
    id="<?php echo esc_attr($slider_id); ?>"
    class="arcn-hero-slider"
    role="region"
    aria-label="Historical image slideshow"
    aria-roledescription="carousel"
    tabindex="0"
    data-autoplay-delay="<?php echo esc_attr($autoplay_delay); ?>">

    <div class="arcn-slider-stage">

      <?php
      foreach (
        $clean_slides
        as
        $index =>
        $slide
      ) :
      ?>

        <figure
          id="<?php echo esc_attr(
                $slider_id .
                  '-slide-' .
                  (
                    $index +
                    1
                  )
              ); ?>"
          class="arcn-slider-slide<?php echo 0 === $index ? ' is-active' : ''; ?>"
          aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>"
          aria-label="<?php echo esc_attr(
                        sprintf(
                          'Slide %1$d of %2$d',
                          $index + 1,
                          $slide_count
                        )
                      ); ?>"
          data-source="<?php echo esc_attr(
                          $slide['source']
                        ); ?>">

          <div class="arcn-slider-image-area">

            <?php
            echo arcn_core_slider_main_image_markup(
              $slide['image'],
              $slide['alt'],
              0 === $index
            );
            ?>

          </div>

        </figure>

      <?php endforeach; ?>


      <?php if ($slide_count > 1) : ?>

        <div class="arcn-slider-overlay">

          <div class="arcn-slider-source-panel">

            <p
              class="arcn-slider-source-text"
              aria-live="off">
              <?php
              echo esc_html(
                $clean_slides[0]['source']
              );
              ?>
            </p>


            <div class="arcn-slider-meta-row">

              <button
                type="button"
                class="arcn-slider-icon-button arcn-slider-prev"
                aria-label="Previous slide">

                <svg
                  viewBox="0 0 24 24"
                  aria-hidden="true"
                  focusable="false">
                  <path d="M15.5 4.5L8.5 12l7 7.5"></path>
                </svg>

              </button>


              <span
                class="arcn-slider-counter"
                aria-live="off">

                <span class="arcn-slider-current">
                  01
                </span>

                <span aria-hidden="true">
                  /
                </span>

                <span class="arcn-slider-total">
                  <?php
                  echo esc_html(
                    str_pad(
                      (string)
                      $slide_count,
                      2,
                      '0',
                      STR_PAD_LEFT
                    )
                  );
                  ?>
                </span>

              </span>


              <div
                class="arcn-slider-progress"
                aria-hidden="true">
                <span class="arcn-slider-progress-bar"></span>
              </div>


              <button
                type="button"
                class="arcn-slider-icon-button arcn-slider-toggle"
                aria-label="Pause slideshow"
                aria-pressed="false">

                <svg
                  viewBox="0 0 24 24"
                  aria-hidden="true"
                  focusable="false">

                  <g class="arcn-slider-pause-bars">
                    <path d="M9 6.5V17.5"></path>
                    <path d="M15 6.5V17.5"></path>
                  </g>

                  <path
                    class="arcn-slider-play-shape"
                    d="M9 6.5L17 12L9 17.5Z"></path>

                </svg>

              </button>


              <button
                type="button"
                class="arcn-slider-icon-button arcn-slider-next"
                aria-label="Next slide">

                <svg
                  viewBox="0 0 24 24"
                  aria-hidden="true"
                  focusable="false">
                  <path d="M8.5 4.5L15.5 12l-7 7.5"></path>
                </svg>

              </button>

            </div>

          </div>


          <ul
            class="arcn-slider-thumbnails"
            aria-label="Choose an image">

            <?php
            foreach (
              $clean_slides
              as
              $index =>
              $slide
            ) :
            ?>

              <li class="arcn-slider-thumbnail-item">

                <button
                  type="button"
                  class="arcn-slider-thumbnail<?php echo 0 === $index ? ' is-active' : ''; ?>"
                  aria-label="<?php echo esc_attr(
                                sprintf(
                                  'Show image %d',
                                  $index + 1
                                )
                              ); ?>"
                  aria-controls="<?php echo esc_attr(
                                    $slider_id .
                                      '-slide-' .
                                      (
                                        $index +
                                        1
                                      )
                                  ); ?>"
                  aria-current="<?php echo 0 === $index ? 'true' : 'false'; ?>"
                  data-slide="<?php echo esc_attr($index); ?>">

                  <?php
                  echo arcn_core_slider_thumbnail_markup(
                    $slide['image']
                  );
                  ?>

                </button>

              </li>

            <?php endforeach; ?>

          </ul>

        </div>

      <?php endif; ?>

    </div>

  </div>

<?php

  return ob_get_clean();
}


/* =========================================================
   GUTENBERG BLOCK
   ========================================================= */

/**
 * Render callback for the native Gutenberg slider block.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function arcn_core_render_hero_slider_block(
  $attributes
) {

  $slides =
    array();


  for (
    $number = 1;
    $number <= 4;
    $number++
  ) {

    $image_key =
      'image' .
      $number;


    $source_key =
      'source' .
      $number;


    $image =
      isset(
        $attributes[$image_key]
      )
      ? $attributes[$image_key]
      : '';


    $source =
      isset(
        $attributes[$source_key]
      )
      ? $attributes[$source_key]
      : '';


    if (
      '' === trim(
        (string) $image
      )
    ) {
      continue;
    }


    $slides[] =
      array(
        'image' =>
        $image,

        'source' =>
        $source,
      );
  }


  /*
	 * Slider timing intentionally stays fixed.
	 *
	 * The client does not need to manage this.
	 */
  return arcn_core_render_hero_slider(
    $slides,
    5500
  );
}


/**
 * Register editor JavaScript and Gutenberg block.
 */
function arcn_core_register_hero_slider_block()
{

  $editor_script_path =
    get_theme_file_path(
      '/assets/css/js/hero-slider-editor.js'
    );


  if (
    ! file_exists(
      $editor_script_path
    )
  ) {
    return;
  }


  wp_register_script(
    'arcn-hero-slider-editor',
    get_theme_file_uri(
      '/assets/css/js/hero-slider-editor.js'
    ),
    array(
      'wp-blocks',
      'wp-element',
      'wp-components',
      'wp-block-editor',
      'wp-dom-ready',
      'wp-i18n',
    ),
    filemtime(
      $editor_script_path
    ),
    true
  );


  register_block_type(
    'arcn/hero-slider',
    array(

      'api_version' =>
      3,

      'editor_script' =>
      'arcn-hero-slider-editor',

      'attributes' =>
      array(

        'image1' =>
        array(
          'type' =>
          'string',

          'default' =>
          '',
        ),

        'source1' =>
        array(
          'type' =>
          'string',

          'default' =>
          '',
        ),

        'image2' =>
        array(
          'type' =>
          'string',

          'default' =>
          '',
        ),

        'source2' =>
        array(
          'type' =>
          'string',

          'default' =>
          '',
        ),

        'image3' =>
        array(
          'type' =>
          'string',

          'default' =>
          '',
        ),

        'source3' =>
        array(
          'type' =>
          'string',

          'default' =>
          '',
        ),

        'image4' =>
        array(
          'type' =>
          'string',

          'default' =>
          '',
        ),

        'source4' =>
        array(
          'type' =>
          'string',

          'default' =>
          '',
        ),
      ),

      'render_callback' =>
      'arcn_core_render_hero_slider_block',
    )
  );
}

add_action(
  'init',
  'arcn_core_register_hero_slider_block',
  20
);


/* =========================================================
   LEGACY SHORTCODE
   ========================================================= */

/**
 * Keep shortcode support while the site is migrated
 * away from the previous Divi implementation.
 *
 * @param array|string $atts Shortcode attributes.
 * @return string
 */
function arcn_core_hero_slider_shortcode(
  $atts = array()
) {

  $atts =
    shortcode_atts(
      array(

        'image1' =>
        '',

        'source1' =>
        '',

        'alt1' =>
        '',

        'image2' =>
        '',

        'source2' =>
        '',

        'alt2' =>
        '',

        'image3' =>
        '',

        'source3' =>
        '',

        'alt3' =>
        '',

        'image4' =>
        '',

        'source4' =>
        '',

        'alt4' =>
        '',

        'delay' =>
        5500,
      ),
      $atts,
      'arcn_hero_slider'
    );


  $slides =
    array();


  for (
    $number = 1;
    $number <= 4;
    $number++
  ) {

    $image_key =
      'image' .
      $number;


    $source_key =
      'source' .
      $number;


    $alt_key =
      'alt' .
      $number;


    $image_url =
      esc_url_raw(
        trim(
          (string)
          $atts[$image_key]
        )
      );


    if (
      '' === $image_url
    ) {
      continue;
    }


    $slides[] =
      array(

        'image' =>
        $image_url,

        'source' =>
        sanitize_text_field(
          (string)
          $atts[$source_key]
        ),

        'alt' =>
        sanitize_text_field(
          (string)
          $atts[$alt_key]
        ),
      );
  }


  return arcn_core_render_hero_slider(
    $slides,
    $atts['delay']
  );
}


/**
 * Register legacy shortcode.
 */
function arcn_core_register_hero_slider_shortcode()
{

  remove_shortcode(
    'arcn_hero_slider'
  );


  add_shortcode(
    'arcn_hero_slider',
    'arcn_core_hero_slider_shortcode'
  );
}

add_action(
  'init',
  'arcn_core_register_hero_slider_shortcode',
  99
);
