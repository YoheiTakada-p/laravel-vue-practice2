<?php

namespace App\Providers;

class sample
{

    public $resolve;

    public function __construct(resolve $resolve)
    {
        var_dump($resolve);
        $this->resolve = $resolve;
    }

    public function test2()
    {
        return 'test2';
    }
    public function test1()
    {
        return $this->resolve->test();
    }
    public function test3()
    {
        return $this->resolve->test3;
    }
    public function var()
    {
        return var_dump($this->resolve);
    }
}
