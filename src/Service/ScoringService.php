<?php

namespace App\Service;

use App\Entity\Customer\EducationType;

final class ScoringService
{
    public static function makeScoring(string $phone, string $mail, string $educationType, bool $pdProcessingPermission): int
    {
        $score = 0;

        $score += ScoringService::scorePhoneOperator($phone);

        $score += ScoringService::scoreMailOperator($mail);

        $score += ScoringService::scoreEducation($educationType);

        $score += ScoringService::scorePdProcessingPermission($pdProcessingPermission);

        return $score;
    }

    public static function scorePhoneOperator(string $phone): int
    {
        return match (mb_substr($phone, 3, 3)) {
            '902' => 10, // Megafon
            '900' => 5, // Beeline
            '901' => 3, // MTS
            default => 1,
        };
    }

    public static function scoreMailOperator(string $mail): int
    {
        return match (explode('.', explode('@', $mail)[1])[0]) {
            'gmail' => 10,
            'yandex' => 8,
            'mail' => 6,
            default => 3,
        };
    }

    public static function scoreEducation(string $educationType): int
    {
        return match ($educationType) {
            EducationType::HIGHER->value => 15,
            EducationType::SPECIAL->value => 10,
            EducationType::SECONDARY->value => 5,
        };
    }

    public static function scorePdProcessingPermission(bool $pdProcessingPermission): int
    {
        return match ($pdProcessingPermission) {
            true => 4,
            false => 0,
        };
    }
}
