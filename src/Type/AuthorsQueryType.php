<?php

namespace YOOtheme\Source\K2\Type;

use function YOOtheme\trans;

class AuthorsQueryType
{
    public static function config(): array
    {
        return [
            'fields' => [
                'k2Authors' => [
                    'type' => [
                        'listOf' => 'User',
                    ],
                    'metadata' => [
                        'label' => 'K2 ' . trans('Authors'),
                        'view' => ['com_k2.item.latest'],
                        'group' => 'Page',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ]
            ],
        ];
    }

    public static function resolve($root)
    {
        if (isset($root['users'])) {
            return $root['users'];
        }
    }
}
