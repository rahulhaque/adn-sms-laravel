<?php

namespace RahulHaque\AdnSms;

class AdnSms extends AbstractAdnSms
{
    /**
     * @param string $key
     * @return $this
     */
    public function key(string $key)
    {
        $this->setApiKey($key);

        return $this;
    }

    /**
     * @param string $secret
     * @return $this
     */
    public function secret(string $secret)
    {
        $this->setApiSecret($secret);

        return $this;
    }

    /**
     * @param string $recipient
     * @return $this
     * @throws \Throwable
     */
    public function to(string $recipient)
    {
        $this->setApiUrl(config('adn-sms.api_urls.send_sms'));

        $this->setRequestType('SINGLE_SMS');

        $this->setRecipient($recipient);

        return $this;
    }

    /**
     * @param string $recipient
     * @return $this
     * @throws \Throwable
     */
    public function otp(string $recipient)
    {
        $this->setApiUrl(config('adn-sms.api_urls.send_sms'));

        $this->setRequestType('OTP');

        $this->setRecipient($recipient);

        return $this;
    }

    /**
     * @param array $recipients
     * @return $this
     * @throws \Throwable
     */
    public function bulk(array $recipients)
    {
        $this->setApiUrl(config('adn-sms.api_urls.send_sms'));

        $this->setRequestType('GENERAL_CAMPAIGN');

        $this->setRecipient(implode(',', $recipients));

        $this->setIsPromotional($this->getFormat() == 'TEXT');

        return $this;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function message(string $message)
    {
        $this->setMessageBody($message);

        return $this;
    }

    /**
     * @param string $format
     * @return $this
     * @throws \Throwable
     */
    public function format(string $format)
    {
        $this->setFormat($format);

        return $this;
    }

    /**
     * @param string $campaignTitle
     * @return $this
     */
    public function campaignTitle(string $campaignTitle)
    {
        $this->setCampaignTitle($campaignTitle);

        return $this;
    }

    /**
     * @return \Illuminate\Http\Client\Response
     * @throws \Throwable
     */
    public function checkBalance()
    {
        $this->setApiUrl(config('adn-sms.api_urls.check_balance'));

        return $this->callToApi();
    }

    /**
     * @param string $smsUid
     * @return \Illuminate\Http\Client\Response
     * @throws \Throwable
     */
    public function checkSmsStatus(string $smsUid)
    {
        $this->setApiUrl(config('adn-sms.api_urls.check_sms_status'));

        $data = [
            'sms_uid' => $smsUid,
        ];

        return $this->callToApi($data);
    }

    /**
     * @param string $campaignUid
     * @return \Illuminate\Http\Client\Response
     * @throws \Throwable
     */
    public function checkCampaignStatus(string $campaignUid)
    {
        $this->setApiUrl(config('adn-sms.api_urls.check_campaign_status'));

        $data = [
            'campaign_uid' => $campaignUid,
        ];

        return $this->callToApi($data);
    }

    /**
     * @return \Illuminate\Http\Client\Response
     * @throws \Throwable
     */
    public function send()
    {
        $data = [
            'mobile' => $this->getRecipient(),
            'message_body' => $this->getMessageBody(),
            'request_type' => $this->getRequestType(),
            'message_type' => $this->getFormat(),
        ];

        if ($this->getCampaignTitle()) {
            $data['campaign_title'] = $this->getCampaignTitle();
        }

        if ($this->getIsPromotional()) {
            $data['isPromotional'] = $this->getIsPromotional();
        }

        return $this->callToApi($data);
    }

    /**
     * @param callable $callback
     */
    public function queue($callback = null)
    {
        dispatch(function () use ($callback) {
            $response = $this->send();
            if (is_callable($callback)) call_user_func($callback, $response);
        });
    }

}
