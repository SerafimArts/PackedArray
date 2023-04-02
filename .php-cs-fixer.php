<?php

return (new PhpCsFixer\Config())
    ->setRules([
        '@PER' => true,
        '@PER:risky' => true,
        'strict_param' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
        ->setCacheFile(__DIR__ . '/vendor/.php-cs-fixer.cache')
        ->setFinder(PhpCsFixer\Finder::create()
            ->in(__DIR__ . '/src'))
        ;
