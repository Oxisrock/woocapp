<?php

namespace Inc\Api\Endpoints;

use WP_Error;

use \Inc\Base\BaseController;

use WP_REST_Response;

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
            $output[] = [
                'ID' => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
                'description' => $term->description,
                'imagen' => $imagen['sizes']
            ];
        endforeach;

        $brands = json_encode($output);

        $response = new WP_REST_Response($brands);
        $response->set_status(200);
    
        return $response;
    }

    public function productBrands($request) {

        $output = array();
        $args = array(
            'post_type'             => 'product',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => '12',
            'meta_query'            => array(
                array(
                    'key'           => '_visibility',
                    'value'         => array('catalog', 'visible'),
                    'compare'       => 'IN'
                )
            ),
            'tax_query'             => array(
                array(
                    'taxonomy'      => 'product_cat',
                    'field' => 'term_id', //This is optional, as it defaults to 'term_id'
                    'terms'         => $request["brand_id"],
                    'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
                )
            )
        );
        $products = new WP_Query($args);
        var_dump($products);
        return $products;
    }
}
