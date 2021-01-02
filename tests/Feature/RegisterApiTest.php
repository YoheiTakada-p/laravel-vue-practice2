<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
//追加
use App\User;

class RegisterApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_新しいユーザーを作成して返却する()
    {
        $data = [
            'name' => 'yohei',
            'email' => 'yohei@example.com',
            'password' => 'yohei00en',
            'password_confirmation' => 'yohei00en'
        ];

        $response = $this->json('POST', route('register'), $data);

        $user = User::first();

        $this->assertEquals($data['name'], $user->name);

        $response->assertStatus(201)->assertJson(['name' => $user->name]);
    }
}
