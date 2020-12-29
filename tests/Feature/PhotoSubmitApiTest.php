<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class PhotoSubmitApiTest extends TestCase
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
    public function should_ファイルをアップロードできる()
    {
        //S3ではなくテスト用のストレージを使用する
        //storage/framework/testing
        Storage::fake('s3');

        $response = $this->actingAs($this->user)
            ->json(
                'POST',
                route('photo.create'),
                //ダミーファイルを作成して送信
                ['photo' => UploadedFile::fake()->image('photo.jpg')]
            );

        $response->assertStatus(201);
        $photo = Photo::first();

        //写真のIDが12桁のランダムな文字列であること
        $this->assertMatchesRegularExpression('/^[0-9a-zA-Z-_]{12}$/', $photo->id);
        //DBに挿入されたファイル名のファイルがストレージに保存されていること
        Storage::cloud()->assertExists($photo->filename);
    }
}
