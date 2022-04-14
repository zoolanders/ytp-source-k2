<?php

namespace YOOtheme\Source\K2\Type;

use function YOOtheme\trans;

class GalleryItemType
{
    public static function config(): array
    {
        return [
            'fields' => [
                'title' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Title'),
                        'filters' => ['limit'],
                    ],
                ],
                'description' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Description'),
                        'filters' => ['limit'],
                    ],
                ],
                'size' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Size'),
                    ]
                ],
                'width' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Width'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::width',
                    ],
                ],
                'height' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Height'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::height',
                    ],
                ],
                'url' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('URL'),
                    ]
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => 'K2 ' . trans('Gallery Item'),
            ],
        ];
    }

    public static function width($item): int
    {
        return explode('x', $item->dimensions)[0] ?? 0;
    }

    public static function height($item): int
    {
        return explode('x', $item->dimensions)[1] ?? 0;
    }
}
