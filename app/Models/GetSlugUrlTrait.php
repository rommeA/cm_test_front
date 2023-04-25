<?php

namespace App\Models;

use Illuminate\Support\Str;

trait GetSlugUrlTrait
{
    public function getSlugUrlAttribute()
    {
        $table_name = $this->routeName ?? $this->getTable();
        $singular = Str::singular($table_name);
        return route("$table_name.show", [$singular => $this->slug]);
    }
}
