<?php

namespace FreeMobile;

use Exception;

class Sms
{
    public const HTTP_MSG_UNDEFINED = 'Undefined';
    public const HTTP_MSG_200 = 'Le SMS a été envoyé sur votre mobile.';
    public const HTTP_MSG_400 = 'Un des paramètres obligatoires est manquant.';
    public const HTTP_MSG_402 = 'Trop de SMS ont été envoyés en trop peu de temps.';
    public const HTTP_MSG_403 = 'Le service n\'est pas activé sur l\'espace abonné, ou login / clé incorrect.';
    public const HTTP_MSG_500 = 'Erreur côté serveur. Veuillez réessayer ultérieurement.';

    public const HTTP_CODE_200 = 200;
    public const HTTP_CODE_400 = 400;
    public const HTTP_CODE_402 = 402;
    public const HTTP_CODE_403 = 403;
    public const HTTP_CODE_500 = 500;

    private $apiUser;
    private $apiKey;
    private $message;
    private $urlApi = 'https://smsapi.free-mobile.fr/';

    /**
     * @param string $apiUser
     * @param string $apiKey
     */
    public  function __construct(string $apiUser, string $apiKey)
    {
        $this->apiUser = $apiUser;
        $this->apiKey = $apiKey;

        $this->reset();
    }

    /**
     * @return string
     */
    public function getUrlApi(): string
    {
        return $this->urlApi;
    }

    /**
     * @param string $urlApi
     */
    public function setUrlApi(string $urlApi): void
    {
        $this->urlApi = $urlApi;
    }

    /**
     * @param $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return null|string
     */
    public function getMessage(): ?string
    {
        return $this->cleanMessage($this->message);
    }

    public function reset()
    {
        $this->message = '';
    }

    /**
     * @throws Exception
     */
    public function send()
    {
        $url = $this->getUrlApi().'sendmsg?user='.$this->apiUser.'&pass='.$this->apiKey.'&msg='.$this->getMessage();

        $httpCode = $this->curlInit($url);
        return $this->curlError($httpCode);
    }

    /**
     * @param string $url
     * @return int
     */
    private function curlInit(string $url): int
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return $httpCode;
    }

    /**
     * @param $httpCode
     * @return int
     * @throws Exception
     */
    private function curlError($httpCode)
    {
        if (self::HTTP_CODE_200 !== $httpCode) {
            $errorMsg = self::HTTP_MSG_UNDEFINED;

            switch ($httpCode) {
                case self::HTTP_CODE_200:
                    $errorMsg = self::HTTP_MSG_200;
                    break;
                case self::HTTP_CODE_400:
                    $errorMsg = self::HTTP_MSG_400;
                    break;
                case self::HTTP_CODE_402:
                    $errorMsg = self::HTTP_MSG_402;
                    break;
                case self::HTTP_CODE_403:
                    $errorMsg = self::HTTP_MSG_403;
                    break;
                case self::HTTP_CODE_500:
                    $errorMsg = self::HTTP_MSG_500;
                    break;
            }

            throw new \Exception($errorMsg, $httpCode);
        }

        return $httpCode;
    }

    /**
     * @param string $message
     * @return string
     */
    private function cleanMessage(string $message): string
    {
        return urlencode(trim($message));
    }
}
