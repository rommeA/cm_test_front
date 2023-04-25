<?php

namespace App\Models\ActivityLogger;

use App\Models\User;

trait AccessesActivityLog
{

    public function getLastChangeAttribute()
    {
        return $this->activities()->orderBy('updated_at', 'desc')->first();
    }

    public function getChangedByUserNameAttribute()
    {
        if (isset($this->lastChange->causer_id)) {
            return User::find($this->lastChange->causer_id)?->display_name;
        }
        return null;
    }

    public function getChangedByUserSlugAttribute()
    {
        if (isset($this->lastChange->causer_id)) {
            return User::find($this->lastChange->causer_id)?->slug;
        }
        return null;
    }
}
