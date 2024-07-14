<?php

namespace Heyharpreetsingh\FCM;

use Exception;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\HttpHandler\HttpHandlerFactory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class FCM
{
    /**
     *  @var string
     *  Scope for the APIs you would like to access 
     **/
    private string $scope = "https://www.googleapis.com/auth/firebase.messaging";

    /**
     *  @var array
     *  Service Account Credential JSON decoded.
     **/
    private array $service_account_credentials;

    function __construct()
    {
        // Set Service Account credentials
        $this->service_account_credentials = json_decode(
            File::get(
                storage_path(env('FCM_GOOGLE_APPLICATION_CREDENTIALS', 'serviceAccountKey.json'))
            ),
            true
        );

        if(empty($this->service_account_credentials["project_id"])) {
            throw new Exception("Invalid file.");
        }
    }

    /**
     * Send a notification to user phone.
     * 
     * @param array $payload FCM Token to send to user phone.
     * References
     *      - https://firebase.google.com/docs/reference/fcm/rest/v1/projects.messages/send
     *      - https://firebase.google.com/docs/reference/fcm/rest/v1/projects.messages#Message
     * @example [
     *    "message" => [ 
     *      "token" => "...", // device token sent a notification to user phone.
     *      "notification" => [ 
     *          "title" => "Breaking News", 
     *          "body" => "New news story available." 
     *      ], 
     *      "data" => []
     *    ]
     * ]
     * 
     * @return array
     */
    public function send(array $payload): array
    {
        $curl = curl_init();

        $endpoint = "https://fcm.googleapis.com/v1/projects/" . $this->service_account_credentials["project_id"] . "/messages:send";

        curl_setopt_array($curl, [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->getAccessToken(),
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($status !== Response::HTTP_OK) {
            // Failed send
            Log::error("FCM notification failed: $response");
        }

        return [
            "status" => $status,
            "data" => $response
        ];
    }

    /**
     * Get the access token to send a notification.
     * 
     * @return string
     */
    private function getAccessToken(): string
    {
        // ServiceAccountCredentials supports authorization using a Google service account
        $credential = new ServiceAccountCredentials(
            $this->scope,
            $this->service_account_credentials
        );

        // Fetch the auth token.
        $token = $credential->fetchAuthToken(HttpHandlerFactory::build());

        return $token['access_token'];
    }
}
