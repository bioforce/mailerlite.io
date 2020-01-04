<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class HomeControllerTest extends TestCase
{
    /** @test */
    public function admin_page_not_authorized()
    {
        $response = $this->get(route('admin.subscribers'));

        $response->assertStatus(302);
    }


    /** @test */
    public function admin_page_authorized()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('admin.subscribers'));

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_subscribers_page_not_authorized()
    {
        $response = $this->get(route('admin.subscribers'));

        $response->assertStatus(302);
    }


    /** @test */
    public function admin_subscribers_fields_page_authorized()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('admin.subscribers'));

        $response->assertStatus(200);
    }

}
