<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
//add
use App\Photo;
use App\User;

class PhotoListTest extends TestCase
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
    public function should_正しい構造のJsonを返却()
    {
        factory(Photo::class, 5)->create();

        $response = $this->actingAs($this->user)->json('GET', route('photo.index'));

        //withメソッドでownerに作成日の降順でデータを作成する
        $photos = Photo::with(['owner'])->orderby('created_at', 'desc')->get();

        echo $photos->first();

        $response->assertStatus(200);
    }
}
