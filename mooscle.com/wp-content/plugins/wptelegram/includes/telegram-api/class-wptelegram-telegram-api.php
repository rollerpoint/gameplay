<?php

/**
 * Class WPTelegram_Telegram_Api.
 *
 * 
 */
class WPTelegram_Telegram_Api
{

    /**
     * @var string Telegram Bot API Access Token.
     */
    private $bot_token;

    /**
     * @var WPTelegram_Telegram_Client The Telegram client
     */
    protected $client;

    /**
     * @since  1.5.4
     *
     * @var WPTelegram_Telegram_Request The original request
     */
    protected $request;

    /**
     * @var WPTelegram_Telegram_Response|null Stores the last request made to Telegram Bot API.
     */
    protected $last_response;

    /**
     * Instantiates a new Telegram super-class object.
     *
     *
     * @param string    $bot_token   The Telegram Bot API Access Token.
     *
     */
    public function __construct( $bot_token = null ) {
        $this->bot_token = $bot_token;

        $this->client = new WPTelegram_Telegram_Client();
    }
    /**
     * Magic Method to handle all API calls.
     *
     * @param $method
     * @param $args
     *
     * @return mixed|string
     */
    public function __call( $method, $args ) {
        if ( ! empty( $args ) ) {
            $args = $args[0];
        }
        return $this->sendRequest( $method, $args );
    }

    /**
     * Set the bot token for this request.
     *
     * @since  1.5.0
     *
     * @param string    $bot_token  The Telegram Bot API Access Token.
     *
     */
    public function set_bot_token( $bot_token ) {
        $this->bot_token = $bot_token;
    }

    /**
     * Returns Telegram Bot API Access Token.
     *
     * @return string
     */
    public function get_bot_token() {
        return $this->bot_token;
    }

    /**
     *
     * @return WPTelegram_Telegram_Client
     */
    public function get_client() {
        return $this->client;
    }

    /**
     * Return the original request 
     *
     * @since   1.5.4
     *
     * @return WPTelegram_Telegram_Request
     */
    public function get_request() {
        return $this->request;
    }

    /**
     * Returns the last response returned from API request.
     *
     * @return WPTelegram_Telegram_Response
     */
    public function get_last_response() {
        return $this->last_response;
    }

    /**
     * Send Message
     *
     * @since  1.0.0
     */
    public function sendMessage( $params ){
        
        // break text after every 4096th character and preserve words
        preg_match_all( '/.{1,4095}(?:\s|$)/su', $params['text'], $matches );
        foreach ( $matches[0] as $text ) {
            $params['text'] = $text;
            $res = $this->sendRequest( __FUNCTION__, $params );
            $params['reply_to_message_id'] = null;
        }
        return $res;
    }

    /**
     * sendRequest
     *
     * @since  1.0.0
     */
    private function sendRequest( $endpoint, $params ){
        
        if ( null == $this->get_bot_token() ) {
            return new WP_Error( 'invalid_bot_token', __( 'Bot Token is required to make a request', 'wptelegram' ) );
        }

        $this->request = $this->request( $endpoint, $params );

        $this->last_response = $this->get_client()->sendRequest( $this->get_request() );
        
        do_action( 'wptelegram_api_debug', $this->last_response, $this );

        return $this->last_response;
    }

    /**
     * Instantiates a new WPTelegram_Telegram_Request
     *
     * @param string $endpoint
     * @param array  $params
     *
     * @return WPTelegram_Telegram_Request
     */
    private function request( $endpoint, array $params = array() ) {
        return new WPTelegram_Telegram_Request(
            $this->get_bot_token(),
            $endpoint,
            $params
        );
    }
}
