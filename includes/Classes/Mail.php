<?php
namespace Product\Announcer\Classes;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
class Mail
{
    public function __construct() {
//        error_log( print_r( ['dir'=>__DIR__], true ) );

    }
    public function send_custom_mail( $receiver_mail, $subject, $product_title, $product_image_url, $product_description ) {
//        error_log( print_r( ['$receiver_mail'=>$receiver_mail], true ) );
        $mail = new PHPMailer(true );
        try {
            $mail->isSMTP();
            // Configure SMTP settings here
            $is_mail_sent = $this->send_mail( $mail, $receiver_mail, $subject, $product_title, $product_image_url, $product_description );
            return 'Email sent successfully';
        } catch (Exception $e) {
            return "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    public function send_mail( $mail, $email, $subject, $product_title, $product_image_url, $product_description ) {
        // Configure SMTP settings here
        $send_mail_settings_data = get_option('PA_send_mail_settings');
//        if( !empty( $form_data ) && is_array( $form_data ) && count( $form_data ) > 0 && isset( $send_mail_settings_data['email'] ) && isset( $send_mail_settings_data['appkey'] ) ){
        $mail_from = trim( $send_mail_settings_data['email'] );
        $mail_from_name = trim( $send_mail_settings_data['fromname'] );
        $appkey = trim( $send_mail_settings_data['appkey'] );
        $subject = trim( $send_mail_settings_data['subject'] );
        $body_message = trim( $send_mail_settings_data['body_message'] );

        $mail->Host       = 'smtp.gmail.com';                   // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                               // Enable SMTP authentication
        $mail->Username   = $mail_from;                         // SMTP username
        $mail->Password   = $appkey;                            // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // Enable TLS encryption
//        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;      // Enable SSL encryption
        $mail->Port       = 587;                                // TCP port to connect to
        // $mail->Port     = 465;                               // Use 465 for SMTPS port with SSL encryption

        // Sender and recipient
        $mail->setFrom( $mail_from , $mail_from_name );
        $mail->addAddress( $email );                            // Add a recipient
        // Content
        $mail->isHTML( true );                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $this->generate_email_body( $product_title, $product_image_url, $product_description, $body_message );
        $mail->AltBody = 'This is the plain text message body for non-HTML mail clients';

       
        // Send email
        $mail->send();

        return true; // Return true if email is sent successfully
    }

    /**
     * Generates the HTML email body for product details.
     *
     * @param string $product_title        The title of the product.
     * @param string $product_image_url    The URL of the product image.
     * @param string $product_description The description of the product.
     * @return string                     The generated email body HTML.
     */
    public function generate_email_body( $product_title, $product_image_url, $product_description, $body_message ) {

//        $product_description = $body_message.'</br>'.$product_description;
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo esc_html__('Product Details', 'product-announcer'); ?></title>
            <style>
                /* Add your CSS styles here */
                .product-container {
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    padding: 20px;
                    margin-bottom: 20px;
                }
                .product-title {
                    font-size: 20px;
                    font-weight: bold;
                    margin-bottom: 10px;
                }
                .product-image {
                    max-width: 100%;
                    height: auto;
                    margin-bottom: 10px;
                }
                .product-description {
                    font-size: 16px;
                }
            </style>
        </head>
        <body>
        <div class="product-container">
            <div>
                <h2><?php echo esc_html__('Additional Information', 'product-announcer'); ?></h2>
                <p><?php echo esc_html__( $body_message , 'product-announcer'); ?></p>
            </div>
            <div class="product-title"><?php echo esc_html($product_title); ?></div>
            <img src="<?php echo esc_url($product_image_url); ?>" alt="<?php echo esc_attr__('Product Image', 'product-announcer'); ?>" class="product-image">
            <div class="product-description"><?php echo esc_html($product_description); ?></div>
        </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }




}