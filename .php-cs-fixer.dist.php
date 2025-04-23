<?php

use PHPyh\CodingStandard\PhpCsFixerCodingStandard;

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('vendor')
    ->name('*.php');

$config = (new PhpCsFixer\Config())
    ->setFinder($finder)
    // ...
;

(new PhpCsFixerCodingStandard())->applyTo($config, [
    // overriding rules
]);

return $config;
