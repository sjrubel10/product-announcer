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
            return $category_names;
        } else {
            return false;
        }

    }
    public function find_similar_product_titles( $product_title, $threshold ) {
        /*global $wpdb;
        $product_title = $wpdb->esc_like($product_title);
        $product_titles = $wpdb->get_col($wpdb->prepare("
                SELECT post_title
                FROM {$wpdb->posts}
                WHERE post_type = %s
                AND post_status = %s
                ", 'product', 'publish'));
        $similar_titles = array();
        foreach ($product_titles as $title) {
            $distance = levenshtein($product_title, $title);
            $length = max(strlen($product_title), strlen($title));
            $similarity_ratio = 1 - ($distance / $length);

            if ($similarity_ratio >= $threshold) {
                $similar_titles[] = $title;
            }
        }*/
        $similar_titles = array();

        return $similar_titles;
    }
    public function getOrderIdsFromProductTitles_old( $productTitles ) {
        $orderIds = array();
        /*global $wpdb;

        $placeholders = array_fill(0, count($productTitles), '%s');
        $placeholder_string = implode(',', $placeholders );
        $values = array_map(function($title) use ($wpdb) {
            return $wpdb->esc_like( $title );
        }, $productTitles);
        $results = $wpdb->get_results( $wpdb->prepare("
        SELECT DISTINCT oi.order_id
        FROM {$wpdb->prefix}woocommerce_order_items AS oi
        INNER JOIN {$wpdb->prefix}posts AS p ON oi.order_id = p.ID
        WHERE oi.order_item_name IN ( $placeholder_string )
    ", $values) );
        foreach ($results as $result) {
            $orderIds[] = $result->order_id;
        }*/

        return $orderIds;
    }

    function getOrderIdsFromProductTitles( $product_title ) {
        global $wpdb;

        $query = $wpdb->prepare(
            "SELECT DISTINCT order_id 
        FROM {$wpdb->prefix}woocommerce_order_items
        WHERE order_item_name LIKE %s",
            '%' . $wpdb->esc_like( $product_title ) . '%'
        );
        $order_ids = $wpdb->get_col( $query );

        return array_unique( $order_ids );
    }


    /**
     * Retrieve order data from the database based on provided order IDs.
     *
     * @param array $orderIds An array of order IDs.
     * @return array An array containing order data (id, billing_email, customer_id) for the given order IDs.
     */
    public function getOrdersData( $order_ids ) {
        /*global $wpdb;
        $ordersData = array();
        $placeholders = implode(',', array_fill(0, count( $order_ids), '%d'));

        // Prepare and execute the SQL query
        $results = $wpdb->get_results( $wpdb->prepare("
            SELECT `id`,`billing_email`,`customer_id`
            FROM `{$wpdb->prefix}wc_orders`
            WHERE `id` IN( $placeholders )
        ", $orderIds) );

        // Extract data from results
        foreach ($results as $result) {
            $orders_data[] = array(
                'id' => $result->id,
                'billing_email' => $result->billing_email,
                'customer_id' => $result->customer_id
            );
        }*/
        foreach ( $order_ids as $order_id ) {
            $order = wc_get_order( $order_id );
            if ( $order ) {
                $orders_data[] = array(
                    'order_id' => $order->get_id(),
                    'order_date' => $order->get_date_created()->date('Y-m-d H:i:s'),
                    'order_status' => $order->get_status(),
                    'total' => $order->get_total(),
                    'billing_name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                    'billing_email' => $order->get_billing_email(),
                    'billing_phone' => $order->get_billing_phone(),
                    'shipping_name' => $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name(),
                    'items' => array(),
                );
            }
        }

        return $orders_data;
    }


}