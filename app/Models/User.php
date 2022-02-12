<?php

namespace App\Models;


use App\Traits\HasRange;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRange;


    protected $admin = 8;
    protected $moderator = 5;
    protected $user = 2;
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    public function isAdmin()
    {
        return $this->authorization_level >= $this->admin;
    }
    public function isModerator()
    {
        return $this->authorization_level >= $this->moderator;
    }
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->HasMany(Message::class);
    }
    public function articles()
    {
        return $this->HasMany(Article::class);
    }
    public function announcements()
    {
        return $this->HasMany(Announcement::class);
    }
    public function materials()
    {
        return $this->HasMany(Material::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
