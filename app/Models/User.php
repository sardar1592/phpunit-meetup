<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getTotalOrderAmountForUser()
    {
        return $this->orders()->sum('amount');
    }

    public function loyaltyTier()
    {
        $totalAmount = $this->getTotalOrderAmountForUser();

        return match (true) {
            $totalAmount >= 10000 => 'Diamond',
            $totalAmount >= 5000 => 'Platinum',
            $totalAmount >= 1000 => 'Gold',
            $totalAmount >= 500 => 'Silver',
            $totalAmount >= 100 => 'Bronze',
            default => 'None',
        };
    }
}
