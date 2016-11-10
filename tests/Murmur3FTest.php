<?php

namespace Z38\MurmurHash\Tests;

use Z38\MurmurHash\Murmur3F;

class Murmur3FTest extends HashTestCase
{
    protected function createHasher($seed)
    {
        return new Murmur3F($seed);
    }

    protected function getVerificationValue()
    {
        return "\x63\x84\xBA\x69";
    }

    /**
     * @dataProvider getKnownValues
     */
    public function testKnownValues($seed, $expected1, $expected2, $input)
    {
        $expected = strrev(hex2bin($expected1)).strrev(hex2bin($expected2));
        $hasher = new Murmur3F($seed);
        $hasher->write($input);
        $this->assertSame($expected, $hasher->sum());
    }

    public function getKnownValues()
    {
        return [
            // from github.com/spaolacci/murmur3
            [0, '0000000000000000', '0000000000000000', ''],
            [0, 'cbd8a7b341bd9b02', '5b1e906a48ae1d19', 'hello'],
            [0, '342fac623a5ebc8e', '4cdcbc079642414d', 'hello, world'],
            [0, 'b89e5988b737affc', '664fc2950231b2cb', '19 Jan 2038 at 3:14:07 AM'],
            [0, 'cd99481f9ee902c9', '695da1a38987b6e7', 'The quick brown fox jumps over the lazy dog.'],
            // from Guava
            [0, '629942693e10f867', '92db0b82baeb5347', 'hell'],
            [1, 'a78ddff5adae8d10', '128900ef20900135', 'hello'],
            [2, '8a486b23f422e826', 'f962a2c58947765f', 'hello '],
            [3, '2ea59f466f6bed8c', 'c610990acc428a17', 'hello w'],
            [4, '79f6305a386c572c', '46305aed3483b94e', 'hello wo'],
            [5, 'c2219d213ec1f1b5', 'a1d8e2e0a52785bd', 'hello wor'],
            [0, 'e34bbc7bbc071b6c', '7a433ca9c49a9347', 'The quick brown fox jumps over the lazy dog'],
            [0, '658ca970ff85269a', '43fee3eaa68e5c3e', 'The quick brown fox jumps over the lazy cog'],
        ];
    }
}
