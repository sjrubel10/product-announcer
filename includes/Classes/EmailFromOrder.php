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
//        global $wpdb;

        // Escape the product title to prevent SQL injection

        // Build the SQL query to retrieve similar product titles
        /*$sql = "
        SELECT post_title
        FROM {$this->wpdb->posts}
        WHERE post_type = 'product'
        AND post_status = 'publish'
    ";*/
        global $wpdb;
        $product_title = $wpdb->esc_like($product_title);
        $product_titles = $wpdb->get_col($wpdb->prepare("
                SELECT post_title
                FROM {$wpdb->posts}
                WHERE post_type = %s
                AND post_status = %s
                ", 'product', 'publish'));

        // Execute the SQL query
//        $product_titles = $wpdb->get_col($sql);

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
    public function getOrderIdsFromProductTitles( $productTitles ) {
        global $wpdb;
        $orderIds = array();

        // Prepare placeholders for the product titles
        $placeholders = array_fill(0, count($productTitles), '%s');

        // Construct the placeholder string
        $placeholder_string = implode(',', $placeholders);

        // Generate an array of placeholders with product titles as values
        $values = array_map(function($title) use ($wpdb) {
            return $wpdb->esc_like( $title );
        }, $productTitles);

        // Construct the query using placeholders
        $results = $wpdb->get_results( $wpdb->prepare("
        SELECT DISTINCT oi.order_id
        FROM {$wpdb->prefix}woocommerce_order_items AS oi
        INNER JOIN {$wpdb->prefix}posts AS p ON oi.order_id = p.ID
        WHERE oi.order_item_name IN ( $placeholder_string )
    ", $values) );

        // Execute the prepared query
//        $results = $wpdb->get_results($query);

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
        global $wpdb;
        $ordersData = array();

        // Prepare placeholders for the order IDs
        $placeholders = implode(',', array_fill(0, count($orderIds), '%d'));

        // Prepare and execute the SQL query
        $results = $wpdb->get_results( $wpdb->prepare("
            SELECT `id`,`billing_email`,`customer_id`
            FROM `{$wpdb->prefix}wc_orders`
            WHERE `id` IN( $placeholders )
        ", $orderIds) );

//        error_log( print_r( ['$query'=>$query], true ) );
//        $results = $wpdb->get_results( $query );

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