<?php

$finder = (new PhpCsFixer\Finder())
    ->exclude('var')
    ->in(__DIR__.'/src')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        '@Symfony' => true,
    ])
    ->setFinder($finder)
;
