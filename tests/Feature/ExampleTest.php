<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Unauthenticated visitors are redirected to login, and login page returns 200.
     */
    public function test_the_application_redirects_guest_to_login(): void
    {
        $this->get('/')
            ->assertRedirect(route('login'));

        $this->get(route('login'))
            ->assertStatus(200)
            ->assertSee('Masuk ke Dashboard BK');
    }
}
