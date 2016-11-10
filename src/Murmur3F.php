<?php

namespace Z38\MurmurHash;

class Murmur3F implements HashInterface
{
    private $seed;
    private $h1;
    private $h2;
    private $len;
    private $tail;
    private static $c1;
    private static $c2;
    private static $m1;
    private static $m2;
    private static $fmix1;
    private static $fmix2;
    private static $mask64;

    public function __construct($seed = 0)
    {
        self::init();
        $this->seed = $seed;
        $this->reset();
    }

    public function reset()
    {
        $this->h1 = $this->seed;
        $this->h2 = $this->seed;
        $this->len = 0;
        $this->tail = '';
    }

    public function write($data)
    {
        $this->len += strlen($data);
        $this->tail .= $data;
        while (strlen($this->tail) >= 16) {
            $k1 = gmp_init(bin2hex(strrev(substr($this->tail, 0, 8))), 16);
            $k2 = gmp_init(bin2hex(strrev(substr($this->tail, 8, 8))), 16);
            $this->tail = substr($this->tail, 16);

            $h1 = $this->h1;
            $h2 = $this->h2;

            $k1 = self::mul64($k1, self::$c1);
            $k1 = self::rotl64($k1, 31);
            $k1 = self::mul64($k1, self::$c2);
            $h1 = gmp_xor($h1, $k1);

            $h1 = self::rotl64($h1, 27);
            $h1 = self::add64($h1, $h2);
            $h1 = self::add64(gmp_mul(5, $h1), self::$m1);

            $k2 = self::mul64($k2, self::$c2);
            $k2 = self::rotl64($k2, 33);
            $k2 = self::mul64($k2, self::$c1);
            $h2 = gmp_xor($h2, $k2);

            $h2 = self::rotl64($h2, 31);
            $h2 = self::add64($h2, $h1);
            $h2 = self::add64(gmp_mul(5, $h2), self::$m2);

            $this->h1 = $h1;
            $this->h2 = $h2;
        }
    }

    public function sum()
    {
        $h1 = $this->h1;
        $h2 = $this->h2;

        $k1 = 0;
        $k2 = 0;

        $tail = array_values(unpack('C*', $this->tail));
        $len = count($tail);
        for ($i = $len; $i > 8; --$i) {
            $k2 = gmp_xor($k2, self::shiftl64($tail[$i - 1], ($i - 9) * 8));
        }
        if ($len > 8) {
            $k2 = self::mul64($k2, self::$c2);
            $k2 = self::rotl64($k2, 33);
            $k2 = self::mul64($k2, self::$c1);
            $h2 = gmp_xor($h2, $k2);
        }
        for ($i = min($len, 8); $i > 0; --$i) {
            $k1 = gmp_xor($k1, self::shiftl64($tail[$i - 1], ($i - 1) * 8));
        }
        if ($len > 0) {
            $k1 = self::mul64($k1, self::$c1);
            $k1 = self::rotl64($k1, 31);
            $k1 = self::mul64($k1, self::$c2);
            $h1 = gmp_xor($h1, $k1);
        }

        $h1 = gmp_xor($h1, $this->len);
        $h2 = gmp_xor($h2, $this->len);
        $h1 = self::add64($h1, $h2);
        $h2 = self::add64($h2, $h1);
        $h1 = self::fmix64($h1);
        $h2 = self::fmix64($h2);
        $h1 = self::add64($h1, $h2);
        $h2 = self::add64($h2, $h1);

        return self::export64($h1).self::export64($h2);
    }

    private static function init()
    {
        if (self::$c1 !== null) {
            return;
        }

        self::$c1 = gmp_init('0x87c37b91114253d5');
        self::$c2 = gmp_init('0x4cf5ad432745937f');
        self::$m1 = gmp_init('0x52dce729');
        self::$m2 = gmp_init('0x38495ab5');
        self::$fmix1 = gmp_init('0xff51afd7ed558ccd');
        self::$fmix2 = gmp_init('0xc4ceb9fe1a85ec53');
        self::$mask64 = gmp_init('0xffffffffffffffff');
    }

    private static function fmix64($x)
    {
        $x = gmp_xor($x, self::shiftr($x, 33));
        $x = self::mul64($x, self::$fmix1);
        $x = gmp_xor($x, self::shiftr($x, 33));
        $x = self::mul64($x, self::$fmix2);
        $x = gmp_xor($x, self::shiftr($x, 33));

        return $x;
    }

    private static function rotl64($x, $r)
    {
        return gmp_and(gmp_or(
            self::shiftl($x, $r),
            self::shiftr($x, 64 - $r)
        ), self::$mask64);
    }

    private static function export64($x)
    {
        return strrev(pack('H*', str_pad(gmp_strval($x, 16), 16, '0', STR_PAD_LEFT)));
    }

    private static function shiftl64($x, $shift)
    {
        return gmp_and(self::shiftl($x, $shift), self::$mask64);
    }

    private static function shiftl($x, $shift)
    {
        return gmp_mul($x, gmp_pow(2, $shift));
    }

    private static function shiftr($x, $shift)
    {
        return gmp_div($x, gmp_pow(2, $shift));
    }

    private static function mul64($a, $b)
    {
        return gmp_and(gmp_mul($a, $b), self::$mask64);
    }

    private static function add64($a, $b)
    {
        return gmp_and(gmp_add($a, $b), self::$mask64);
    }
}
