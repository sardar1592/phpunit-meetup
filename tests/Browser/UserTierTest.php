<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Order;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTierTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();

        Order::factory(5)->create([
            'user_id' => $user->id,
            'amount' => fake()->numberBetween(20, 99)
        ]);
    }
    public function testUserTierIsCorrectlyVisibleIntheUI(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                ->visit('/users')
                ->pause(5000)
                ->assertSee('Users Management')
                ->assertSee('Bronze')
                ->assertDontSee('Silver')
                ->assertDontSee('Gold')
                ->assertDontSee('Platinum')
                ->assertDontSee('Diamond');
        });
    }
}
