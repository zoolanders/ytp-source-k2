<?php

namespace YOOtheme\Source\K2\Type;

use function YOOtheme\trans;
use YOOtheme\Source\K2\K2Helper;
use YOOtheme\Source\K2\K2ItemsHelper;

class CustomItemsQueryType
{
    public static function config(): array
    {
        return [
            'fields' => [
                'customK2Items' => [
                    'type' => [
                        'listOf' => 'K2Item',
                    ],
                    'args' => [
                        'id' => [
                            'type' => 'String',
                        ],
                        'catid' => [
                            'type' => [
                                'listOf' => 'String',
                            ],
                        ],
                        'tags' => [
                            'type' => [
                                'listOf' => 'String',
                            ],
                        ],
                        'users' => [
                            'type' => [
                                'listOf' => 'String',
                            ],
                        ],
                        'featured' => [
                            'type' => 'Boolean',
                        ],
                        'order' => [
                            'type' => 'String',
                        ],
                        'order_timerange' => [
                            'type' => 'String',
                        ],
                        'offset' => [
                            'type' => 'Int',
                        ],
                        'limit' => [
                            'type' => 'Int',
                        ],
                    ],

                    'metadata' => [
                        'label' => 'K2 ' . trans('Items'),
                        'group' => 'K2',
                        'fields' => [
                            'catid' => [
                                'label' => trans('Filter by Categories'),
                                'type' => 'select',
                                'default' => [],
                                'options' => [['evaluate' => 'config.k2categories']],
                                'attrs' => [
                                    'multiple' => true,
                                    'class' => 'uk-height-small',
                                ],
                            ],
                            'tags' => [
                                'label' => trans('Filter by Tags'),
                                'type' => 'select',
                                'default' => [],
                                'options' => [['evaluate' => 'config.k2tags']],
                                'attrs' => [
                                    'multiple' => true,
                                    'class' => 'uk-height-small',
                                ],
                            ],
                            'users' => [
                                'label' => trans('Filter by Users'),
                                'type' => 'select',
                                'default' => [],
                                'options' => [['evaluate' => 'config.users']],
                                'attrs' => [
                                    'multiple' => true,
                                    'class' => 'uk-height-small',
                                ],
                            ],
                            'featured' => [
                                'label' => trans('Limit by Featured Articles'),
                                'type' => 'checkbox',
                                'text' => trans('Load featured articles only'),
                            ],
                            '_offset_limit' => [
                                'description' => trans(
                                    'Set the starting point and limit the number of articles.'
                                ),
                                'type' => 'grid',
                                'width' => '1-2',
                                'fields' => [
                                    'offset' => [
                                        'label' => trans('Start'),
                                        'type' => 'number',
                                        'default' => 0,
                                        'modifier' => 1,
                                        'attrs' => [
                                            'min' => 1,
                                            'required' => true,
                                        ],
                                    ],
                                    'limit' => [
                                        'label' => trans('Quantity'),
                                        'type' => 'limit',
                                        'default' => 10,
                                        'attrs' => [
                                            'min' => 1,
                                        ],
                                    ],
                                ],
                            ],
                            'order' => [
                                'label' => trans('Order'),
                                'type' => 'select',
                                'default' => '',
                                'options' => [
                                    'Default' => '',
                                    'Oldest first (by date created)' => 'date',
                                    'Most recent first (by date created)' => 'rdate',
                                    'Most recent first (by date published)' => 'publishUp',
                                    'Title Alphabetical' => 'alpha',
                                    'Title Reverse-Alphabetical' => 'ralpha',
                                    'Ordering' => 'order',
                                    'Ordering reverse' => 'rorder',
                                    'Most popular' => 'hits',
                                    'Highest rated' => 'best',
                                    'Most commented' => 'comments',
                                    'Latest modified' => 'modified',
                                    'Random ordering' => 'rand',
                                ],
                            ],
                            'order_timerange' => [
                                'label' => 'Order Time Range',
                                'type' => 'select',
                                'default' => '',
                                'options' => [
                                    'All Time' => '',
                                    trans('Today') => 'today',
                                    '1 Day' => '1',
                                    '3 Days' => '3',
                                    '1 Week' => '7',
                                    '2 Weeks' => '15',
                                    '1 Month' => '30',
                                    '3 Months' => '90',
                                    '6 Months' => '180',
                                ],
                                'show' => "order === 'hits' || order === 'comments'"
                            ],
                        ],
                    ],

                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],
            ],
        ];
    }

    public static function resolve($root, array $args)
    {
        if (!empty($args['catid'])) {
            $args += ['catfilter' => true, 'category_id' => $args['catid']];
            unset($args['catid']);
        }

        if (!empty($args['featured'])) {
            $args += ['FeaturedItems' => true];
            unset($args['featured']);
        }

        if (!empty($args['order'])) {
            $args += ['itemsOrdering' => $args['order']];
            unset($args['order']);
        }

        if (!empty($args['limit'])) {
            $args += ['itemCount' => $args['limit']];
            unset($args['limit']);
        }

        if (!empty($args['offset'])) {
            $args += ['limitstart' => $args['offset']];
            unset($args['offset']);
        }

        if (!empty($args['order_timerange'])) {
            $args += ['popularityRange' => $args['order_timerange']];
            unset($args['order_timerange']);
        }

        $items = K2ItemsHelper::getItems($args);

        // resolve categories
        foreach ($items as $item) {
            $item->category = K2Helper::getCategory($item->categoryid);
        }

        return $items;
    }
}
