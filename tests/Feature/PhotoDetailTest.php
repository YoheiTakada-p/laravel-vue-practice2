<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
//add
use App\Photo;

class PhotoDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_写真詳細のJSONを取得する()
    {

        factory(Photo::class)->create();

        $photo = Photo::first();

        $response = $this->json('GET', route('photo.show', [
            'id' => $photo->id
        ]));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $photo->id,
                'url' => $photo->url,
                'owner' => [
                    'name' => $photo->owner->name
                ]
            ]);
    }
}
