<?php

use Illuminate\Support\Env;

return [
    "api_url" => Env::get("SMS_URL"),
    "login" => Env::get("SMS_LOGIN"),
    "password" => Env::get("SMS_PASSWORD"),
    "dry_run" => Env::get("SMS_DRY_RUN", false),
];