<?php

/**
 * Class WPTelegram_Telegram_Client.
 *
 * 
 */
class WPTelegram_Telegram_Client
{
    /**
     * @const string Telegram Bot API URL.
     *
     * @since  1.5.0
     */
    const BASE_URL = 'https://api.telegram.org/bot';

    /**
     * Returns the base URL of the Bot API.
     *
     * @since  1.5.0
     *
     * @return string
     */
    public function get_base_url() {
        return self::BASE_URL;
    }

    /**
     * Prepares the API request for sending to the client
     *
     * @since  1.5.0
     *
     * @param WPTelegram_Telegram_Request $request
     *
     * @return array
     */
    public function prepare_request( $request ) {
        $url = $this->get_base_url() . $request->get_bot_token() . '/' . $request->get_endpoint();

        return array(
            $url,
            $request->get_params(),
        );
    }

    /**
     * Send an API request and process the result.
     *
     * @since  1.5.0
     *
     * @param WPTelegram_Telegram_Request $request
     *
     * @return WP_Error|WPTelegram_Telegram_Response
     */
    public function sendRequest( $request ) {
        list( $url, $params ) = $this->prepare_request( $request );

        $args = array(
            'body' => $params,
        );
        $args = apply_filters( 'wptelegram_remote_post_args', $args );
        $raw_response = wp_remote_post( $url, $args );

        if ( ! is_wp_error( $raw_response ) ) {
            return $this->get_response( $request, $raw_response );
        }

        return $raw_response;
    }

    /**
     * Creates response object.
     *
     * @since  1.5.0
     *
     * @param WPTelegram_Telegram_Request   $request
     * @param array                         $raw_response
     *
     * @return WPTelegram_Telegram_Response
     */
    protected function get_response( $request, $raw_response ) {
        return new WPTelegram_Telegram_Response( $request, $raw_response );
    }
}
