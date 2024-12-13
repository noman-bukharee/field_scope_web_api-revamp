<?php

namespace App\Traits;

use App\Libraries\Helper;
use App\Models\MailTemplate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;

trait TimezoneTrait
{

    public function getCreatedAtAttribute($value)
    {
        return $this->changeTimestampToUserTimezone($value);
    }


    public function getUpdatedAtAttribute($value)
    {
        return $this->changeTimestampToUserTimezone($value);
    }


    private function changeTimestampToUserTimezone($value)
    {
        $value = Carbon::parse($value);
        if (request()->header('timezone')) {
            return self::getUserTimestamp($value, request()->header('timezone'));
            // ->format(config('constants.APP_DATE_FORMAT'))
        } else {
            return Carbon::parse($value)->setTimezone('UTC');
        }
    }

    public static function getServerTimestamp($timestamp, $baseTimezone = ""): Carbon
    {
        if (empty($baseTimezone) && !empty(request()->header('timezone'))) {
            $baseTimezone = request()->header('timezone') ?: config("app.timezone"); //if missing in header set defaults
        }
        return self::changeTimezoneOfTimestamp($timestamp, config("app.timezone"), $baseTimezone);
    }

    public static function getUserTimestamp($timestamp, $targetTimezone = ""): Carbon
    {
        if (empty($targetTimezone) && !empty(request()->header('timezone'))) {
            $targetTimezone = request()->header('timezone') ?: config(
                "app.timezone"
            ); //if missing in header set defaults
        }
        return self::changeTimezoneOfTimestamp($timestamp, $targetTimezone);
    }

    public static function changeTimezoneOfTimestamp($timestamp, $targetTimezone, $baseTimezone = ""): Carbon
    {
        $baseTimezone = $baseTimezone ?: config("app.timezone");
        return Carbon::parse($timestamp . " $baseTimezone")->setTimezone($targetTimezone);
    }
}