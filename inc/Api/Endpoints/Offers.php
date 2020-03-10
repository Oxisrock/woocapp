<?php

namespace Inc\Api\Endpoints;

use WP_Error;

use \Inc\Base\BaseController;

use WP_REST_Response;

use WP_REST_Request;

use WP_Query;

use Automattic\WooCommerce\HttpClient\HttpClientException;

class Offers extends BaseController {
    // API custom endpoints for WP-REST API
    public function getOffers() {

        $query = new WP_Query( $args =[
            'post_type'             => 'boletin',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => -1, // Limit: two products
            // 'post__not_in'          =>[ get_the_id() ], // Excluding current product
            // 'tax_query'             =>[[
            //     'taxonomy'      => $taxonomy,
            //     'field'         => 'term_id', // can be 'term_id', 'slug' or 'name'
            //     'terms'         => $term->term_id,
            // ], ], ]
            
         ]);
        $offers = [];
        if ( $query->have_posts() ): while( $query->have_posts() ): $query->the_post();
            $products = get_field('producto_promocion');
            $products_offers = [];
            foreach($products as $product) :
                $product_object = wc_get_product($product->ID, true);
                $products_offers[] = $this->get_product($product_object);
            endforeach;
            $offers[] = [
                'id' =>  get_the_ID(),
                'title' => get_the_title(),
                'image' => $this->getImage(),
                ];
            endwhile;
            wp_reset_postdata();
        endif;
        
        if (empty($offers)) :
            return new WP_Error( '404', 'no found offers', '' );
        endif;

        $offers = json_encode($offers);

        $response = new WP_REST_Response($offers);

        $response->set_status(200);
    
        return $response;
    }

    public function getProductOffer(WP_REST_Request $request) {
        
        $offer_id = $request['offer_id'];
        $offer = get_post($offer_id);
        
        if (empty($offer)) :
            return new WP_Error( '404', 'offer no exist', '' );
        endif;
        //products in offert
        $products_offers = [];
        $products = get_field('producto_promocion', $offer->ID);
        
        if (empty($products)) :
            return new WP_Error( '404', 'its offer not have products', '' );
        endif;

        foreach($products as $product) :
            $product_object = wc_get_product($product->ID, true);
            $products_offers[] = $this->get_product($product_object);
        endforeach;

        $products_offers = json_encode($products_offers);

        $response = new WP_REST_Response($products_offers);

        $response->set_status(200);
    
        return $response;
    }

    public function get_product($product) {

        $product_categories = $product->get_category_ids();
        $tax = [];
        $product_id = $product->get_id();
        $brands = $this->getBrands($product_id);
        $products = [
            'id' => $product->get_id(),
            'name' => $product->get_name(),
            'sku' => $product->get_slug(),
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
            'variations' => $this->getVariationsProducts($product),
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
                $brands
                // 'name' => $term_name,
                // 'imagen' => $imagen,
                // 'logo' => $logo
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
        return $products;
    }
    public function getCategoriesProducts($categories) {
        $cats = [];
        foreach ($categories as $category) :
           $cats[] = get_term_by('id', $category, 'product_cat');
        endforeach;

        return $cats;
    }

    public function getBrands($product_id) {
        $terms = get_the_terms( $product_id, 'marca' );
        $brands = [];
        foreach ($terms as $term) {
            $terms_id = 'term_'.$term->term_id;
            $term_name = $term->name;
            $imagen = get_field('image', $terms_id);
            $logo = get_field('logo', $terms_id);
         $brands[] = [
               'name' => $term_name,
                'imagen' => $imagen,
                'logo' => $logo
         ];
        }
        return $brands;
    }

    public function getVariationsProducts($product) {
        $variations = [];
        if($product->is_type('variable')) :
            foreach($product->get_available_variations() as $variation ) :
                // Variation ID
                $variation_id = $variation['variation_id'];
                
                // Attributes
                $attributes = array();
                foreach( $variation['attributes'] as $key => $value ) :
                    $taxonomy = str_replace('attribute_', '', $key );
                    $taxonomy_label = get_taxonomy( $taxonomy )->labels->singular_name;
                    $term_name = get_term_by( 'slug', $value, $taxonomy )->name;
                    $attributes[] = $taxonomy_label.': '.$term_name;
                endforeach;
                
                // Prices
                $active_price = floatval($variation['display_price']); // Active price
                $regular_price = floatval($variation['display_regular_price']); // Regular Price
                if( $active_price != $regular_price ) :
                    $sale_price = $active_price; // Sale Price
                endif;
                $variations[] = [
                    'id' => $variation_id,
                    'attributes' => $attributes,
                    'image' => $variation['image'],
                    'price' => [
                        'active_price' => $active_price,
                        'regular_price' => $regular_price,
                    ]
                ];
            endforeach;
            return $variations;
        endif;
    }
    public function getImage() {
        $image = [
            'thumbnail' => wp_get_attachment_image_src(get_post_thumbnail_id(),'thumbnail'),
            'medium' => wp_get_attachment_image_src(get_post_thumbnail_id(),'medium'),
            'woocommerce_thumbnail' => wp_get_attachment_image_src(get_post_thumbnail_id(),'woocommerce_thumbnail'),
            'medium_large' =>  wp_get_attachment_image_src(get_post_thumbnail_id(),'medium_large'),
            'large' =>  wp_get_attachment_image_src(get_post_thumbnail_id(),'large'),
            'full' =>  wp_get_attachment_image_src(get_post_thumbnail_id(),'full'),
            ];
        return $image;
    }
}
