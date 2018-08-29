<?php

$finder = PhpCsFixer\Finder::create()
    ->name('*.php')
    ->in(__DIR__ . DIRECTORY_SEPARATOR . 'src');

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        'align_multiline_comment' => ['comment_type' => 'all_multiline'],
        'array_syntax' => ['syntax' => 'short'],
        'blank_line_before_return' => true,
        'concat_space' => ['spacing' => 'one'],
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'native_function_invocation' => ['scope' => 'namespaced'],
        'no_unused_imports' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'not_operator_with_successor_space' => true,
        'ordered_class_elements' => [
            'sortAlgorithm' => 'alpha',
            'order' => [
                'use_trait',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'destruct',
                'magic',
                'phpunit',
                'method_public',
                'method_protected',
                'method_private',
            ],
        ],
        'ordered_imports' => ['sortAlgorithm' => 'length'],
        'phpdoc_annotation_without_dot' => true,
        'phpdoc_order' => true,
        'short_scalar_cast' => true,
        'single_blank_line_before_namespace' => true,
        'strict_param' => true,
        'trailing_comma_in_multiline_array' => true,
        'trim_array_spaces' => true,
        'yoda_style' => true,
    ])
    ->setFinder($finder);
