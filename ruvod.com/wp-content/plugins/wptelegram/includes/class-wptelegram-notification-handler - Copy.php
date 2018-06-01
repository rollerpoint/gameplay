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
		$this->wp_mail_args = $args;
		
		$bot_token = $this->options['telegram']['bot_token'];
		$chat_ids = $this->options['notify']['chat_ids'];
		/**
	     * Decide whether to send notification or not
	     *
	     * @since	1.4.0
	     * @param	bool	$send
	     * @param	array	$args	A compacted array of wp_mail() arguments, including the "to" email,
	     *                    subject, message, headers, and attachments values.
	     */
		$send = apply_filters( 'wptelegram_send_notification', true, $args );

		if ( ! ( $bot_token && $chat_ids ) ) {
			$send = false;
		}

		if ( ! ( $send && $this->is_watched_recipient( $args['to'] ) ) ) {
			$send = false;
		}
		if ( $send ) {
			$this->prepare_text();
		}
		return $args;
	}

	/**
	 * prepare the text to be sent to Telegram
	 *
	 * @since 1.4.0
     *
     * @access private
	 */
	private function prepare_text() {

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
		$this->send( $method_params );
	}

	/**
	 * Send the message
	 *
	 * @since 1.4.0
	 *
	 * @param 	array	$method_params	Array of methods
	 */
	public function send( $method_params ){

		$bot_token = $this->options['telegram']['bot_token'];
		$chat_ids = $this->options['notify']['chat_ids'];
		
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
	 * Check if the recipient of the email is watched
	 *
	 * @since 1.4.0
	 *
	 * @param	string|array	$to
	 *
     * @return	boolean
     *
     * @access	private
	 */
	private function is_watched_recipient( $to ) {
		$watch_emails = $this->options['notify']['watch_emails'];

		if ( 'all' == $watch_emails ) {
			return true;
		} elseif ( '' == $watch_emails ) {
			return false;
		}

		// Get the destination address
		if ( ! is_array( $to ) ){
			$to = explode( ',', $to );
		}

		foreach ( (array) $to as $recipient ) {
			// Break $recipient into name and address parts if in the format "Foo <bar@baz.com>"
			if( preg_match( '/(.*)<(.+)>/', $recipient, $matches ) ) {
				if ( count( $matches ) == 3 ) {
					$recipient = trim( $matches[2] );
				}
			}
			if ( $this->is_in_watch_list( $recipient, $watch_emails ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check if the recipient of the email is in the watch list
	 *
	 * @since 1.6.0
	 *
	 * @param	string	$watch_list
	 * @param	string	$recipient
	 *
     * @return	boolean
     *
     * @access	private
	 */
	private function is_in_watch_list( $recipient, $watch_list ) {
		$watch_list = array_map( 'trim', explode( ',', $watch_list ) );
		$watch_list = array_map( 'strtolower', $watch_list );
		return in_array( strtolower( $recipient ), $watch_list );
	}
}