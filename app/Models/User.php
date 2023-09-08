<?php

namespace App\Models;

use App\DTOs\FilterParams;
use App\Traits\Filterable;
use App\Traits\HasJwtTokens;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;


class User extends Authenticatable
{
    use HasJwtTokens, SoftDeletes, HasFactory, Notifiable, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'is_admin',
        'email',
        'avatar',
        'address',
        'phone_number',
        'is_marketing'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_marketing' => 'boolean',
        'last_login_at' => 'datetime'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, string|boolean>
     */
    protected $attributes = [
        'uuid' => '',
        'is_admin' => false,
        'is_marketing' => false
    ];

    /**
     * Run query filters with these columns.
     *
     * @var array<int, string>
     */
    private static array $filterable = [
        'first_name',
        'email',
        'phone_number',
        'address',
        'created_at',
        'is_marketing',
        'is_admin',
    ];

   /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            $user->uuid = Str::uuid()->toString();
        });
    }

    /**
     * Called when user logs in
     *
     * @return void
     */
    public function loggedIn()
    {
        $this->last_login_at = now();
        $this->save();
    }
 
    /**
     * Checks if user is admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * Get users.
     *
     * @param FilterParams $filter_params
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public static function getUsers(FilterParams $filter_params): LengthAwarePaginator
    {
        $filter_params->__set('is_admin', false);

        return self::getRecords($filter_params, self::$filterable);
    }
}
