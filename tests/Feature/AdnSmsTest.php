<?php

namespace RahulHaque\AdnSms\Tests\Feature;

use Orchestra\Testbench\TestCase;
use RahulHaque\AdnSms\AdnSmsServiceProvider;

class AdnSmsTest extends TestCase
{
    public $recipient = ''; // Set recipient for the tests

    protected function getPackageProviders($app)
    {
        return [AdnSmsServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'AdnSms' => \RahulHaque\AdnSms\Facades\AdnSms::class,
        ];
    }

    /** @test */
    public function can_send_sms()
    {
        $response = \AdnSms::to($this->recipient)->message('Send SMS Test.')->send();

        $this->assertTrue($response['api_response_code'] == 200);
    }

    /** @test */
    public function can_send_otp_sms()
    {
        $response = \AdnSms::otp($this->recipient)->message('Send OTP SMS Test.')->send();

        $this->assertTrue($response['api_response_code'] == 200);
    }

    /** @test */
    public function can_send_bulk_sms()
    {
        $response = \AdnSms::bulk([$this->recipient])->campaignTitle('Bulk SMS Test')->message('Send Bulk SMS Test.')->send();

        $this->assertTrue($response['api_response_code'] == 200);
    }

    /** @test */
    public function can_get_balance()
    {
        $response = \AdnSms::checkBalance();

        $this->assertTrue($response['api_response_code'] == 200);
    }

    /** @test */
    public function can_check_sms_status()
    {
        $response = \AdnSms::checkSmsStatus('XXXXX');

        $this->assertTrue($response['api_response_code'] == 200);
    }

    /** @test */
    public function can_check_campaign_status()
    {
        $response = \AdnSms::checkSmsStatus('XXXXX');

        $this->assertTrue($response['api_response_code'] == 200);
    }
}
