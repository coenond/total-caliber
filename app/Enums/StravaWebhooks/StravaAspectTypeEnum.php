<?php

namespace App\Enums\StravaWebhooks;

enum StravaAspectTypeEnum: string
{
    case create = 'create';
    case update = 'update';
    case delete = 'delete';
}
