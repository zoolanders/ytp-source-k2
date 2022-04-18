<?php

namespace YOOtheme\Source\K2\Type;

use function YOOtheme\trans;
use YOOtheme\Source\K2\K2Helper;

class CustomItemQueryType
{
    public static function config(): array
    {
        $itemsConfig = CustomItemsQueryType::config()['fields']['customK2Items'];

        return [
            'fields' => [
                'customK2Item' => array_merge($itemsConfig, [
                    'type' => 'K2Item',

                    'args' => [
                        'id' => [
                            'type' => 'String',
                        ]
                     ] + $itemsConfig['args'],

                    'metadata' => [
                        'label' => 'K2 ' . trans('Item'),
                        'group' => 'K2',
                        'fields' => [
                            'id' => [
                                'label' => trans('Set Manually'),
                                'description' => trans(
                                    'Set an item id manually or use filter options to specify which article should be loaded dynamically.'
                                )
                            ],
                        ] + array_map(function($field) {
                            return $field + ['show' => '!id'];
                        }, $itemsConfig['metadata']['fields'])
                    ],

                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ]),
            ],
        ];
    }

    public static function resolve($root, array $args)
    {
        if (!empty($args['id'])) {
            $items = K2Helper::getModalItems([
                'source' => 'specific',
                'items' => [$args['id']]
            ]);
        } else {
            $items = CustomItemsQueryType::resolve($root, $args);
        }

        return is_array($items) ? array_shift($items) : null;
    }
}
