<?php

namespace Xanax\Plugin;

use Xanax\Classes\ClientURL;
use Xanax\Classes\Data\JSONHandler;

class NaverPapago
{
    private $clientId;

    private $clientSecret;

    private $requestUrl = 'https://openapi.naver.com/v1/papago/n2mt';

    public function __construct($clientId, $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function translate($text, $source, $target)
    {
        $headers = array(
            sprintf("X-Naver-Client-Id: %s", $this->clientId),
            sprintf("X-Naver-Client-Secret: %s", $this->clientSecret)
        );

        $text = urlencode($text);
        $postData = "source=${source}&target=${target}&text=${text}";

        $cURL = new ClientURL();
        $cURL->option->setURL($this->requestUrl)
            ->setPostMethod(true)
            ->setHeaders($headers)
            ->setReturnTransfer(true)
            ->setPostField($postData)
            ->setAutoReferer(true)
            ->setReturnHeader(false)
            ->disableCache(true);

        $result = $cURL->execute();

        if (JSONHandler::isJSON($result->__toString())) {
            $json = JSONHandler::decode($result);

            if (isset($json->message->result->translatedText)) {
                return $json->message->result->translatedText;
            }
        }

        return $result;
    }
}
