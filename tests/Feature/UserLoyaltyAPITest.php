<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;
use App\Models\User;

class UserLoyaltyAPITest extends TestCase
{
    use RefreshDatabase;

    public User $user;
    public Order $order;
    public Collection $orders;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->orders = Order::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'amount' => fake()->numberBetween(20, 99)
        ]);
    }

    public function testItCanDetectTheLoyaltyTierCorrectly()
    {

        $url = env('APP_URL') . '/api/loyalty/' . $this->user->id;

        $response = $this->actingAs($this->user)
            ->get($url);

        $response->assertStatus(200);

        $response->assertJson([
            'user_id' => $this->user->id,
            'loyalty_tier' => 'Bronze',
            'orders_total' => $this->orders->sum('amount'),
            'order_count' => $this->orders->count()
        ]);
    }

    public function testItReturns404ForNonExistentUser()
    {
        $url = env('APP_URL') . '/api/loyalty/' . ($this->user->id + rand(5, 1000));

        $response = $this->actingAs($this->user)->get($url);

        $response->assertStatus(404);
    }

    public function testItReturns302ForUnauthenticatedUser()
    {
        $response = $this->get('/api/loyalty/' . $this->user->id);

        $response->assertStatus(302);
    }

    public function testOneUserCanNotAccessAnotherUsersLoyaltyInfo()
    {
        $anotherUser = User::factory()->create();

        $response = $this->actingAs($anotherUser)
            ->get('/api/loyalty/' . $this->user->id);

        $response->assertStatus(403);
    }
}
