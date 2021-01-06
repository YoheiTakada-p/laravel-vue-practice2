<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Testposts extends Model
{
    //
    public function testcomment() {

        return $this->hasMany('App\Testcomment');
    }
}
