<?php

namespace YOOtheme\Source\K2\Type;

use function YOOtheme\trans;

class CategoryQueryType
{
    public static function config(): array
    {
        return [
            'fields' => [
                'k2Category' => [
                    'type' => 'K2Category',
                    'metadata' => [
                        'label' => 'K2 ' . trans('Category'),
                        'view' => ['com_k2.category'],
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
        if (isset($root['category'])) {
            return $root['category'];
        }
    }
}
