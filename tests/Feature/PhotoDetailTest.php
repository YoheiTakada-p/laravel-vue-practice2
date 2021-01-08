<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
//add
use App\Photo;
use App\Comment;

class PhotoDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_写真詳細のJSONを取得する()
    {

        factory(Photo::class)->create()->each(function ($photo) {
            $photo->comments()->saveMany(factory(Comment::class, 3)->make());
        });

        $photo = Photo::first();

        $a = Photo::first()->with(['owner'])->first();
        echo $a;
        $response = $this->json('GET', route('photo.show', [
            'id' => $photo->id
        ]));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $photo->id,
                'url' => $photo->url,
                'owner' => [
                    'name' => $photo->owner->name
                ],
                'liked_by_user' => false,
                'likes_count' => 0,
                'comments' => $photo->comments
                    ->sortByDesc('id')
                    ->map(function ($comment) {
                        return [
                            'author' => [
                                'name' => $comment->author->name
                            ],
                            'content' => $comment->content
                        ];
                    })
                    ->all()
            ]);
    }
}
