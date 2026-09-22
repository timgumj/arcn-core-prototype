<?php

/**
 * ARCN Event List
 *
 * Provides:
 * [arcn_event_list]
 *
 * Requires The Events Calendar.
 *
 * Examples:
 *
 * [arcn_event_list]
 * [arcn_event_list limit="6"]
 * [arcn_event_list category="exhibitions"]
 * [arcn_event_list location_field="Event Location"]
 * [arcn_event_list show_address="yes"]
 */

if (! defined('ABSPATH')) {
  exit;
}


/**
 * Render the ARCN event list.
 *
 * @param array|string $atts Shortcode attributes.
 * @return string
 */
function arcn_core_event_list_shortcode($atts = array())
{

  /*
	 * Stop if The Events Calendar is not active.
	 */
  if (! function_exists('tribe_get_events')) {

    return current_user_can('manage_options')
      ? '<p><strong>The Events Calendar must be activated to display the ARCN event list.</strong></p>'
      : '';
  }


  $atts = shortcode_atts(
    array(
      'limit'             => '6',
      'category'          => '',
      'date_format'       => 'D, j.n.Y',
      'time_format'       => 'H.i',
      'time_suffix'       => 'UHR',
      'all_day_text'      => 'ALL DAY',
      'show_end_time'     => 'no',
      'show_address'      => 'no',
      'location_field'    => '',
      'location_fallback' => 'LOCATION TO BE ANNOUNCED',
      'no_events_text'    => 'NO UPCOMING EVENTS',
      'aria_label'        => 'Upcoming ARCN events',
    ),
    $atts,
    'arcn_event_list'
  );


  /*
	 * Number of events.
	 */
  $limit = absint(
    $atts['limit']
  );

  if ($limit < 1) {
    $limit = 6;
  }


  /*
	 * Boolean options.
	 */
  $show_end_time = in_array(
    strtolower(
      trim(
        (string) $atts['show_end_time']
      )
    ),
    array(
      'yes',
      'true',
      '1',
      'on',
    ),
    true
  );

  $show_address = in_array(
    strtolower(
      trim(
        (string) $atts['show_address']
      )
    ),
    array(
      'yes',
      'true',
      '1',
      'on',
    ),
    true
  );


  /*
	 * Query upcoming events.
	 */
  $query_args = array(
    'posts_per_page' => $limit,
    'ends_after'     => 'now',
    'eventDisplay'   => 'list',
    'orderby'        => 'event_date',
    'order'          => 'ASC',
    'post_status'    => 'publish',
  );


  /*
	 * Optional category filtering.
	 *
	 * Examples:
	 *
	 * [arcn_event_list category="exhibitions"]
	 *
	 * [arcn_event_list category="exhibitions,community"]
	 */
  if (! empty($atts['category'])) {

    $categories = array_filter(
      array_map(
        'sanitize_title',
        explode(
          ',',
          (string) $atts['category']
        )
      )
    );

    if (! empty($categories)) {

      $query_args['tax_query'] = array(
        array(
          'taxonomy' => 'tribe_events_cat',
          'field'    => 'slug',
          'terms'    => $categories,
        ),
      );
    }
  }


  $events = tribe_get_events(
    $query_args
  );


  /*
	 * Find an Events Calendar Pro custom field
	 * by its visible field label.
	 */
  $get_custom_field_value = static function (
    $fields,
    $wanted_label
  ) {

    $wanted_label = trim(
      wp_strip_all_tags(
        (string) $wanted_label
      )
    );

    if (
      empty($wanted_label) ||
      empty($fields) ||
      ! is_array($fields)
    ) {
      return '';
    }


    foreach (
      $fields as $label => $value
    ) {

      $clean_label = trim(
        wp_strip_all_tags(
          (string) $label
        )
      );

      if (
        0 === strcasecmp(
          $clean_label,
          $wanted_label
        )
      ) {

        return trim(
          wp_strip_all_tags(
            (string) $value
          )
        );
      }
    }


    return '';
  };


  ob_start();
?>

  <section
    class="arcn-events"
    aria-label="<?php echo esc_attr($atts['aria_label']); ?>">

    <div class="arcn-events__list">

      <?php if (empty($events)) : ?>

        <p
          class="arcn-events__empty"
          role="status">
          <?php
          echo esc_html(
            $atts['no_events_text']
          );
          ?>
        </p>

      <?php else : ?>

        <?php foreach ($events as $event) : ?>

          <?php

          $event_id =
            $event->ID;

          $event_title =
            get_the_title(
              $event_id
            );

          /*
					 * Event URL.
					 */
          $event_url =
            function_exists(
              'tribe_get_event_link'
            )
            ? tribe_get_event_link(
              $event_id
            )
            : get_permalink(
              $event_id
            );


          /*
					 * Visible event start date.
					 */
          $start_date =
            tribe_get_start_date(
              $event_id,
              false,
              sanitize_text_field(
                $atts['date_format']
              )
            );


          /*
					 * Visible event end date.
					 */
          $end_date =
            function_exists(
              'tribe_get_end_date'
            )
            ? tribe_get_end_date(
              $event_id,
              false,
              sanitize_text_field(
                $atts['date_format']
              )
            )
            : '';


          /*
					 * Machine-readable start date.
					 */
          $start_date_iso =
            tribe_get_start_date(
              $event_id,
              true,
              'c'
            );


          /*
					 * Machine-readable end date.
					 */
          $end_date_iso =
            function_exists(
              'tribe_get_end_date'
            )
            ? tribe_get_end_date(
              $event_id,
              true,
              'c'
            )
            : '';


          $is_multiday =
            function_exists(
              'tribe_event_is_multiday'
            )
            && tribe_event_is_multiday(
              $event_id
            );


          $is_all_day =
            function_exists(
              'tribe_event_is_all_day'
            )
            && tribe_event_is_all_day(
              $event_id
            );


          /*
					 * Begin visible date text.
					 */
          $date_text =
            $start_date;


          /*
					 * Multi-day date range.
					 */
          if (
            $is_multiday &&
            ! empty($end_date) &&
            $end_date !== $start_date
          ) {

            $date_text .=
              ' – ' .
              $end_date;
          }


          /*
					 * Event time.
					 */
          if ($is_all_day) {

            $date_text .=
              ' - ' .
              $atts['all_day_text'];
          } else {

            $start_time =
              tribe_get_start_date(
                $event_id,
                true,
                sanitize_text_field(
                  $atts['time_format']
                )
              );


            $end_time =
              function_exists(
                'tribe_get_end_time'
              )
              ? tribe_get_end_time(
                $event_id,
                sanitize_text_field(
                  $atts['time_format']
                )
              )
              : '';


            if (
              ! empty($start_time)
            ) {

              $date_text .=
                ' - ' .
                $start_time;
            }


            if (
              $show_end_time &&
              ! empty($end_time) &&
              $end_time !== $start_time
            ) {

              $date_text .=
                '–' .
                $end_time;
            }


            if (
              ! empty($atts['time_suffix'])
            ) {

              $date_text .=
                ' ' .
                $atts['time_suffix'];
            }
          }


          /*
					 * Events Calendar Pro custom fields.
					 */
          $custom_fields =
            function_exists(
              'tribe_get_custom_fields'
            )
            ? tribe_get_custom_fields(
              $event_id
            )
            : array();


          $location = '';


          /*
					 * Explicit custom location field.
					 */
          if (
            ! empty($atts['location_field'])
          ) {

            $location =
              $get_custom_field_value(
                $custom_fields,
                $atts['location_field']
              );
          }


          /*
					 * Automatically check common custom
					 * location field names.
					 */
          if (
            empty($location)
          ) {

            $automatic_location_fields =
              array(
                'Event Location',
                'Location',
              );


            foreach (
              $automatic_location_fields
              as $field_label
            ) {

              $location =
                $get_custom_field_value(
                  $custom_fields,
                  $field_label
                );


              if (
                ! empty($location)
              ) {
                break;
              }
            }
          }


          /*
					 * Standard Events Calendar venue.
					 */
          $venue =
            function_exists(
              'tribe_get_venue'
            )
            ? trim(
              wp_strip_all_tags(
                tribe_get_venue(
                  $event_id
                )
              )
            )
            : '';


          /*
					 * Standard Events Calendar address.
					 */
          $address =
            function_exists(
              'tribe_get_full_address'
            )
            ? trim(
              preg_replace(
                '/\s+/',
                ' ',
                wp_strip_all_tags(
                  tribe_get_full_address(
                    $event_id,
                    false
                  )
                )
              )
            )
            : '';


          /*
					 * Fall back to venue.
					 */
          if (
            empty($location) &&
            ! empty($venue)
          ) {

            $location =
              $venue;
          }


          /*
					 * Optionally append the address.
					 */
          if (
            $show_address &&
            ! empty($address)
          ) {

            if (
              ! empty($location)
            ) {

              $location .=
                ' · ' .
                $address;
            } else {

              $location =
                $address;
            }
          }


          /*
					 * Final location fallback.
					 */
          if (
            empty($location)
          ) {

            $location =
              $atts['location_fallback'];
          }


          $event_title_id =
            'arcn-event-title-' .
            absint(
              $event_id
            );

          ?>

          <article
            class="arcn-events__item"
            itemscope
            itemtype="https://schema.org/Event"
            aria-labelledby="<?php echo esc_attr($event_title_id); ?>">

            <div class="arcn-events__name">

              <h3
                id="<?php echo esc_attr($event_title_id); ?>"
                class="arcn-events__title"
                itemprop="name">

                <a
                  class="arcn-events__title-link"
                  href="<?php echo esc_url($event_url); ?>"
                  itemprop="url">
                  <?php
                  echo esc_html(
                    $event_title
                  );
                  ?>
                </a>

              </h3>

            </div>


            <div class="arcn-events__schedule-wrap">

              <p class="arcn-events__schedule">

                <time
                  datetime="<?php echo esc_attr($start_date_iso); ?>"
                  itemprop="startDate">
                  <?php
                  echo esc_html(
                    $date_text
                  );
                  ?>
                </time>


                <?php if (! empty($end_date_iso)) : ?>

                  <meta
                    itemprop="endDate"
                    content="<?php echo esc_attr($end_date_iso); ?>">

                <?php endif; ?>

              </p>

            </div>


            <div class="arcn-events__location-wrap">

              <p
                class="arcn-events__location"
                itemprop="location">
                <?php
                echo esc_html(
                  $location
                );
                ?>
              </p>

            </div>

          </article>

        <?php endforeach; ?>

      <?php endif; ?>

    </div>

  </section>

<?php

  wp_reset_postdata();

  return ob_get_clean();
}


/**
 * Register the ARCN event-list shortcode.
 *
 * Priority 99 replaces the previous
 * Code Snippets registration while we test.
 */
function arcn_core_register_event_list_shortcode()
{

  remove_shortcode(
    'arcn_event_list'
  );

  add_shortcode(
    'arcn_event_list',
    'arcn_core_event_list_shortcode'
  );
}


add_action(
  'init',
  'arcn_core_register_event_list_shortcode',
  99
);
