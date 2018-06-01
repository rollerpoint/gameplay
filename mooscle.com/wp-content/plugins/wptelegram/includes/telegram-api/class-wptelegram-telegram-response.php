<?php

/**
 * Class WPTelegram_Telegram_Response.
 *
 * 
 */
class WPTelegram_Telegram_Response
{
    /**
     * @since  1.5.0
     *
     * @var null|int The HTTP response code from API.
     */
    protected $response_code;
    /**
     * @since  1.5.0
     *
     * @var null|int The HTTP response message from API.
     */
    protected $response_message;

    /**
     * @since  1.5.0
     *
     * @var array The headers returned from API request.
     */
    protected $headers = null;

    /**
     * @since  1.5.0
     *
     * @var string The raw body of the response from API request.
     */
    protected $body = null;

    /**
     * @since  1.5.0
     *
     * @var array The decoded body of the API response.
     */
    protected $decoded_body = array();

    /**
     * @since  1.5.0
     *
     * @var string API Endpoint used to make the request.
     */
    protected $endpoint;

    /**
     * @since  1.5.0
     *
     * @var WPTelegram_Telegram_Request The original request that returned this response.
     */
    protected $request;

    /**
     * @since   1.5.0
     *
     * @var array   The original response from wp_remote_post
     */
    protected $raw_response;

    /**
     * Gets the relevant data from the client.
     * @since   1.5.0
     *
     * @param WPTelegram_Telegram_Request   $request
     * @param array                         $raw_response
     */
    public function __construct( $request, $raw_response ) {
        
        $this->set_properties( $raw_response );

        $this->decode_body();

        $this->request = $request;
        $this->raw_response = $raw_response;
        $this->endpoint = (string) $request->get_endpoint();
    }

    /**
     * Sets the class properties
     * @since   1.5.0
     *
     */
    public function set_properties( $raw_response ) {
        $properties = array(
            'response_code',
            'response_message',
            'body',
            'headers',
        );
        foreach ( $properties as $property ) {
            $this->$property = call_user_func( 'wp_remote_retrieve_' . $property, $raw_response );
        }
    }

    /**
     * Return the original request that returned this response.
     * @since   1.5.0
     *
     * @return WPTelegram_Telegram_Request
     */
    public function get_request() {
        return $this->request;
    }

    /**
     * Gets the original HTTP response.
     * @since   1.5.0
     *
     * @return array
     */
    public function get_raw_response() {
        return $this->raw_response;
    }

    /**
     * Gets the HTTP response code.
     * @since   1.5.0
     *
     * @return null|int
     */
    public function get_response_code() {
        return $this->response_code;
    }

    /**
     * Gets the HTTP response message.
     * @since   1.5.0
     *
     * @return null|string
     */
    public function get_response_message() {
        return $this->response_message;
    }

    /**
     * Gets the Request Endpoint used to get the response.
     * @since   1.5.0
     *
     * @return string
     */
    public function get_endpoint() {
        return $this->endpoint;
    }

    /**
     * Return the bot access token that was used for this request.
     * @since   1.5.0
     *
     * @return string|null
     */
    public function get_bot_token() {
        return $this->request->get_bot_token();
    }

    /**
     * Return the HTTP headers for this response.
     * @since   1.5.0
     *
     * @return array
     */
    public function get_headers() {
        return $this->headers;
    }

    /**
     * Return the raw body response.
     * @since   1.5.0
     *
     * @return string
     */
    public function get_body() {
        return $this->body;
    }

    /**
     * Return the decoded body response.
     * @since   1.5.0
     *
     * @return array
     */
    public function get_decoded_body() {
        return $this->decoded_body;
    }

    /**
     * Helper function to return the payload of a successful response.
     * @since   1.5.0
     *
     * @return mixed
     */
    public function get_result() {
        return $this->decoded_body['result'];
    }

    /**
     * Converts raw API response to proper decoded response.
     * @since   1.5.0
     */
    public function decode_body() {
        $this->decoded_body = json_decode( $this->body, true );
    }
}
