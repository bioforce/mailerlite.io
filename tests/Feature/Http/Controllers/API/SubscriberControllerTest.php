<?php

namespace Tests\Feature\Http\Controllers\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubscriberControllerTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function return_subscribers()
    {
        $response = $this->getJson(route('subscribers.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function error_with_email_when_subscribe()
    {
        $response = $this->postJson(route('subscribe'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function subscribe_user()
    {
        $response = $this->postJson(route('subscribe'), [
            'email' => $this->faker->safeEmail,
            'name' => $this->faker->name,
            'address' => $this->faker->address,
        ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function add_subscribe_user()
    {
        $response = $this->postJson(route('subscribers.store'), [
            'email' => $this->faker->safeEmail,
            'name' => $this->faker->name,
            'address' => $this->faker->address,
        ]);

        $response->assertStatus(201);
    }
}
