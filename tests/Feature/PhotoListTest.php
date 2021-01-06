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

    /**
     * @test
     */
    public function should_正しい構造のJsonを返却()
    {
        factory(Photo::class, 5)->create();

        $response = $this->json('GET', route('photo.index'));

        //withメソッドでownerに作成日の降順でデータを作成する
        $photos = Photo::with(['owner'])->orderby('created_at', 'desc')->get();

        //オブジェクトをJSON形式で表現する
        $json_data = $photos->map(function ($photo) {
            return [
                'id' => $photo->id,
                'url' => $photo->url,
                'owner' => [
                    'name' => $photo->owner->name
                ]
            ];
        })
        ->all();


        $response->assertStatus(200)
            // レスポンスJSONのdata項目に含まれる要素が5つであること
            ->assertJsonCount(5, 'data')
            // レスポンスJSONのdata項目が期待値と合致すること
            ->assertJsonFragment([
                'data' => $json_data,
            ]);
    }
}
