<?php

namespace YOOtheme\Source\K2\Type;

use function YOOtheme\trans;

class ItemQueryType
{
    public static function config(): array
    {
        return [
            'fields' => [
                'k2Item' => [
                    'type' => 'K2Item',
                    'metadata' => [
                        'label' => 'K2 ' . trans('Item'),
                        'view' => ['com_k2.item'],
                        'group' => 'Page',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],
                'k2PrevItem' => [
                    'type' => 'K2Item',
                    'metadata' => [
                        'label' => 'K2 ' . trans('Previous Item'),
                        'view' => ['com_k2.item'],
                        'group' => 'Page',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolvePreviousItem',
                    ],
                ],
                'k2NextItem' => [
                    'type' => 'K2Item',
                    'metadata' => [
                        'label' => 'K2 ' . trans('Next Item'),
                        'view' => ['com_k2.item'],
                        'group' => 'Page',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveNextItem',
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root)
    {
        if (isset($root['item'])) {
            return $root['item'];
        }
    }

    public static function resolvePreviousItem($root)
    {
        $item = static::resolve($root);

        if (!$item) {
            return;
        }

        return $item->previous ?? null;
    }

    public static function resolveNextItem($root)
    {
        $item = static::resolve($root);

        if (!$item) {
            return;
        }

        return $item->next ?? null;
    }
}
