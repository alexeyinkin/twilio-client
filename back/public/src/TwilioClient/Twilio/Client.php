<?php
declare(strict_types=1);

namespace TwilioClient\Twilio;

use SimpleXMLElement;

class Client
{
    private const BASE_URL = 'https://api.twilio.com/2010-04-01';

    private Credentials $credentials;
    private string $statusCallback;

    public function __construct(Credentials $credentials)
    {
        $this->credentials = $credentials;
    }

    public function setStatusCallback(string $statusCallback): void
    {
        $this->statusCallback = $statusCallback;
    }

    public function call(string $fromNumber, string $toNumber, string $twiml): SimpleXMLElement
    {
        $postData = [
            'To' => $toNumber,
            'From' => $fromNumber,
            'StatusCallback' => $this->statusCallback,
            'StatusCallbackEvent' => TwilioCallEventEnum::ALL,
            'Twiml' => $twiml,
        ];

        return $this->callMethod('Accounts', 'Calls', $postData);
    }

    private function callMethod(string $class, string $resource, array $postData): SimpleXMLElement
    {
        $url = sprintf(
            '%s/%s/%s/%s',
            self::BASE_URL,
            $class,
            $this->credentials->getAccountSid(),
            $resource
        );

        $ch = curl_init($url);

        $curlOptions = [
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPAUTH        => CURLAUTH_BASIC,
            CURLOPT_USERPWD         => $this->credentials->getApiKeySid() . ':' . $this->credentials->getApiKeyToken(),
        ];

        if ($postData)
        {
            $curlOptions[CURLOPT_POST]          = true;
            $curlOptions[CURLOPT_POSTFIELDS]    = self::postDataToRaw($postData);
        }

        curl_setopt_array($ch, $curlOptions);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

        if ($response === false || intdiv($httpCode, 100) !== 2) {
            throw new \Exception($httpCode . ' ' . $response);
        }

        return new SimpleXMLElement($response);
    }

    /**
     * We need to pass an array for StatusCallbackEvents but Twilio does not handle key[]=a&key[]=b
     * and only supports key=a&key=b. This requires manual body encoding. This method does this.
     *
     * @param mixed[] $postData
     * @return string
     */
    private static function postDataToRaw(array $postData): string
    {
        $parts = [];

        foreach ($postData as $key => $value) {
            switch (true) {
                case is_scalar($value):
                    $parts[] = urlencode($key) . '=' . urlencode((string) $value);
                    break;
                case is_array($value):
                    foreach ($value as $arrayElementValue) {
                        $parts[] = urlencode($key) . '=' . urlencode((string) $arrayElementValue);
                    }
                    break;
            }
        }

        return implode('&', $parts);
    }
}
