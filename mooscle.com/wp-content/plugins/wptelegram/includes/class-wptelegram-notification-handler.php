<?php

/**
 * Notification Handling functionality of the plugin.
 *
 * @link       https://t.me/WPTelegram
 * @since      1.4.0
 *
 * @package    Wptelegram
 * @subpackage Wptelegram/includes
 */

/**
 * The Notification Handling functionality of the plugin.
 *
 *
 * @package    Wptelegram
 * @subpackage Wptelegram/includes
 * @author     Manzoor Wani <@manzoorwanijk>
 */
class Wptelegram_Notification_Handler {

	/**
	 * Settings/Options
	 *
	 * @since  	1.4.0
	 * @access 	private
	 * @var  	string 		$options 	Plugin Options
	 */
	private $options;

	/**
	 * wp_mail arguments
	 *
	 * @since  	1.6.0
	 * @access 	private
	 * @var  	array
	 */
	private $wp_mail_args;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.4.0
	 */
	public function __construct() {
		global $wptelegram_options;
		
		$this->options = $wptelegram_options;
	}

	/**
	 * Filters the wp_mail() arguments
	 *
	 * @since	1.6.0
	 *
	 * @param	array	$args	A compacted array of wp_mail() arguments,
	 * including the "to" email, subject, message, headers, and attachments values.
	 */
	public function handle_wp_mail( $args ) {
		if ( '' == $this->options['telegram']['bot_token'] ) {
			return $args;
		}
		$this->wp_mail_args = $args;
		
		extract( $args ); // 'to', 'subject', 'message', 'headers', 'attachments'
		$emails = array();

		if ( ! apply_filters( 'wptelegram_send_telegram_notification', true, $args ) ) {
			return $args;
		}

		if ( ! is_array( $to ) )
			$to = explode( ',', $to );

		foreach ( (array) $to as $recipient ) {
			// Break $recipient into name and address parts if in the format "Foo <bar@baz.com>"
			if( preg_match( '/(.*)<(.+)>/', $recipient, $matches ) && count( $matches ) == 3 ) {
				$email = $matches[2];
			} else {
				$email = $recipient;
			}

			$chat_id = $this->get_user_chat_id( $email );

			$chat_id = apply_filters( 'wptelegram_user_chat_id', $chat_id, $email, $args );

			if ( $chat_id ) {
				$this->prepare_text( $chat_id );
			} elseif ( $this->is_email_in_watch_list( $email ) && '' != $this->options['notify']['chat_ids'] ) {
					$chat_ids = $this->options['notify']['chat_ids'];
					$this->prepare_text( $chat_ids );
			} else{
				$emails[] = $recipient; // User not having Chat ID
			}
		}

		if ( apply_filters( 'wptelegram_notification_abort_email', false, $args ) ) {
			$to = $emails;
			return compact( 'to', 'subject', 'message', 'headers', 'attachments' );
		}
		return $args;
	}

	/**
	 * prepare the text to be sent to Telegram
	 *
	 * @since	1.4.0
     * @param	array|string	$chat_ids
     * @access	private
	 */
	private function prepare_text( $chat_ids ) {

		extract( $this->wp_mail_args ); // $to, $subject, $message, $headers, $attachments

		$text = convert_html_to_text( $message, true );

		$text = '🔔‌*' . $subject . '‌🔔*' . "\n\n" . $text;

		$hashtag = $this->options['notify']['hashtag'];
		if ( $hashtag ) {
			$text .= "\n\n" . $hashtag;
		}
		$text = apply_filters( 'wptelegram_notification_text', $text, $this->wp_mail_args );

		$method_params = array(
			'sendMessage' => array(
				'text'			=> $text,
				'parse_mode'	=> 'Markdown',
			),
		);
		$this->send_message( $chat_ids, $method_params );
	}

	/**
	 * Send the message
	 *
	 * @since 1.4.0
	 *
     * @param	array|string	$chat_ids
	 * @param 	array			$method_params	Array of methods
	 */
	public function send_message( $chat_ids, $method_params ){

		$bot_token = $this->options['telegram']['bot_token'];
		
		$tg_api = new WPTelegram_Telegram_Api( $bot_token );

		$chat_ids = explode( ',', $chat_ids );

		$chat_ids = (array) apply_filters( 'wptelegram_notification_chat_ids', $chat_ids, $this->wp_mail_args );

		foreach ( $chat_ids as $chat_id ) {
			
			foreach ( $method_params as $method => $params ) {
				$params['chat_id'] = $chat_id;
				
				$response = $tg_api->$method( $params );

				do_action( 'wptelegram_notification_response', $response, $this->wp_mail_args );
			}
		}
	}

	/** 
	 * Get Telegram Chat ID from email address
	 *
	 * return false if the user has not saved the Chat ID
	 * 
	 * @param	string	$email	Email ID of the user
	 * @since	1.6
	 * @return	string|bool
	 */
	private function get_user_chat_id( $email ) {
		$telegram_chat_id = '';
		$user = get_user_by( 'email', $email );
		if ( is_object( $user ) ) {
			$telegram_chat_id = $user->telegram_chat_id;
		}
		return apply_filters( 'wptelegram_user_telegram_chat_id', $telegram_chat_id, $email );
	}

	/**
	 * Check if the recipient of the email is in the watch list
	 *
	 * @since 1.6.0
	 *
	 * @param	string	$email
	 *
     * @return	boolean
     *
     * @access	private
	 */
	private function is_email_in_watch_list( $email ) {
		$watch_emails = $this->options['notify']['watch_emails'];
		if ( 'all' == $watch_emails ) {
			return true;
		} elseif ( '' == $watch_emails ) {
			return false;
		}
		$watch_list = array_map( 'trim', explode( ',', $watch_emails ) );
		$watch_list = array_map( 'strtolower', $watch_list );
		return in_array( strtolower( $email ), $watch_list );
	}
}