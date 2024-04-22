<?php

namespace Product\Announcer\Classes;

class EmailFromOrder
{

    protected $wpdb;
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
    }


    public function get_product_categories( $product_id ) {
        $product_categories = wp_get_post_terms($product_id, 'product_cat');
        if (!is_wp_error($product_categories) && !empty($product_categories)) {
            $category_names = array();

            foreach ($product_categories as $category) {
                $category_names[] = $category->name;
            }
            error_log( print_r( ['$category_names'=>$category_names], true ) );
            return $category_names;
        } else {
            return false;
        }

    }
    public function find_similar_product_titles($product_title, $threshold = 0.6) {
        global $wpdb;

        // Escape the product title to prevent SQL injection
        $product_title = $wpdb->esc_like($product_title);

        // Build the SQL query to retrieve similar product titles
        $sql = "
        SELECT post_title
        FROM {$wpdb->posts}
        WHERE post_type = 'product'
        AND post_status = 'publish'
    ";

        // Execute the SQL query
        $product_titles = $wpdb->get_col($sql);

        // Find similar product titles based on Levenshtein distance
        $similar_titles = array();
        foreach ($product_titles as $title) {
            $distance = levenshtein($product_title, $title);
            $length = max(strlen($product_title), strlen($title));
            $similarity_ratio = 1 - ($distance / $length);

            if ($similarity_ratio >= $threshold) {
                $similar_titles[] = $title;
            }
        }

        return $similar_titles;
    }
    public function getOrderIdsFromProductTitles($productTitles) {
        $orderIds = array();

        // Prepare placeholders for the product titles
        $placeholders = array_fill(0, count($productTitles), '%s');
        $placeholders = implode(',', $placeholders);

        // Query to get the order IDs based on the product titles
        $results = $this->wpdb->get_results(
            $this->wpdb->prepare("
                SELECT DISTINCT oi.order_id
                FROM {$this->wpdb->prefix}woocommerce_order_items AS oi
                INNER JOIN {$this->wpdb->prefix}posts AS p ON oi.order_id = p.ID
                WHERE oi.order_item_name IN ( $placeholders )
            ", $productTitles)
        );

        // Extract order IDs from the results
        foreach ($results as $result) {
            $orderIds[] = $result->order_id;
        }

        return $orderIds;
    }
    /**
     * Retrieve order data from the database based on provided order IDs.
     *
     * @param array $orderIds An array of order IDs.
     * @return array An array containing order data (id, billing_email, customer_id) for the given order IDs.
     */
    public function getOrdersData( $orderIds ) {
        $ordersData = array();

        // Prepare placeholders for the order IDs
        $placeholders = implode(',', array_fill(0, count($orderIds), '%d'));

        // Prepare and execute the SQL query
        $query = $this->wpdb->prepare("
            SELECT `id`,`billing_email`,`customer_id`
            FROM `{$this->wpdb->prefix}wc_orders`
            WHERE `id` IN($placeholders)
        ", $orderIds);
        $results = $this->wpdb->get_results($query);

        // Extract data from results
        foreach ($results as $result) {
            $ordersData[] = array(
                'id' => $result->id,
                'billing_email' => $result->billing_email,
                'customer_id' => $result->customer_id
            );
        }

        return $ordersData;
    }


}