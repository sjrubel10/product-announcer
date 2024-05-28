<?php
namespace Product\Announcer\Admin;

use Product\Announcer\Classes\Mail;
use Product\Announcer\Classes\EmailFromOrder;
class SendMail
{
    public $emailFromOrder;
    public $mail;
    public function __construct() {
        add_action( 'wp_after_insert_post',[ $this, 'my_custom_function'], 10, 4 );

        $mail = new Mail();
        $emailFromOrder = new EmailFromOrder();

        $this->mail = $mail;
        $this->emailFromOrder = $emailFromOrder;
    }

    function my_custom_function( $post_id, $post, $update, $c ) {
        $mail_send_checked = get_option( 'PA_mailSendChecked' );
        $mailSend = "";
        if( $mail_send_checked ) {
            if ($post->post_type === 'product' && $post->post_status === 'publish' && $update) {
                $product_title = $post->post_title;
                $product_image_url = 'https://images.pexels.com/photos/90946/pexels-photo-90946.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2';
                $product_description = $post->post_content;

//            $product_categories = $this->emailFromOrder->get_product_categories( $post_id );
//            $similar_products = $this->emailFromOrder->find_similar_product_titles( $product_title, 0.6 );

                $orderIds = $orders_data = [];
//                if ( count( $similar_products ) > 0) {
                    $orderIds = $this->emailFromOrder->getOrderIdsFromProductTitles( $product_title );
//                }
                if ( count( $orderIds ) > 0 ) {
                    $orders_data = $this->emailFromOrder->getOrdersData( $orderIds );
                    if( is_array( $orders_data ) && count( $orders_data ) > 0 ){
                        foreach ( $orders_data as $data ){
                            if( isset( $data['billing_email'] ) ) {
                                $send_mails = $data['billing_email'];
                                error_log( print_r( ['$send_mails' => $send_mails], true ) );
                                $mailSend = $this->mail->send_custom_mail( $send_mails, 'mail subject', $product_title, $product_image_url, $product_description);
                            }
                        }
                    }
                }
            }
        }

        return $mailSend;
    }

}