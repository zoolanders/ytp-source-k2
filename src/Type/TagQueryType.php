<?php

namespace YOOtheme\Source\K2\Type;

use function YOOtheme\trans;

class TagQueryType
{
    public static function config(): array
    {
        return [
            'fields' => [
                'k2Tag' => [
                    'type' => 'K2Tag',
                    'metadata' => [
                        'label' => 'K2 ' . trans('Tag'),
                        'view' => ['com_k2.tag'],
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
        if (isset($root['tag'])) {
            return $root['tag'];
        }
    }
}
