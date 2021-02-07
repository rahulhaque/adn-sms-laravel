<?php


namespace RahulHaque\AdnSms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \RahulHaque\AdnSms\AdnSms key(string $key)
 * @method static \RahulHaque\AdnSms\AdnSms secret(string $secret)
 * @method static \RahulHaque\AdnSms\AdnSms to(string $recipient)
 * @method static \RahulHaque\AdnSms\AdnSms otp(string $recipient)
 * @method static \RahulHaque\AdnSms\AdnSms bulk(array $recipients)
 * @method static \RahulHaque\AdnSms\AdnSms message(string $message)
 * @method static \RahulHaque\AdnSms\AdnSms format(string $format)
 * @method static \RahulHaque\AdnSms\AdnSms campaignTitle(string $campaignTitle)
 * @method static \RahulHaque\AdnSms\AdnSms checkBalance()
 * @method static \RahulHaque\AdnSms\AdnSms checkSmsStatus(string $smsUid)
 * @method static \RahulHaque\AdnSms\AdnSms checkCampaignStatus(string $campaignUid)
 * @method static \RahulHaque\AdnSms\AdnSms send()
 * @method static \RahulHaque\AdnSms\AdnSms queue($callback = null)
 *
 * @see \RahulHaque\AdnSms\AdnSms
 */
class AdnSms extends Facade
{
    protected static function getFacadeAccessor()
    {
        self::clearResolvedInstance('adn-sms');

        return 'adn-sms';
    }
}
