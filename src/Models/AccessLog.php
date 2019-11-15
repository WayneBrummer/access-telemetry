<?php

namespace Pace\MailTelemetry\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    use Cachable;

    public $timestamps = false;

    protected $guarded = ['id'];
}
