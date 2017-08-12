<?php

namespace App;

use App\Models\Booking;
use App\Models\Trip;
use App\Models\Vehicle;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Jobs\SendConfirmationEmail;

class User extends Authenticatable
{
    use Notifiable;

    const PASSENGER_PERMISSION = 1;
    const DRIVER_PERMISSION = 2;
    const ADMIN_PERMISSION = 4;

    /**
     * Boot the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->verification_token = str_random(30);
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'permissions',
        'phone',
        'birth_date',
        'about_me',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'birth_date',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'is_verified' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    /**
     * @return bool
     */
    public function isAdmin() : bool
    {
        return (bool) ($this->attributes['permissions'] & self::ADMIN_PERMISSION);
    }

    /**
     * @return bool
     */
    public function isPassenger() : bool
    {
        return (bool) ($this->attributes['permissions'] & self::PASSENGER_PERMISSION);
    }

    /**
     * @return bool
     */
    public function isDriver() : bool
    {
        return (bool) ($this->attributes['permissions'] & self::DRIVER_PERMISSION);
    }

    /**
     * Check whether the user can uncheck the passenger role.
     *
     * For this, the user should not have any bookings.
     *
     * @return bool
     */
    public function canUncheckPassenger(): bool
    {
        return $this->bookings()->first() === null;
    }

    /**
     * Check whether the user can uncheck the driver role.
     *
     * For this, the user should not have any created trips.
     *
     * @return bool
     */
    public function canUncheckDriver(): bool
    {
        return $this->trips()->first() === null;
    }
}
