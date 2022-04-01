<?php

namespace YOOtheme\Source\K2;

use YOOtheme\Config;

class TemplateListener
{
    public static function matchTemplate($view)
    {
        if (static::isView($view, 'item')) {

            $item = $view->item;

            return [
                'type' => 'com_k2.item',
                'query' => [
                    // 'catid' => $item->getRelatedCategoryIds(true),
                    // 'tag' => $item->getTags(),
                ],
                'params' => [
                    'item' => $item,
                    // 'pagination' => function () use ($item) {
                    //     $element = $item->getElement('_itemprevnext');

                    //     if ($element && $links = $element->getValue()) {
                    //         return [
                    //             'previous' => $links['prev_link'] ? new PaginationObject(Text::_('JPREV'), '', null, $links['prev_link']) : null,
                    //             'next' => $links['next_link'] ? new PaginationObject(Text::_('JNEXT'), '', null, $links['next_link']) : null,
                    //         ];
                    //     }
                    // },
                ],
                // 'editUrl' => $item->canEdit()
                //     ? $item->app->route->submission($view->application->getItemEditSubmission(), $item->type, null, $item->id, 'itemedit')
                //     : null,
            ];

        }

        if (static::isView($view, 'category', 'itemlist') && !isset($view->category)) {

            return [
                'type' => 'com_k2.items',
                'query' => [
                ],
                'params' => [
                    'items' => array_merge($view->get('leading'), $view->get('primary'), $view->get('secondary'))
                ]
            ];
        }

        if (static::isView($view, 'category', 'itemlist') && isset($view->category)) {

            $category = $view->category;

            return [
                'type' => 'com_k2.category',
                'query' => [
                ],
                'params' => [
                    'category' => $category,
                    'items' => array_merge($view->get('leading'), $view->get('primary'), $view->get('secondary'))
                ]
            ];
        }

        if (static::isView($view, 'tag', 'itemlist')) {

            return [
                'type' => 'com_k2.tag',
                'query' => [
                ],
                'params' => [
                    'tag' => $view,
                    'items' => $view->items
                ]
            ];
        }

        if (static::isView($view, 'latest')) {

            return [
                'type' => 'com_k2.items',
                'query' => [
                ],
                'params' => [
                    'items' => array_reduce($view->blocks, function($res, $obj) {
                        return array_merge($res, $obj->items);
                    }, [])
                ]
            ];
        }
    }

    public static function initCustomizer(Config $config)
    {
        $templates = [

            'com_k2.item' => [
                'label' => 'Item',
                'group' => 'K2',
                'fieldset' => [
                    'default' => [
                        'fields' => [
                            'my_field' => [
                                'label' => 'My Field',
                                'description' => 'My field description ...',
                                'type' => 'text',
                            ],
                        ],
                    ],
                ],
            ],

            'com_k2.items' => [
                'label' => 'Items',
                'group' => 'K2'
            ],

            'com_k2.category' => [
                'label' => 'Category',
                'group' => 'K2'
            ],

            'com_k2.tag' => [
                'label' => 'Tag',
                'group' => 'K2'
            ],

        ];

        $config->add('customizer.templates', $templates);
    }

    protected static function isView($view, $task, $name = null)
    {
        return $view->getName() === ($name ?? $task) && $view->getLayout() === $task;
    }
}