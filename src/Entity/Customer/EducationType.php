<?php

namespace App\Entity\Customer;

enum EducationType: string
{
    case SECONDARY = 'Среднее образование';
    case SPECIAL = 'Специальное образование';
    case HIGHER = 'Высшее образование';
}
