<?php

namespace App\ValueObjects\Plan;

enum Benefits: string
{
    case VISITS_LIMIT = 'VISITS_LIMIT';
    case MESSAGES_LIMIT = 'MESSAGES_LIMIT';
    case FAVORITES_LIMIT = 'FAVORITES_LIMIT';
    case ADS_LIMIT = 'ADS_LIMIT';
}
