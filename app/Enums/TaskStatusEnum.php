<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case planned = "planned";
    case in_progress = "in_progress";
    case done = "done";
}
