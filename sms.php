<?php

namespace sms;

use Exception;

class sms
{
    private const MSG_ERROR_UNDEFINED = 'Undefined';
    private const MSG_ERROR_400 = 'Un des paramètres obligatoires est manquant.';
    private const MSG_ERROR_402 = 'Trop de SMS ont été envoyés en trop peu de temps.';
    private const MSG_ERROR_403 = 'Le service n’est pas activé sur l’espace abonné, ou login / clé incorrect.';
    private const MSG_ERROR_500 = 'Erreur côté serveur. Veuillez réessayez ultérieurement.';

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
    }

    /**
     * @return string
     */
    public function getUrlApi(): string
    {
        return $this->urlApi;
    }

    /**
     * @param $urlApi
     */
    public function setUrlApi($urlApi)
    {
        $this->urlApi = $urlApi;
    }

    /**
     * @param $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return null|string
     */
    public function getMessage(): ?string
    {
        return $this->message;
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
        $this->curlError($httpCode);

        $this->reset();
    }

    /**
     * @param string $url
     * @return int
     */
    private function curlInit(string $url): int
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url
        ));

        return curl_getinfo($curl, CURLINFO_HTTP_CODE);
    }

    /**
     * @param int $httpCode
     * @throws Exception
     */
    private function curlError(int $httpCode)
    {
        if (0 < $httpCode) {
            $errorMsg = self::MSG_ERROR_UNDEFINED;

            switch ($httpCode) {
                case 400:
                    $errorMsg = self::MSG_ERROR_400;
                    break;
                case 402:
                    $errorMsg = self::MSG_ERROR_402;
                    break;
                case 403:
                    $errorMsg = self::MSG_ERROR_403;
                    break;
                case 500:
                    $errorMsg = self::MSG_ERROR_500;
                    break;
            }

            throw new Exception('Code : '.$errorMsg.' - '.$httpCode);
        }
    }
}
