<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    //
    public function test()
    {
        $test_1 = "テスト";
        return $test_1;
    }
}
