<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserLoyaltyTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    public Collection $orders;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_new_user_has_tier_none()
    {

        $this->assertEquals($this->user->loyaltyTier(), 'None');
    }


    public function test_user_with_orders_between_0_and_100_has_none_tier()
    {

        $this->orders = Order::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'amount' => fake()->numberBetween(0, 20)
        ]);

        $this->assertEquals($this->user->loyaltyTier(), 'None');
    }
    public function test_user_with_orders_between_100_and_500_has_bronze_tier()
    {

        $this->orders = Order::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'amount' => fake()->numberBetween(20, 100)
        ]);

        $this->assertEquals($this->user->loyaltyTier(), 'Bronze');
    }

    public function test_user_with_orders_between_500_and_1000_has_silver_tier()
    {

        $this->orders = Order::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'amount' => fake()->numberBetween(100, 200)
        ]);

        $this->assertEquals($this->user->loyaltyTier(), 'Silver');
    }

    public function test_user_with_orders_between_1000_and_5000_has_gold_tier()
    {

        $this->orders = Order::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'amount' => fake()->numberBetween(200, 1000)
        ]);

        $this->assertEquals($this->user->loyaltyTier(), 'Gold');
    }

    public function test_user_with_orders_between_5000_and_10000_has_platinum_tier()
    {

        $this->orders = Order::factory()->count(10)->create([
            'user_id' => $this->user->id,
            'amount' => fake()->numberBetween(500, 999)
        ]);

        $this->assertEquals($this->user->loyaltyTier(), 'Platinum');
    }

    public function test_user_with_orders_above_10000_has_diamond_tier()
    {

        $this->orders = Order::factory()->count(10)->create([
            'user_id' => $this->user->id,
            'amount' => fake()->numberBetween(1000, 10000)
        ]);

        $this->assertEquals($this->user->loyaltyTier(), 'Diamond');
    }

    public function test_user_with_orders_exactly_5000_has_platinum_tier()
    {

        Order::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 5000
        ]);

        $this->assertEquals($this->user->loyaltyTier(), 'Platinum');
    }

    public function test_user_with_orders_exactly_10000_has_diamond_tier()
    {

        Order::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 10000
        ]);

        $this->assertEquals($this->user->loyaltyTier(), 'Diamond');
    }

    public function test_user_with_orders_exactly_10001_has_diamond_tier()
    {
        Order::factory()->create([
            'user_id' => $this->user->id,
            'amount' => 10001
        ]);

        $this->assertEquals($this->user->loyaltyTier(), 'Diamond');
    }
}
