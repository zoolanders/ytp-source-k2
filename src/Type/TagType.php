<?php

namespace YOOtheme\Source\K2\Type;

use function YOOtheme\trans;

class TagType
{
    public static function config(): array
    {
        return [
            'fields' => [
                'name' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Title'),
                        'filters' => ['limit'],
                    ],
                ],

                'link' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Link'),
                    ]
                ],

                // 'id' => [
                //     'type' => 'Int',
                //     'metadata' => [
                //         'label' => trans('ID')
                //     ]
                // ],
            ],

            'metadata' => [
                'type' => true,
                'label' => 'K2 ' . trans('Tag'),
            ],
        ];
    }
}
