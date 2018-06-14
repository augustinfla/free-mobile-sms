<?php
declare(strict_types=1);

namespace FreeMobile\Tests;

use FreeMobile\Sms;
use PHPUnit\Framework\TestCase;

final class SmsTest extends TestCase
{
    private const API_USER = '';
    private const API_KEY = '';
    private const MSG = 'Message test with special char é$⁾é"à_çéà("_èrè_ç-"r';

    /**
     * @throws \Exception
     */
    public function testSendSms200()
    {
        $sms = new Sms(self::API_USER, self::API_KEY);
        $sms->setMessage(self::MSG);

        $this->assertEquals($sms->send(), 200);
    }

    /**
     * @throws \Exception
     */
    public function testSendSms400()
    {
        $sms = new Sms(self::API_USER, self::API_KEY);

        try {
            $sms->send();
        } catch (\Exception $e) {
            $this->assertEquals($e->getCode(), Sms::HTTP_CODE_400);
            $this->assertEquals($e->getMessage(), Sms::HTTP_MSG_400);
        }
    }

    /**
     * @throws \Exception
     */
    public function testSendSms403()
    {
        $sms = new Sms('12345', '1234');
        $sms->setMessage(self::MSG);

        try {
            $sms->send();
        } catch (\Exception $e) {
            $this->assertEquals($e->getCode(), Sms::HTTP_CODE_403);
            $this->assertEquals($e->getMessage(), Sms::HTTP_MSG_403);
        }
    }
}
