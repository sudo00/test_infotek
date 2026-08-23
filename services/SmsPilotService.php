<?php

declare(strict_types=1);

namespace app\services;

use Yii;
use yii\base\Component;

class SmsPilotService extends Component
{
    public string $apiKey = 'эмулятор';
    public string $sender = 'INFORM';
    public string $apiUrl = 'https://smspilot.ru/api.php';

    public function send(string $phone, string $message): bool
    {
        $response = $this->request([
            'send' => $message,
            'to' => $phone,
            'from' => $this->sender,
            'format' => 'json',
        ]);

        if ($response === null) {
            return false;
        }

        if (isset($response['error'])) {
            Yii::warning('SMS Pilot error: ' . json_encode($response, JSON_UNESCAPED_UNICODE), __METHOD__);

            return false;
        }

        return true;
    }

    private function request(array $params): ?array
    {
        $params['apikey'] = $this->apiKey;
        $url = $this->apiUrl . '?' . http_build_query($params);

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 10,
            ],
        ]);

        $body = @file_get_contents($url, false, $context);
        if ($body === false) {
            Yii::error('SMS Pilot request failed', __METHOD__);

            return null;
        }

        $data = json_decode($body, true);

        return is_array($data) ? $data : null;
    }
}
