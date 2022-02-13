<?php

namespace App\Models;


use App\Traits\HasRange;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


/**
 *
 * @OA\Schema(
 * required={"password", "department_id", "email" , "username", "phone_number", "authorization_level"},
 * @OA\Xml(name="User"),
 * @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="department_id", type="integer", readOnly="true"),
 * @OA\Property(property="username", type="string", description="User unique username"),
 * @OA\Property(property="phone_number", type="string", description="User phone number"),
 * @OA\Property(property="email", type="string", readOnly="true", format="email", description="User unique email address", example="user@gmail.com"),
 * @OA\Property(property="email_verified_at", type="string", readOnly="true", format="date-time", description="Datetime marker of verification status", example="2019-02-25 12:59:20"),
 * @OA\Property(property="authorization_level", type="integer", description="number from 1-11 level 5 above = moderator, level 8 above is admin"),
 * @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 * @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 * @OA\Property(property="deleted_at", ref="#/components/schemas/BaseModel/properties/deleted_at")
 * )
 *
 * Class User
 */


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
