<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
//追加
use Illuminate\Http\UploadedFile;
use App\User;
use App\Photo;

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
        \Storage::fake('s3');

        $response = $this->actingAs($this->user)
            ->json(
                'POST',
                route('photo.create'),
                //ダミーファイルを作成して送信
                ['photo' => UploadedFile::fake()->image('photo.jpg')]
            );

        //assert
        $response->assertStatus(201);
        $photo = Photo::first();

        //写真のIDが12桁のランダムな文字列であること
        $this->assertMatchesRegularExpression('/^[0-9a-z]{12}$/', $photo->id);
        //DBに挿入されたファイル名のファイルがストレージに保存されていること
        \Storage::cloud()->assertExists($photo->filename);
    }
    /**
     * @test
     */
    public function should_データベースエラーの場合はファイルを保存しない()
    {

        //エラー
        \Schema::drop('photos');

        \Storage::fake('s3');

        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.create'), [
                'photo' => UploadedFile::fake()->image('photo.jpg'),
            ]);

        //assert
        $response->assertStatus(500);

        $this->assertEquals(0, count(\Storage::cloud()->files()));
    }
    /**
     * @test
     */
    public function should_ファイル保存エラーの場合はDBへの挿入はしない()
    {
        //ストレージをモックして保存時にエラーを起こさせる
        \Storage::shouldReceive('cloud')->once()->andReturnNull();

        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.create'), [
                'photo' => UploadedFile::fake()->image('photo.jpg')
            ]);

        //assert
        $response->assertStatus(500);

        $this->assertEmpty(Photo::all());
    }
}
