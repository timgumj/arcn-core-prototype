<?php

if (! defined('ABSPATH')) {
    exit;
}


/* =========================================================
   HOMEPAGE COMPONENTS
   ========================================================= */

$hero_slider_path =
    get_theme_file_path(
        '/assets/css/inc/hero-slider.php'
    );

if (
    file_exists(
        $hero_slider_path
    )
) {
    require_once $hero_slider_path;
}


$event_list_path =
    get_theme_file_path(
        '/inc/event-list.php'
    );

if (
    file_exists(
        $event_list_path
    )
) {
    require_once $event_list_path;
}


/* =========================================================
   THEME SETUP
   ========================================================= */

function arcn_core_prototype_setup()
{

    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');

    add_editor_style(
        array(
            'assets/css/main.css',
            'assets/css/header.css',
            'assets/css/page.css',
            'assets/css/footer.css',

            /*
             * Homepage editor styles.
             */
            'assets/css/home.css',
            'assets/css/hero-slider.css',
            'assets/css/event-list.css',

            'assets/css/editor.css',
        )
    );
}

add_action(
    'after_setup_theme',
    'arcn_core_prototype_setup'
);


/* =========================================================
   HOMEPAGE PATTERN FALLBACK REGISTRATION
   ========================================================= */

function arcn_core_register_homepage_pattern()
{

    $pattern_name =
        'arcn-core-prototype/page-home';


    /*
     * WordPress normally discovers patterns inside
     * the /patterns folder automatically.
     *
     * If it has already registered this pattern,
     * do nothing.
     */
    if (
        class_exists(
            'WP_Block_Patterns_Registry'
        )
    ) {

        $registry =
            WP_Block_Patterns_Registry::get_instance();


        if (
            $registry->is_registered(
                $pattern_name
            )
        ) {
            return;
        }
    }


    /*
     * Homepage pattern file.
     */
    $pattern_file =
        get_theme_file_path(
            '/patterns/page-home.php'
        );


    if (
        ! file_exists(
            $pattern_file
        )
    ) {
        return;
    }


    /*
     * Read the actual Gutenberg markup
     * from page-home.php.
     */
    ob_start();

    include $pattern_file;

    $pattern_content =
        ob_get_clean();


    if (
        ! is_string(
            $pattern_content
        ) ||
        trim(
            $pattern_content
        ) === ''
    ) {
        return;
    }


    /*
     * Register explicitly.
     */
    register_block_pattern(
        $pattern_name,
        array(
            'title' =>
            __(
                'ARCN Homepage',
                'arcn-core-prototype'
            ),

            'description' =>
            __(
                'Complete editable homepage layout for the Austrian Restored Citizenship Network.',
                'arcn-core-prototype'
            ),

            'categories' =>
            array(
                'arcn-pages',
            ),

            'content' =>
            $pattern_content,

            'inserter' =>
            true,
        )
    );
}

add_action(
    'init',
    'arcn_core_register_homepage_pattern',
    99
);


/* =========================================================
   FRONTEND ASSETS
   ========================================================= */

function arcn_core_prototype_assets()
{

    /* =====================================================
       MAIN CSS
       ===================================================== */

    $main_css_path =
        get_theme_file_path(
            '/assets/css/main.css'
        );

    if (
        file_exists(
            $main_css_path
        )
    ) {

        wp_enqueue_style(
            'arcn-main',
            get_theme_file_uri(
                '/assets/css/main.css'
            ),
            array(),
            filemtime(
                $main_css_path
            )
        );
    }


    /* =====================================================
       HEADER CSS

       IMPORTANT:
       Independent loading.
       Does NOT depend on main.css.
       ===================================================== */

    $header_css_path =
        get_theme_file_path(
            '/assets/css/header.css'
        );

    if (
        file_exists(
            $header_css_path
        )
    ) {

        wp_enqueue_style(
            'arcn-header',
            get_theme_file_uri(
                '/assets/css/header.css'
            ),
            array(),
            filemtime(
                $header_css_path
            )
        );
    }


    /* =====================================================
       PAGE CSS
       ===================================================== */

    $page_css_path =
        get_theme_file_path(
            '/assets/css/page.css'
        );

    if (
        file_exists(
            $page_css_path
        )
    ) {

        wp_enqueue_style(
            'arcn-page',
            get_theme_file_uri(
                '/assets/css/page.css'
            ),
            array(),
            filemtime(
                $page_css_path
            )
        );
    }


    /* =====================================================
       FOOTER CSS
       ===================================================== */

    $footer_css_path =
        get_theme_file_path(
            '/assets/css/footer.css'
        );

    if (
        file_exists(
            $footer_css_path
        )
    ) {

        wp_enqueue_style(
            'arcn-footer',
            get_theme_file_uri(
                '/assets/css/footer.css'
            ),
            array(),
            filemtime(
                $footer_css_path
            )
        );
    }


    /* =====================================================
       HOMEPAGE CSS
       ===================================================== */

    $home_css_path =
        get_theme_file_path(
            '/assets/css/home.css'
        );

    if (
        file_exists(
            $home_css_path
        )
    ) {

        wp_enqueue_style(
            'arcn-home',
            get_theme_file_uri(
                '/assets/css/home.css'
            ),
            array(),
            filemtime(
                $home_css_path
            )
        );
    }


    /* =====================================================
       HERO SLIDER CSS
       ===================================================== */

    $hero_slider_css_path =
        get_theme_file_path(
            '/assets/css/hero-slider.css'
        );

    if (
        file_exists(
            $hero_slider_css_path
        )
    ) {

        wp_enqueue_style(
            'arcn-hero-slider',
            get_theme_file_uri(
                '/assets/css/hero-slider.css'
            ),
            array(),
            filemtime(
                $hero_slider_css_path
            )
        );
    }


    /* =====================================================
       EVENT LIST CSS
       ===================================================== */

    $event_list_css_path =
        get_theme_file_path(
            '/assets/css/event-list.css'
        );

    if (
        file_exists(
            $event_list_css_path
        )
    ) {

        wp_enqueue_style(
            'arcn-event-list',
            get_theme_file_uri(
                '/assets/css/event-list.css'
            ),
            array(),
            filemtime(
                $event_list_css_path
            )
        );
    }


    /* =====================================================
       PAGE NAVIGATION JS
       ===================================================== */

    $page_nav_js_path =
        get_theme_file_path(
            '/assets/css/js/page-nav.js'
        );

    if (
        file_exists(
            $page_nav_js_path
        )
    ) {

        wp_enqueue_script(
            'arcn-page-nav',
            get_theme_file_uri(
                '/assets/css/js/page-nav.js'
            ),
            array(),
            filemtime(
                $page_nav_js_path
            ),
            true
        );
    }


    /* =====================================================
       HEADER JS
       ===================================================== */

    $header_js_path =
        get_theme_file_path(
            '/assets/css/js/header.js'
        );

    if (
        file_exists(
            $header_js_path
        )
    ) {

        wp_enqueue_script(
            'arcn-header',
            get_theme_file_uri(
                '/assets/css/js/header.js'
            ),
            array(),
            filemtime(
                $header_js_path
            ),
            true
        );
    }


    /* =====================================================
       HERO SLIDER JS
       ===================================================== */

    $hero_slider_js_path =
        get_theme_file_path(
            '/assets/css/js/hero-slider.js'
        );

    if (
        file_exists(
            $hero_slider_js_path
        )
    ) {

        wp_enqueue_script(
            'arcn-hero-slider',
            get_theme_file_uri(
                '/assets/css/js/hero-slider.js'
            ),
            array(),
            filemtime(
                $hero_slider_js_path
            ),
            true
        );
    }
}

add_action(
    'wp_enqueue_scripts',
    'arcn_core_prototype_assets',
    20
);


/* =========================================================
   PATTERN CATEGORIES
   ========================================================= */

function arcn_core_prototype_pattern_categories()
{

    register_block_pattern_category(
        'arcn-sections',
        array(
            'label' =>
            __(
                'ARCN Sections',
                'arcn-core-prototype'
            ),
        )
    );


    register_block_pattern_category(
        'arcn-pages',
        array(
            'label' =>
            __(
                'ARCN Page Starters',
                'arcn-core-prototype'
            ),
        )
    );
}

add_action(
    'init',
    'arcn_core_prototype_pattern_categories'
);


/* =========================================================
   HERO SLIDER EDITOR BLOCK
   ========================================================= */

function arcn_hero_slider_editor_assets()
{

    $path =
        '/assets/css/js/hero-slider-editor.js';


    $full_path =
        get_theme_file_path(
            $path
        );


    if (
        ! file_exists(
            $full_path
        )
    ) {
        return;
    }


    wp_enqueue_script(
        'arcn-hero-slider-editor',
        get_theme_file_uri(
            $path
        ),
        array(
            'wp-blocks',
            'wp-element',
            'wp-components',
            'wp-dom-ready',
            'wp-block-editor',
        ),
        filemtime(
            $full_path
        ),
        true
    );
}

add_action(
    'enqueue_block_editor_assets',
    'arcn_hero_slider_editor_assets'
);


/* =========================================================
   AUTOMATIC STANDARD PAGE NAVIGATION
   ========================================================= */

function arcn_page_navigation_editor_assets()
{

    $path =
        '/assets/css/js/page-nav-editor.js';


    wp_enqueue_script(
        'arcn-page-nav-editor',
        get_theme_file_uri(
            $path
        ),
        array(
            'wp-hooks',
            'wp-compose',
            'wp-element',
            'wp-block-editor',
            'wp-components',
            'wp-data',
            'wp-i18n',
        ),
        filemtime(
            get_theme_file_path(
                $path
            )
        ),
        true
    );
}

add_action(
    'enqueue_block_editor_assets',
    'arcn_page_navigation_editor_assets'
);


function arcn_build_standard_page_navigation(
    $content
) {

    /* -----------------------------------------------------
       DO NOT MODIFY GUTENBERG / ADMIN
       ----------------------------------------------------- */

    if (
        is_admin()
    ) {
        return $content;
    }


    /* -----------------------------------------------------
       ONLY ARCN STANDARD PAGES
       ----------------------------------------------------- */

    if (
        strpos(
            $content,
            'arcn-standard-page'
        ) === false
    ) {
        return $content;
    }


    /* -----------------------------------------------------
       DOMDOCUMENT
       ----------------------------------------------------- */

    if (
        ! class_exists(
            'DOMDocument'
        )
    ) {
        return $content;
    }


    $previous_libxml_setting =
        libxml_use_internal_errors(
            true
        );


    $dom =
        new DOMDocument(
            '1.0',
            'UTF-8'
        );


    /* -----------------------------------------------------
       PARSER WRAPPER
       ----------------------------------------------------- */

    $wrapped_content =
        '<div id="arcn-content-parser-root">' .
        $content .
        '</div>';


    $encoded_content =
        '<?xml encoding="UTF-8">' .
        $wrapped_content;


    $loaded =
        $dom->loadHTML(
            $encoded_content,
            LIBXML_HTML_NOIMPLIED |
                LIBXML_HTML_NODEFDTD
        );


    if (
        ! $loaded
    ) {

        libxml_clear_errors();

        libxml_use_internal_errors(
            $previous_libxml_setting
        );

        return $content;
    }


    $xpath =
        new DOMXPath(
            $dom
        );


    /* -----------------------------------------------------
       FIND STANDARD PAGE
       ----------------------------------------------------- */

    $pages =
        $xpath->query(
            '//*[contains(concat(" ", normalize-space(@class), " "), " arcn-standard-page ")]'
        );


    if (
        ! $pages ||
        $pages->length === 0
    ) {

        libxml_clear_errors();

        libxml_use_internal_errors(
            $previous_libxml_setting
        );

        return $content;
    }


    foreach (
        $pages as $page
    ) {

        if (
            ! $page instanceof DOMElement
        ) {
            continue;
        }


        /* =================================================
           FIND PAGE NAV
           ================================================= */

        $nav_nodes =
            $xpath->query(
                './/*[contains(concat(" ", normalize-space(@class), " "), " arcn-page-nav ")]',
                $page
            );


        if (
            ! $nav_nodes ||
            $nav_nodes->length === 0
        ) {
            continue;
        }


        $nav =
            $nav_nodes->item(
                0
            );


        if (
            ! $nav instanceof DOMElement
        ) {
            continue;
        }


        /* =================================================
           FIND SECTIONS
           ================================================= */

        $sections =
            $xpath->query(
                './/*[contains(concat(" ", normalize-space(@class), " "), " arcn-content-section ")]',
                $page
            );


        if (
            ! $sections ||
            $sections->length === 0
        ) {
            continue;
        }


        /* =================================================
           CLEAR NAV PLACEHOLDER
           ================================================= */

        while (
            $nav->firstChild
        ) {

            $nav->removeChild(
                $nav->firstChild
            );
        }


        $used_ids =
            array();


        /* =================================================
           BUILD LINKS
           ================================================= */

        foreach (
            $sections as
            $section_index =>
            $section
        ) {

            if (
                ! $section instanceof DOMElement
            ) {
                continue;
            }


            /* ---------------------------------------------
               SECTION HEADING
               --------------------------------------------- */

            $heading_nodes =
                $xpath->query(
                    './/*[contains(concat(" ", normalize-space(@class), " "), " arcn-section-heading ")]',
                    $section
                );


            if (
                ! $heading_nodes ||
                $heading_nodes->length === 0
            ) {
                continue;
            }


            $heading =
                $heading_nodes->item(
                    0
                );


            if (
                ! $heading instanceof DOMElement
            ) {
                continue;
            }


            $section_title =
                preg_replace(
                    '/\s+/u',
                    ' ',
                    $heading->textContent
                );


            $section_title =
                trim(
                    (string)
                    $section_title
                );


            if (
                $section_title === ''
            ) {
                continue;
            }


            /* ---------------------------------------------
               OPTIONAL SHORT NAV LABEL
               --------------------------------------------- */

            $navigation_title =
                '';


            $nav_label_nodes =
                $xpath->query(
                    './/*[contains(concat(" ", normalize-space(@class), " "), " arcn-nav-label ")]',
                    $section
                );


            if (
                $nav_label_nodes &&
                $nav_label_nodes->length > 0
            ) {

                $nav_label =
                    $nav_label_nodes->item(
                        0
                    );


                if (
                    $nav_label instanceof DOMElement
                ) {

                    $navigation_title =
                        preg_replace(
                            '/\s+/u',
                            ' ',
                            $nav_label->textContent
                        );


                    $navigation_title =
                        trim(
                            (string)
                            $navigation_title
                        );
                }
            }


            if (
                $navigation_title === ''
            ) {

                $navigation_title =
                    $section_title;
            }


            /* ---------------------------------------------
               SECTION ID
               --------------------------------------------- */

            $base_id =
                sanitize_title(
                    $section_title
                );


            if (
                $base_id === ''
            ) {

                $base_id =
                    'section-' .
                    (
                        (int)
                        $section_index +
                        1
                    );
            }


            $section_id =
                $base_id;


            $duplicate_number =
                2;


            while (
                in_array(
                    $section_id,
                    $used_ids,
                    true
                )
            ) {

                $section_id =
                    $base_id .
                    '-' .
                    $duplicate_number;


                $duplicate_number++;
            }


            $used_ids[] =
                $section_id;


            $section->setAttribute(
                'id',
                $section_id
            );


            /*
             * Keep the section and its ID,
             * but omit its navigation entry.
             */
            if (
                in_array(
                    'arcn-nav-excluded',
                    preg_split(
                        '/\s+/',
                        trim(
                            $section->getAttribute(
                                'class'
                            )
                        )
                    ),
                    true
                )
            ) {
                continue;
            }


            /* ---------------------------------------------
               NAV LINK
               --------------------------------------------- */

            $link =
                $dom->createElement(
                    'a'
                );


            if (
                ! $link instanceof DOMElement
            ) {
                continue;
            }


            $link->setAttribute(
                'href',
                '#' .
                    $section_id
            );


            $link->setAttribute(
                'class',
                'arcn-page-nav__link'
            );


            $link->appendChild(
                $dom->createTextNode(
                    $navigation_title
                )
            );


            $nav->appendChild(
                $link
            );
        }
    }


    /* =====================================================
       EXTRACT CONTENT
       ===================================================== */

    $root_nodes =
        $xpath->query(
            '//*[@id="arcn-content-parser-root"]'
        );


    if (
        ! $root_nodes ||
        $root_nodes->length === 0
    ) {

        libxml_clear_errors();

        libxml_use_internal_errors(
            $previous_libxml_setting
        );

        return $content;
    }


    $root =
        $root_nodes->item(
            0
        );


    if (
        ! $root instanceof DOMElement
    ) {

        libxml_clear_errors();

        libxml_use_internal_errors(
            $previous_libxml_setting
        );

        return $content;
    }


    $output =
        '';


    foreach (
        $root->childNodes
        as $child
    ) {

        $child_html =
            $dom->saveHTML(
                $child
            );


        if (
            is_string(
                $child_html
            )
        ) {

            $output .=
                $child_html;
        }
    }


    libxml_clear_errors();


    libxml_use_internal_errors(
        $previous_libxml_setting
    );


    return $output;
}


/* =========================================================
   APPLY STANDARD PAGE NAVIGATION
   ========================================================= */

add_filter(
    'the_content',
    'arcn_build_standard_page_navigation',
    20
);
