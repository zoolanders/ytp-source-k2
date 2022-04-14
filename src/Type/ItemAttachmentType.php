<?php

namespace YOOtheme\Source\K2\Type;

use function YOOtheme\trans;

class ItemAttachmentType
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
                'titleAttribute' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Title Attribute'),
                        'filters' => ['limit'],
                    ],
                ],
                'hits' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => trans('Hits'),
                    ]
                ],
                'filename' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Filename'),
                    ]
                ],
                'link' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Link'),
                    ]
                ],
                'id' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('ID'),
                    ]
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => 'K2 ' . trans('Attachment'),
            ],
        ];
    }
}
