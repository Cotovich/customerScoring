<?php

namespace App\Tests\Functional\Service;

use App\Entity\Customer;
use App\Service\ScoringService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ScoringServiceTest extends WebTestCase
{
    public function testScoring(
    ): void {
        $phones = [['+7(902)123-12-12', 10], ['+7(900)123-12-12', 5], ['+7(901)123-12-12', 3], ['+7(999)123-12-12', 1]];
        $mails = [['customer@gmail.com', 10], ['customer@yandex.ru', 8], ['customer@mail.ru', 6], ['customer@yahoo.com', 3]];
        $educations = [[Customer\EducationType::HIGHER->value, 15], [Customer\EducationType::SPECIAL->value, 10], [Customer\EducationType::SECONDARY->value, 5]];
        $pds = [[true, 4], [false, 0]];

        foreach ($phones as $phone) {
            foreach ($mails as $mail) {
                foreach ($educations as $education) {
                    foreach ($pds as $pd) {
                        $this->assertSame(
                            $phone[1] + $mail[1] + $education[1] + $pd[1],
                            ScoringService::makeScoring(
                                $phone[0],
                                $mail[0],
                                $education[0],
                                $pd[0],
                            )
                        );
                    }
                }
            }
        }
    }
}
