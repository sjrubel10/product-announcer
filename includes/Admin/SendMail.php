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
//        $mail = new PHPMailer(true);
        if ($post->post_type === 'product' && $post->post_status === 'publish' && $update ) {
            $product_categories = $this->emailFromOrder->get_product_categories( $post_id );
            $similar_product = $this->emailFromOrder->find_similar_product_titles( 'sdfsdf' );
            error_log( print_r( ['$similar_product'=>$similar_product], true ) );

            $product_title = $post->post_title;
            $product_image_url = 'https://images.pexels.com/photos/90946/pexels-photo-90946.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2';
            $product_description = $post->post_content;
//            $mailSend = $this->mail->send_custom_mail( 'rubelcuet10@gmail.com', 'mail subject', $product_title, $product_image_url, $product_description );

//            error_log(print_r( ['product'=>$mailSend], true ));
//            error_log(print_r( [ '$post_id'=>$post_id ,'$product_categories'=>$product_categories, '$post'=>$post->post_title], true ) );
        }
    }

}