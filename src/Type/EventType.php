<?php

namespace YOOtheme\Source\K2\Type;

use function YOOtheme\trans;

class EventType
{
    public static function config(): array
    {
        return [
            'fields' => [
                'afterDisplayTitle' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('After Display Title'),
                    ],
                    'extensions' => [
                        'call' => get_called_class() . '::resolve',
                    ],
                ],

                'beforeDisplayContent' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Before Display Content'),
                    ],
                    'extensions' => [
                        'call' => get_called_class() . '::resolve',
                    ],
                ],

                'afterDisplayContent' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('After Display Content'),
                    ],
                    'extensions' => [
                        'call' => get_called_class() . '::resolve',
                    ],
                ],
            ],

            'metadata' => [
                'label' => trans('Events'),
            ],
        ];
    }

    public static function resolve($item, $args, $context, $info)
    {
        $key = $info->fieldName;

        if (isset($item->event->$key)) {
            return $item->event->$key;
        }
    }
}
