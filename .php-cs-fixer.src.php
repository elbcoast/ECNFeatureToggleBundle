<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__.'/src')
;

return (new PhpCsFixer\Config())
    ->setRules([
        // Symfony Coding Standard (includes PSR2 and PSR12) but disable some settings to not violate the current Otelo Coding Standard
        '@Symfony' => true,
        '@Symfony:risky' => true,
        // So declare_strict_type can be on the same line as the opening tag
        'blank_line_after_opening_tag' => false,
        'linebreak_after_opening_tag' => false,
        // Would otherwise remove @inheritdoc tags from classes does not inherit.
        'phpdoc_no_useless_inheritdoc' => false,
        // Would otherwise remove @param, @return and @var tags that don't provide any useful information.
        'no_superfluous_phpdoc_tags' => false,
        // In @PHP80Migration:risky containing 'declare_strict_types' is not needed on interfaces,
        // so you can remove them there @see https://github.com/Automattic/phpcs-neutron-standard/issues/20
        '@PHP81Migration' => true,
        '@DoctrineAnnotation' => true,
        // Risky formatting. Use --allow-risky=true in the command to use them.
        '@PHP80Migration:risky' => true,
    ])
    ->setFinder($finder)
;
