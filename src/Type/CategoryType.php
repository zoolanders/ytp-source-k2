<?php

namespace YOOtheme\Source\K2\Type;

use function YOOtheme\trans;
use YOOtheme\Source\K2\K2Helper;

class CategoryType
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
                    ]
                ],

                'description' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Description'),
                        'filters' => ['limit'],
                    ],
                ],

                'numitems' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Item Count'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::numitems',
                    ],
                ],

                'image' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Image'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::image',
                    ],
                ],

                'link' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Link'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::link',
                    ],
                ],

                'parent' => [
                    'type' => 'K2Category',
                    'metadata' => [
                        'label' => 'K2 ' . trans('Parent Category'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::parent',
                    ],
                ],

                'categories' => [
                    'type' => [
                        'listOf' => 'K2Category',
                    ],
                    'metadata' => [
                        'label' => trans('Child Categories'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::categories',
                    ],
                ],

                'id' => [
                    'type' => 'Int',
                    'metadata' => [
                        'label' => trans('ID')
                    ]
                ],
            ],

            'metadata' => [
                'type' => true,
                'label' => 'K2 ' . trans('Category'),
            ],
        ];
    }

    public static function numitems($category)
    {
        return K2Helper::countCategoryItems($category->id);
    }

    public static function image($category)
    {
        return K2Helper::getCategoryImage($category);
    }

    public static function link($category)
    {
        return $category->link;
    }

    public static function parent($category)
    {
        return K2Helper::getCategory($category->parent);
    }

    public static function categories($category)
    {
        return K2Helper::getCategoryFirstChildren($category->id);
    }
}
