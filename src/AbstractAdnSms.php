<?php

namespace RahulHaque\AdnSms;

use Illuminate\Support\Facades\Http;
use RahulHaque\AdnSms\Exceptions\InvalidMessageFormatException;
use RahulHaque\AdnSms\Exceptions\InvalidRequestTypeException;
use RahulHaque\AdnSms\Exceptions\MissingRequiredMethodCallException;

abstract class AbstractAdnSms
{
    protected $isEnabled;
    private $apiKey;
    private $apiSecret;
    protected $recipient;
    protected $message;
    protected $format;
    protected $campaignTitle;
    protected $requestType;
    protected $isPromotional;
    protected $apiUrl;

    private $allowedRequestTypes = ['SINGLE_SMS', 'OTP', 'GENERAL_CAMPAIGN', 'MULTIBODY_CAMPAIGN'];
    private $allowedMessageFormats = ['TEXT', 'UNICODE'];

    /**
     * @return bool
     */
    private function getIsEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * @param bool $isEnabled
     */
    protected function setIsEnabled(string $isEnabled)
    {
        $this->isEnabled = $isEnabled;
    }

    /**
     * @return string
     */
    private function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    protected function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    private function getApiSecret()
    {
        return $this->apiSecret;
    }

    /**
     * @param string $apiSecret
     */
    protected function setApiSecret(string $apiSecret)
    {
        $this->apiSecret = $apiSecret;
    }

    /**
     * @return bool
     */
    public function getIsPromotional()
    {
        return $this->isPromotional;
    }

    /**
     * @param bool $isPromotional
     */
    public function setIsPromotional(bool $isPromotional): void
    {
        $this->isPromotional = $isPromotional;
    }

    /**
     * @return string
     */
    protected function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @param string $url
     */
    protected function setApiUrl(string $url)
    {
        $this->apiUrl = $url;
    }

    /**
     * @return string
     * @throws \Throwable
     */
    protected function getRequestType()
    {
        throw_if((
                $this->requestType == 'GENERAL_CAMPAIGN' ||
                $this->requestType == 'MULTIBODY_CAMPAIGN'
            )
            && empty($this->campaignTitle),
            new MissingRequiredMethodCallException('Missing required method call campaignTitle()')
        );

        return $this->requestType;
    }

    /**
     * @param string $requestType
     * @throws \Throwable
     */
    protected function setRequestType(string $requestType)
    {
        throw_if(!in_array(strtoupper($requestType), $this->allowedRequestTypes), new InvalidRequestTypeException('Invalid request type. Must be one of ' . implode(', ', $this->allowedRequestTypes) . '.'));

        $this->requestType = $requestType;
    }

    /**
     * @return string
     * @throws \Throwable
     */
    protected function getFormat()
    {
        throw_if(empty($this->format), new MissingRequiredMethodCallException('Missing required method call format()'));

        return $this->format;
    }

    /**
     * @param string $format
     * @throws \Throwable
     */
    protected function setFormat(string $format)
    {
        throw_if(!in_array(strtoupper($format), $this->allowedMessageFormats), new InvalidMessageFormatException('Invalid message format. Must be one of ' . implode(', ', $this->allowedMessageFormats) . '.'));

        $this->format = $format;
    }

    /**
     * @return string
     * @throws \Throwable
     */
    protected function getRecipient()
    {
        throw_if(empty($this->recipient), new MissingRequiredMethodCallException('Recipient list is empty.'));

        return $this->recipient;
    }

    /**
     * @param string | array $recipient
     */
    protected function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @return string
     * @throws \Throwable
     */
    protected function getMessageBody()
    {
        throw_if(empty($this->message), new MissingRequiredMethodCallException('Missing required method call message()'));

        return $this->message;
    }

    /**
     * @param string $message
     */
    protected function setMessageBody(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    protected function getCampaignTitle()
    {
        return $this->campaignTitle;
    }

    /**
     * @param $campaignTitle
     */
    protected function setCampaignTitle(string $campaignTitle)
    {
        $this->campaignTitle = $campaignTitle;
    }

    /**
     * AbstractAdnSms constructor.
     * @throws \Throwable
     */
    public function __construct()
    {
        $this->setIsEnabled(config('adn-sms.enabled'));
        $this->setApiKey(config('adn-sms.api_key'));
        $this->setApiSecret(config('adn-sms.api_secret'));
        $this->setFormat(config('adn-sms.message_format'));
    }

    /**
     * Make request and return response
     *
     * @param array $data
     * @return \Illuminate\Http\Client\Response
     * @throws \Throwable
     */
    protected function callToApi(array $data = [])
    {
        if (!$this->isEnabled) Http::fake();

        $request = array_merge($data, [
            'api_key' => $this->getApiKey(),
            'api_secret' => $this->getApiSecret(),
        ]);

        return Http::baseUrl(config('adn-sms.domain'))->acceptJson()->post($this->getApiUrl(), $request);
    }
}
