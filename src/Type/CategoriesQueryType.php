<?php

namespace YOOtheme\Source\K2\Type;

use function YOOtheme\trans;

class CategoriesQueryType
{
    public static function config(): array
    {
        return [
            'fields' => [
                'k2Categories' => [
                    'type' => [
                        'listOf' => 'K2Category',
                    ],
                    'metadata' => [
                        'label' => 'K2 ' . trans('Categories'),
                        'view' => ['com_k2.category', 'com_k2.category.latest'],
                        'group' => 'Page',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root)
    {
        if (isset($root['categories'])) {
            return $root['categories'];
        }
    }
}
