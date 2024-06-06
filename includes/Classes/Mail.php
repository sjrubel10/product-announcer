<?php
namespace Product\Announcer\Classes;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
class Mail
{
    public function __construct() {}
    public function send_custom_mail( $receiver_mail, $subject, $product_title, $product_image_url, $product_description ) {
        $mail = new PHPMailer(true );
        try {
            $mail->isSMTP();
            $is_mail_sent = $this->send_mail( $mail, $receiver_mail, $subject, $product_title, $product_image_url, $product_description );
            return 'Email sent successfully';
        } catch (Exception $e) {
            return "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    public function send_mail( $mail, $receiver_emails, $subject, $product_title, $product_image_url, $product_description ) {
        $send_mail_settings_data = maybe_unserialize( get_option('PA_send_mail_settings' ) );
        if( !empty( $send_mail_settings_data ) && is_array( $send_mail_settings_data ) && count( $send_mail_settings_data ) > 0 && isset( $send_mail_settings_data['email'] ) && isset( $send_mail_settings_data['email_host'] ) && isset( $send_mail_settings_data['appkey'] ) ) {
            $mail_from = trim($send_mail_settings_data['email']);
            $mail_host = trim($send_mail_settings_data['email_host']);
            $mail_from_name = trim($send_mail_settings_data['fromname']);
            $appkey = trim($send_mail_settings_data['appkey']);
            $subject = trim($send_mail_settings_data['subject']);
            $body_message = trim($send_mail_settings_data['body_message']);

            $mail->Host = $mail_host;                            // Set the SMTP server to send through
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $mail_from;                         // SMTP username
            $mail->Password = $appkey;                            // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // Enable TLS encryption
            $mail->Port = 587;                                    // TCP port to connect to
//            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;      // Enable SSL encryption
//            $mail->Port     = 465;                               // Use 465 for SMTPS port with SSL encryption

            // Sender and recipient
            $mail->setFrom($mail_from, $mail_from_name);
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $this->generate_email_body($product_title, $product_image_url, $product_description, $body_message);
            $mail->AltBody = 'This is the plain text message body for non-HTML mail clients';

            // Send email
            foreach ($receiver_emails as $recipient ) {
                if ($mail_from !== $recipient) {
                    $mail->clearAddresses(); // Clear all recipients for the next iteration
                    $mail->addAddress($recipient, 'name'); // Add a recipient
                    $mail->send();
                }
            }
            return true;
        }

        return false; // Return true if email is sent successfully
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
                <p><?php echo esc_html( $body_message ); ?></p>

                <p>Hi {$user_name},</p>
                <p>We hope you are enjoying your previously buying product!</p>
                <p>We are excited to introduce our latest addition: <strong><?php echo esc_html( $product_title ); ?></strong>. We think you'll love it as much as your previous purchase.</p>
                <p>To learn more about this new product and to make a purchase, click the button below:</p>
                <a class='button' href='#'>View New Product</a>
                <p>Thank you for being a valued customer!</p>
                <p>Best regards,</p>
                <p>Your Company Name</p>

            </div>
            <div class="product-title"><?php echo esc_html( $product_title ); ?></div>
            <img src="<?php echo esc_url($product_image_url); ?>" alt="<?php echo esc_attr__('Product Image', 'product-announcer'); ?>" class="product-image">
            <div class="product-description"><?php echo esc_html($product_description); ?></div>
        </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }




}