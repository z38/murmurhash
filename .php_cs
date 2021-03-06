<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests')
;

return Symfony\CS\Config\Config::create()
    ->fixers([
        '-psr0',
        '-phpdoc_short_description',
        '-empty_return',
        'ordered_use',
        'short_array_syntax',
    ])
    ->finder($finder)
;
