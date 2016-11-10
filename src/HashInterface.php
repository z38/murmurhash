<?php

namespace Z38\MurmurHash;

interface HashInterface
{
    /**
     * Resets the hash to its initial state.
     */
    public function reset();

    /**
     * Adds more data to the running hash.
     *
     * @param string $data
     */
    public function write($data);

    /**
     * Returns the current hash without changing the underlying state.
     *
     * @return string
     */
    public function sum();
}
