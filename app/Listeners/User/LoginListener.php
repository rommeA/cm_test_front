<?php

namespace App\Listeners\User;

use App\Events\User\SuspiciousLoginAttempt;
use App\Models\AuthenticationLog;
use Carbon\Carbon;
use danielme85\Geoip2\Facade\Reader;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginListener
{
    /**
     * The request.
     *
     * @var Request
     */
    public Request $request;

    /**
     * Create the event listener.
     *
     * @param  Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param string $ip
     * @return null|array
     */
    public function getLocationData(): ?array
    {
        $data = [
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'country_geoname_id' => null,
            'country_name' => null,
            'country_iso_code' =>null,
            'city_geoname_id' => null,
            'city_name' => null,
            'login_at' => Carbon::now(),
        ];
        try {
            $reader = Reader::connect();
            $geoData = $reader->city($data['ip_address']);
            $geoData = $geoData->jsonSerialize();
            $data['country_geoname_id'] = $geoData['country']['geoname_id'] ?? null;
            $data['country_name'] = $geoData['country']['names']['en'] ?? null;
            $data['country_iso_code'] = $geoData['country']['iso_code'] ?? null;
            $data['city_geoname_id'] = $geoData['city']['geoname_id'] ?? null;
            $data['city_name'] = $geoData['city']['names']['en'] ?? null;
        } catch (\Exception $e) {
        }
        return $data;
    }

    public function isLocationUnusual(Authenticatable $user, int|null $country_geoname_id): bool
    {
        if ($country_geoname_id) {
            $from = date(now()->addDays(-14));
            $to = date(now());

            $knownCountryId = $user->authentications()
                ->whereCountryGeonameId($country_geoname_id)
                ->whereBetween('login_at', [$from, $to])
                ->skip(1)
                ->first();
            $is_first_login = $user->authentications()->count() == 1;
            if (! $knownCountryId and ! $is_first_login) {
                return true;
            }
        }
        return false;
    }

    public function isDeviceUnusual(Authenticatable $user, string|null $user_agent): bool
    {
        $knownUserAgent = $user->authentications()
            ->whereUserAgent($user_agent)
            ->skip(1)
            ->first();

        $devices = $user->authentications()
            ->select('user_agent')
            ->distinct()
            ->count();

        $allowed_devices = config('auth-log.devices_count');
        if (!$knownUserAgent and $devices > $allowed_devices) {
            return true;
        }
        return false;
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        $user = $event->user;
        $data = $this->getLocationData();
        $authenticationLog = new AuthenticationLog($data);
        $authenticationLog['authenticatable_type'] = 'App\Models\User';
        $user->authentications()->save($authenticationLog);
        $data['auth_log_id'] = $user->authentications()->first()->id;

        if ($this->isLocationUnusual($user, $data['country_geoname_id'])) {
            SuspiciousLoginAttempt::dispatch($user, $data);
        } elseif ($this->isDeviceUnusual($user, $data['user_agent'])) {
            SuspiciousLoginAttempt::dispatch($user, $data);
        }
    }
}
