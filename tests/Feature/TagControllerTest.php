<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagControllerTest extends TestCase
{
    /**
     * @test
     */
    public function itListTags()
    {
        $response = $this->get('api/tags');
        $response->assertStatus(200);
        $this->assertNotNull($response->json('data')[0]['id']);
        //  $this->assertCount(3, $response->json('data'));
        $this->assertArrayHasKey('name', ['name' => 'dfdfd']);
    }
}
