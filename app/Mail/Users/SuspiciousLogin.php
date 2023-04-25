<?php

namespace App\Mail\Users;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SuspiciousLogin extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * Sender's email
     *
     * @var string
     */
    public string $from_address;


    /**
     * Sender's name
     *
     * @var string
     */
    public string $from_name;

    /**
     * Login info
     *
     * @var array
     */
    public array $data;

    public string $route;

    /**
     * Create a new message instance.
     * @param array $data
     * @param array $from
     * @param string $route
     * @return void
     */
    public function __construct(array $data, array $from, string $route)
    {
        $this->data = $data;
        $this->from_address = $from['address'];
        $this->from_name = $from['name'];
        $this->route = $route;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->markdown('emails.login.suspicious-login', ['data' => $this->data, 'route' => $this->route])
            ->from($this->from_address, $this->from_name);
    }
}
