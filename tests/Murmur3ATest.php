<?php

namespace Z38\MurmurHash\Tests;

class Murmur3ATest extends HashTestCase
{
    protected function createHasher($seed)
    {
        return new Murmur3A($seed);
    }

    protected function getVerificationValue()
    {
        return 0xB0F57EE3;
    }
}
