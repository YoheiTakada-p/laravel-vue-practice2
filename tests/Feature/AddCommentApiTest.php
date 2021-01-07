<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
//add
use App\User;
use App\Photo;
use App\Comment;

class AddCommentApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }
    /**
     * @test
     */
    public function should_コメント投稿できる！()
    {
        factory(Photo::class)->create();

        $photo = Photo::first();

        $content = 'sample comment';

        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.comment', [
                'photo' => $photo->id,
            ]), compact('content'));

        $comments = $photo->comments()->get();

        echo $comments;


        $response->assertStatus(201)
            ->assertJsonFragment([
                "author" => [
                    "name" => $this->user->name
                ],
                "content" => $content
            ]);

        //DBにコメントが1つあるか
        $this->assertEquals(1, $comments->count());
        //コメント内容が期待したものと同じか
        $this->assertEquals($content, $comments[0]->content);
    }
}
