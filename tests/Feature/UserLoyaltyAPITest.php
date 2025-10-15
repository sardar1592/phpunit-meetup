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
            'amount' => fake()->numberBetween(20, 100)
        ]);
    }

    public function testItCanDetectTheLoyaltyTierCorrectly()
    {

        $response = $this->actingAs($this->user)
            ->get('/api/loyalty/' . $this->user->id);

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
        $last_user = User::latest('id')->first();

        $response = $this->actingAs($this->user)->get('/api/loyalty/' . ($last_user->id + 1));

        $response->assertStatus(404);
    }

    public function testItReturns401ForUnauthenticatedUser()
    {
        $response = $this->get('/api/loyalty/' . $this->user->id);

        $response->assertStatus(302);
    }
}
