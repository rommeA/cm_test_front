<?php
namespace App\Models;

trait AuthenticationLogable
{
    /**
     * Get the entity's authentications.
     */
    public function authentications(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(AuthenticationLog::class, 'authenticatable_id')->latest('login_at');
    }


    /**
     * The Authentication Log notifications delivery channels.
     *
     * @return array
     */
    public function notifyAuthenticationLogVia()
    {
        return ['mail'];
    }

    /**
     * Get the entity's last login at.
     */
    public function lastLoginAt()
    {
        return optional($this->authentications()->first())->login_at;
    }

    /**
     * Get the entity's last login ip address.
     */
    public function lastLoginIp()
    {
        return optional($this->authentications()->first())->ip_address;
    }

    /**
     * Get the entity's last login country geoname id.
     */
    public function lastLoginGeonameId()
    {
        return optional($this->authentications()->first())->geoname_id;
    }

    /**
     * Get the entity's previous login at.
     */
    public function previousLoginAt()
    {
        return optional($this->authentications()->skip(1)->first())->login_at;
    }

    /**
     * Get the entity's previous login ip.
     */
    public function previousLoginIp()
    {
        return optional($this->authentications()->skip(1)->first())->ip_address;
    }

    /**
     * Get the entity's previous login geoname id.
     */
    public function previousLoginGeonameId()
    {
        return optional($this->authentications()->skip(1)->first())->geoname_id;
    }
}
