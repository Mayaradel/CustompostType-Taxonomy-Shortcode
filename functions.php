<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );


/*
* Creating a function to create our CPT
*/
function custom_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Books', 'Post Type General Name', 'twentytwenty' ),
        'singular_name'       => _x( 'Book', 'Post Type Singular Name', 'twentytwenty' ),
        'menu_name'           => __( 'Books', 'twentytwenty' ),
        'parent_item_colon'   => __( 'Parent Book', 'twentytwenty' ),
        'all_items'           => __( 'All Books', 'twentytwenty' ),
        'view_item'           => __( 'View Book', 'twentytwenty' ),
        'add_new_item'        => __( 'Add New Book', 'twentytwenty' ),
        'add_new'             => __( 'Add New', 'twentytwenty' ),
        'edit_item'           => __( 'Edit Book', 'twentytwenty' ),
        'update_item'         => __( 'Update Book', 'twentytwenty' ),
        'search_items'        => __( 'Search Book', 'twentytwenty' ),
        'not_found'           => __( 'Not Found', 'twentytwenty' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'twentytwenty' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'books', 'twentytwenty' ),
        'description'         => __( 'Book news and reviews', 'twentytwenty' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'genres' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'books', $args );
 
}
add_action( 'init', 'custom_post_type', 0 );




//hook into the init action and call create_book_taxonomies when it fires
 
add_action( 'init', 'create_subjects_hierarchical_taxonomy', 0 );
 
//create a custom taxonomy name it subjects for your posts
 
function create_subjects_hierarchical_taxonomy() {
 
// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI
 
  $labels = array(
    'name' => _x( 'Subjects', 'taxonomy general name' ),
    'singular_name' => _x( 'Subject', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Subjects' ),
    'all_items' => __( 'All Subjects' ),
    'parent_item' => __( 'Parent Subject' ),
    'parent_item_colon' => __( 'Parent Subject:' ),
    'edit_item' => __( 'Edit Subject' ), 
    'update_item' => __( 'Update Subject' ),
    'add_new_item' => __( 'Add New Subject' ),
    'new_item_name' => __( 'New Subject Name' ),
    'menu_name' => __( 'Subjects' ),
  );    
 
// Now register the taxonomy
  register_taxonomy('subjects',array('books'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'subject' ),
  ));
 
}


//>> Create Shortcode to Display books Post Types
function create_shortcode_books_post_type($atts , $content=null){
    $atts= shortcode_atts(
        array(
            'type' => 'Books',
            'count' => 5, 
            'status' => 'published',
            'Subjects'=>'action'
        ), $atts);

        $args= array (
            'post_type' =>$atts['type'],
            'posts_per_page' => $atts['count'],
            'tax_query' => array(
                array(
                    'taxonomy' => 'subjects', // the custom vocabulary
                    'field' => 'slug',
                    'terms' =>'romance', // provide the term slugs
                ),
            ),

        );
       
       
        // 'tax_query' => array(
        //     array(
        //         'taxonomy' => 'fabric_building_types',
        //         'field' => 'term_id',
        //         'terms' => $cat->term_id,
        //     )
        // )



 
    $query = new WP_Query($args);
  
    if($query->have_posts()) :
  
        while($query->have_posts()) :
  
        $query->the_post() ;
           
        $result .= '<div class="book-item">';
      //$result .= '<div class="book-poster">' . get_the_post_thumbnail() . '</div>';
        $result .= '<div class="book-name">' . get_the_title() . '</div>';
      //$result .= '<div class="book-desc">' . get_the_content() . '</div>'; 
        $result .= '</div>';
  
        endwhile;
        wp_reset_postdata();
  
    endif;    
    return $result;            
}
  
add_shortcode( 'books', 'create_shortcode_books_post_type' ); 
  
// shortcode code ends here
