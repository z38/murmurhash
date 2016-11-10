<?php

namespace Z38\MurmurHash\Tests;

use PHPUnit_Framework_TestCase;

abstract class HashTestCase extends PHPUnit_Framework_TestCase
{
    abstract protected function createHasher($seed);

    abstract protected function getVerificationValue();

    public function testVerification()
    {
        $hashes = '';
        $key = '';
        for ($i = 0; $i < 256; ++$i) {
            $keyHasher = $this->createHasher(256 - $i);
            $keyHasher->write($key);
            $hashes .= $keyHasher->sum();
            $key .= chr($i);
        }

        $hasher = $this->createHasher(0);
        $hasher->write($hashes);
        $final = $hasher->sum();

        $result = strrev(substr($final, 0, 4));
        $this->assertSame($this->getVerificationValue(), $result);
    }
}
