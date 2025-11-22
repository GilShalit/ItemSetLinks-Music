<?php
return [
    'block_layouts' => [
        'invokables' => [
            'itemSetLinks' => ItemSetLinks\Site\BlockLayout\ItemSetLinks::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
];
