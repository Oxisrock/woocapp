<?php

namespace Inc\Api\Endpoints;

use WP_Error;

use \Inc\Base\BaseController;

use WP_REST_Response;

use WP_REST_Request;

use WP_Query;

class Brands extends BaseController {
    // API custom endpoints for WP-REST API
    public function listBrands() {

        $output = [];
        $post_type = 'product';
        // Get all the taxonomies for this post type
        $taxonomies = get_object_taxonomies( (object) array( 'post_type' => $post_type ) );

        $terms = get_terms( 'marca' );

        foreach($terms as $term) : 
            $term_id = 'term_'.$term->term_id;
            $imagen = get_field('image', $term_id);
            $logo = get_field('logo', $term_id);
            $output[] = [
                'ID' => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
                'description' => $term->description,
                'imagen' => $imagen['sizes'],
                'logo' => $logo['sizes'],
                'count' => $term->count
            ];
        endforeach;

        $brands = json_encode($output);

        $response = new WP_REST_Response($brands);
        $response->set_status(200);
    
        return $response;
    }

    public function productsBrands(WP_REST_Request $request) {
        
        $taxonomy = 'marca'; // The targeted custom taxonomy
        
        $term_ids = $request['brand_id'];

        $term = get_term_by('id', $term_ids, 'marca');
        if (empty($term)) :
            return new WP_Error( '404', 'brands not exist', '' );
        endif;

        $query = new WP_Query( $args =[
            'post_type'             => 'product',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => 2, // Limit: two products
            'post__not_in'          =>[ get_the_id() ], // Excluding current product
            'tax_query'             =>[[
                'taxonomy'      => $taxonomy,
                'field'         => 'term_id', // can be 'term_id', 'slug' or 'name'
                'terms'         => $term->term_id,
            ], ], ]
        );

        $terms_id = 'term_'.$term->term_id;
        $term_name = $term->name;
        $imagen = get_field('image', $terms_id);
        $logo = get_field('logo', $terms_id);

        $products = [];
        // The WP_Query loop
        if ( $query->have_posts() ): while( $query->have_posts() ): $query->the_post();
                
                $product = wc_get_product($query->post->ID, true);
                $product_categories = $product->get_category_ids();
                $products[] = [
                    'id' => $product->get_id(),
                    'name' => $product->get_name(),
                    'description' => $product->get_description(),
                    'short_description' => $product->get_short_description(),
                    'get_date_created' => $product->get_date_created(),
                    'get_date_modified' => $product->get_date_modified(),
                    'price' => ['price' => $product->get_price(),
                    'regular_price' => $product->get_regular_price(),
                    'sale_price' => $product->get_sale_price(),
                    'get_date_on_sale_from' => $product->get_date_on_sale_from(),
                    'get_date_on_sale_to' => $product->get_date_on_sale_to(),],
                    'stock' => [
                    'manage_stock' =>  $product->get_manage_stock(),
                    'stock_quantity' =>    $product->get_stock_quantity(),
                    'stock_status' => $product->get_stock_status(),
                    ],
                    'dimensions' => [
                      'weight' => $product->get_weight(),
                      'length' => $product->get_length(),
                      'width' => $product->get_width(),
                      'height' => $product->get_height(),

                    ],
                    'tax' => [
                       'tax_status' => $product->get_tax_status(),
                        'tax_class' => $product->get_tax_class(),
                    ],
                    'brands' => [
                        'name' => $term_name,
                        'imagen' => $imagen,
                        'logo' => $logo
                    ],
                    'image_id' => $product->get_image_id(),
                    'image_sizes' => [
                        'thumbnail' => wp_get_attachment_image_src($product->get_image_id(),'thumbnail'),
                        'medium' => wp_get_attachment_image_src($product->get_image_id(),'medium'),
                        'woocommerce_thumbnail' => wp_get_attachment_image_src($product->get_image_id(),'woocommerce_thumbnail'),
                        'medium_large' =>  wp_get_attachment_image_src($product->get_image_id(),'medium_large'),
                        'large' =>  wp_get_attachment_image_src($product->get_image_id(),'large'),
                        'full' =>  wp_get_attachment_image_src($product->get_image_id(),'full'),
                    ],
                    'categories' => $this->getCategoriesProducts($product_categories),
                    'gallery' => $product->get_gallery_image_ids(),
                    'reviews' => [
                        'reviews_allowed' => $product->get_reviews_allowed(),
                        'rating_counts' => $product->get_rating_counts(),
                        'average_rating' => $product->get_average_rating(),
                        'review_count' => $product->get_review_count(),
                    ]

                ];
            endwhile;
            wp_reset_postdata();
        endif;

        if (empty($products)) :
            return new WP_Error( '404', 'brands not have products', '' );
        endif;
        
        $products = json_encode($products);
        
        $response = new WP_REST_Response($products);
        
        $response->set_status(200);
    
        return $response;
    }

    public function getCategoriesProducts($categories) {
        $cats = [];
        foreach ($categories as $category) :
           $cats[] = get_term_by('id', $category, 'product_cat');
        endforeach;

        return $cats;
    }
}
