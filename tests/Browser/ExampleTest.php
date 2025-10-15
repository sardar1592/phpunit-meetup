<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    use DatabaseTruncation;
    public function testLaravelDefaultPageIsVisible(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->pause(1000)
                ->assertSee('Laravel')
                ->assertSee("Let's get started")
                ->assertSeeLink('Documentation')
                ->assertSeeLink('Laracasts');
        });
    }
}
