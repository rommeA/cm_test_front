<?php

namespace App\Listeners;

use App\Models\DefaultSmtpSetting;
use App\Models\SMTPServer;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

trait UsesOwnSMTP
{
    public string $mailer = 'smtp';

    /**
     * Connection for queue
     * @var string
     */
    public string $connection = 'database';

    /**
     * Sender's email
     *
     * @var array
     */
    public array $from;

    /**
     * Config for SMTP session
     *
     * @var array
     */
    public array $config;

    /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public int $tries = 5;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public int $backoff = 180;

    public function __construct()
    {
        $this->from['address'] = config('mail.from.address');
        $this->from['name'] = config('mail.from.name');
    }

    public function makeConfigFromDB(string $eventName)
    {
        // Firstly find event-smtp server binding (if exists)
        $smtp = SMTPServer::select('smtp_servers.*')
            ->join('event_smtps', 'event_smtps.smtp_server_id', '=', 'smtp_servers.id')
            ->where('event_smtps.event_name', '=', $eventName)
            ->first();

        if (!$smtp) {
            //  if there's no specific SMTP for this event, using default SMTP
            $this->setDefaultConfig();
        } else {
            $this->config = [
                'transport' => $smtp->transport,
                'host' => $smtp->host,
                'port' => $smtp->port,
                'encryption' => $smtp->encryption,
                'username' => $smtp->username,
                'password' => $smtp->password,
                'timeout' => null,
                'attempts' => $smtp->attempts,
            ];
            $this->from['address'] = $smtp->from_address;
            $this->from['name'] = $smtp->from_name;
            $this->tries = $smtp->attempts;
        }
        $this->mailer = 'smtp_'.$eventName;
        Config::set("mail.mailers.$this->mailer", $this->config);
    }

    public function setDefaultConfig()
    {
        try {
            $transport = DefaultSmtpSetting::where('setting_key', 'transport')->first()->setting_value;
            $host = DefaultSmtpSetting::where('setting_key', 'host')->first()->setting_value;
            $port = DefaultSmtpSetting::where('setting_key', 'port')->first()->setting_value;
            $encryption = DefaultSmtpSetting::where('setting_key', 'encryption')->first()->setting_value;
            $username = DefaultSmtpSetting::where('setting_key', 'username')->first()->setting_value;
            $password = DefaultSmtpSetting::where('setting_key', 'password')->first()->setting_value;
            $attempts = DefaultSmtpSetting::where('setting_key', 'attempt_number')->first()->setting_value;
            $from_address = DefaultSmtpSetting::where('setting_key', 'from_address')->first()->setting_value;
            $from_name = DefaultSmtpSetting::where('setting_key', 'from_name')->first()->setting_value;

            $this->config = [
                'transport' => $transport,
                'host' => $host,
                'port' => $port,
                'encryption' => $encryption,
                'username' => $username,
                'password' => $password,
                'timeout' => null,
                'attempts' => $attempts,
            ];
            $this->from['address'] = $from_address;
            $this->from['name'] = $from_name;
            $this->tries = $attempts;
        } catch (\Exception $exception) {
            Log::error('Default SMTP-server not set in DB. Using default from config. ' . $exception->getMessage());
            $this->config = config('mail.mailers.smtp');
            $this->from['address'] = config('mail.from.address');
            $this->from['name'] = config('mail.from.name');
            $this->tries = config('mail.mailers.smtp.attempts');
        }
    }
}
