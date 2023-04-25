<?php

namespace App\Models;

use Adldap\Laravel\Traits\HasLdapUser;
use App\Events\User\UserBlocked;
use App\Models\ActivityLogger\AccessesActivityLog;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use OpenApi\Annotations as OA;
use Ramsey\Uuid\Uuid;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


/**
 *
 * @OA\Schema(
 * required={"password"},
 * @OA\Xml(name="User"),
 * @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="email", type="string", readOnly="true", format="email", description="User unique email address", example="user@gmail.com"),
 * @OA\Property(property="email_verified_at", type="string", readOnly="true", format="date-time", description="Datetime marker of verification status", example="2019-02-25 12:59:20"),
 * @OA\Property(property="firstname", type="string", maxLength=32, example="John"),
 * @OA\Property(property="lastname", type="string", maxLength=32, example="John"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Initial creation timestamp", readOnly="true"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", readOnly="true"),
 * @OA\Property(property="deleted_at", type="string", format="date-time", description="Soft delete timestamp", readOnly="true"),
 * )
 *
 * Class User
 *
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use AuthenticationLogable;
    use LogsActivity;
    use AccessesActivityLog;
    use GetSlugUrlTrait;


    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $guarded = ['id'];

    public $routeName = 'employees';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'binary_photo'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'id' => 'string',
    ];

    /**
     * The attributes that should be appended to JSON.
     *
     * @var array<string, string>
     */
    protected $appends = [];


    protected static array $recordEvents = ['created', 'updated', 'deleted'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logExcept(['binary_photo'])
            ->logUnguarded()
            ->logOnlyDirty();
        // Chain fluent methods for configuration options
    }


    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getPhotoAttribute(): bool|string|null
    {
        if (isset($this->binary_photo)  && is_resource($this->binary_photo)) {
            return stream_get_contents($this->binary_photo);
        }
        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function boot()
    {
        parent::boot();


        static::creating(function ($model) {
            $slug = Str::slug(($model->firstname . '_' . $model->lastname), '_');
            $count = DB::table('users')
                ->where('slug', 'like', "$slug%")
                ->count();
            if ($count) {
                $last_slug = DB::table('users')
                ->where('slug', 'like', "$slug%")
                ->orderByDesc('created_at')
                ->first();

                $arr = explode('_', $last_slug->slug);
                $num = (int)end($arr);


                $slug = $slug . "_" . $num+1+$count;
            }
            $model->slug = $slug;
        });
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $post = $this->where('slug', $value)->first();
        if (! $post and Uuid::isValid($value)) {
            $post = $this->where('id', $value)->firstOrFail();
        }
        return $post;
    }



    public function setDateBirthAttribute($value)
    {
        if (!$value) {
            $this->attributes['date_birth'] = null;
        } else {
            $this->attributes['date_birth'] = date('Y-m-d H:i:s', strtotime($value));
        }
    }

    public function getAgeAttribute()
    {
        return date_diff(date_create($this->date_birth), date_create('now'))->y;
    }

    public function getFormattedLastSeenAttribute()
    {
        $time = $this->lastLoginAt()?->timezone('Europe/Moscow');
        if ( ! $time) {
            return '-';
        }
        $now = now()->timezone('Europe/Moscow');
        $days = $now->diffInDays($time);

        if ($days < 1) {
            return 'today at ' . $time->format('H:i');
        } elseif ($days < 2) {
            return 'yesterday at ' . $time->format('H:i');
        } else {
            return $time->format('d.m.y') . ' ' . __('at') . ' ' . $time->format('H:i');
        }
    }

}
