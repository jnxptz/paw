<?php

namespace App\Services;

use Google\Cloud\Dialogflow\V2\Client\SessionsClient;
use Google\Cloud\Dialogflow\V2\DetectIntentResponse;
use Google\Cloud\Dialogflow\V2\QueryInput;
use Google\Cloud\Dialogflow\V2\TextInput;
use Google\Cloud\Dialogflow\V2\QueryResult;

class DialogflowService
{
    protected string $projectId;
    protected string $credentialsPath;

    public function __construct()
    {
        $this->projectId = env('DIALOGFLOW_PROJECT_ID');
        $this->credentialsPath = storage_path('app/dialogflow-key.json'); // correct path
    }

    public function detectIntent(string $sessionId, string $text, string $languageCode = 'en'): QueryResult
    {
        // Initialize SessionsClient with credentials
        $sessionsClient = new SessionsClient([
            'credentials' => $this->credentialsPath,
            'transport' => 'rest', // safer on Windows
        ]);

        try {
            /** @var string $session */
            $session = $sessionsClient->sessionName($this->projectId, $sessionId);

            /** @var TextInput $textInput */
            $textInput = (new TextInput())
                ->setText($text)
                ->setLanguageCode($languageCode);

            /** @var QueryInput $queryInput */
            $queryInput = (new QueryInput())->setText($textInput);

            /** @var DetectIntentResponse $response */
            $response = $sessionsClient->detectIntent($session, $queryInput);

            /** @var QueryResult $result */
            $result = $response->getQueryResult();

            return $result;

        } finally {
            $sessionsClient->close();
        }
    }
}
