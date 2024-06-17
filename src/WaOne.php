<?php

namespace Esoftdream\WaOne;

use Exception;

class WaOne
{
    /**
     * @var string The base URL for the WaOne API.
     */
    protected $url;

    /**
     * @var string The API token for authentication.
     */
    protected $token;

    /**
     * WaOne constructor.
     *
     * @param string $url   The base URL for the WaOne API.
     * @param string $token The API token for authentication.
     */
    public function __construct(string $url, string $token)
    {
        $this->url = $url;
        $this->token = $token;
    }

    /**
     * Sends a message to a receiver via the WaOne API.
     *
     * @param string $message  The message content to be sent.
     * @param string $receiver The phone number of the receiver.
     *
     * @return string The response from the WaOne API.
     *
     * @throws Exception If there is a cURL error during the request.
     */
    public function send(string $message, string $receiver): string
    {
        $request_data = [
            'message_content' => [
                'text' => $message,
            ],
            'message_phonenumber' => $receiver,
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->url . '/api/v1/message/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30, // Set a reasonable timeout
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode($request_data), // Convert array to JSON
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->token,
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            // Handle cURL error
            throw new Exception("cURL Error #:" . $err);
        } else {
            // Return the response from the API
            return $response;
        }
    }
}
