<?php

namespace Z38\MurmurHash\Tests;

use Z38\MurmurHash\HashInterface;

class Murmur3A implements HashInterface
{
    private $seed;
    private $key;

    public function __construct($seed = 0)
    {
        $this->seed = $seed;
        $this->reset();
    }

    public function reset()
    {
        $this->key = '';
    }

    public function write($data)
    {
        $this->key .= $data;
    }

    public function sum()
    {
        $h1 = murmurhash3_int($this->key, $this->seed);

        return pack('V', $h1);
    }
}
