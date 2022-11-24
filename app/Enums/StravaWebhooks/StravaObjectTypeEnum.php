<?php

namespace App\Enums\StravaWebhooks;

enum StravaObjectTypeEnum: string
{
    case activity = 'activity';
    case athlete = 'athlete';
}
