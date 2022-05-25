<?php

namespace YOOtheme\Source\K2;

use function YOOtheme\trans;
use Joomla\CMS\Document\Document;
use YOOtheme\Config;
use YOOtheme\Source\K2\K2Helper;

class TemplateListener
{
    public static function matchTemplate(Document $document, $view)
    {
        if (static::isView($view, 'item')) {
            $item = $view->get('item');

            return [
                'type' => 'com_k2.item',
                'query' => [
                    'catid' => $item->category->id,
                    'tag' => array_column($item->tags ?? [], 'id'),
                    'lang' => $document->language,
                ],
                'params' => [
                    'item' => $item,
                ],
                // 'editUrl' => $item->canEdit()
                //     ? $item->app->route->submission($view->application->getItemEditSubmission(), $item->type, null, $item->id, 'itemedit')
                //     : null,
            ];
        }

        if (static::isView($view, 'category', 'itemlist')) {
            $pagination = $view->get('pagination');
            $categories = array_filter(array_map(function ($cat) {
                return K2Helper::getCategory($cat);
            }, $view->get('params')->get('categories', [])));

            return [
                'type' => 'com_k2.category',
                'query' => [
                    'catid' => array_map(function($cat) {
                        return $cat->id;
                    }, $categories),
                    'pages' => $pagination->pagesCurrent === 1 ? 'first' : 'except_first',
                    'lang' => $document->language,
                ],
                'params' => [
                    'categories' => $categories,
                    'items' => array_merge($view->get('leading'), $view->get('primary'), $view->get('secondary'), $view->get('links')),
                    'pagination' => $pagination,
                ]
            ];
        }

        if (static::isView($view, 'latest') && $view->get('source') === 'categories') {
            $categories = $view->get('blocks');

            return [
                'type' => 'com_k2.category.latest',
                'query' => [
                    'catid' => array_map(function($cat) {
                        return $cat->id;
                    }, $categories),
                    'lang' => $document->language,
                ],
                'params' => [
                    'categories' => $categories,
                    'items' => array_reduce($categories, function($res, $cat) {
                        return array_merge($res, $cat->items);
                    }, [])
                ]
            ];
        }

        if (static::isView($view, 'tag', 'itemlist')) {
            $pagination = $view->get('pagination');

            return [
                'type' => 'com_k2.tag',
                'query' => [
                    'pages' => $pagination->pagesCurrent === 1 ? 'first' : 'except_first',
                    'lang' => $document->language,
                ],
                'params' => [
                    'tag' => $view,
                    'items' => $view->get('items'),
                    'pagination' => $pagination,
                ],
            ];
        }

        if (static::isView($view, 'latest') && $view->get('source') === 'users') {
            $users = $view->get('blocks');

            return [
                'type' => 'com_k2.item.latest',
                'query' => [
                    'lang' => $document->language,
                ],
                'params' => [
                    'users' => $users,
                    'items' => array_reduce($users, function($res, $user) {
                        return array_merge($res, $user->items);
                    }, [])
                ]
            ];
        }
    }

    public static function initCustomizer(Config $config)
    {
        $languageField = [
            'label' => trans('Limit by Language'),
            'type' => 'select',
            'defaultIndex' => 0,
            'options' => [['evaluate' => 'config.languages']],
            'show' => '$customizer.languages[\'length\'] > 2 || lang',
        ];

        $templates = [

            'com_k2.item' => [
                'label' => trans('Single Item'),
                'group' => 'K2',
                'fieldset' => [
                    'default' => [
                        'fields' => [
                            'catid' => ($category = [
                                'label' => trans('Limit by Categories'),
                                'description' => trans(
                                    'The template is only assigned to items from the selected categories. Items from child categories are not included. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple categories.'
                                ),
                                'type' => 'select',
                                'default' => [],
                                'options' => [['evaluate' => 'config.k2categories']],
                                'attrs' => [
                                    'multiple' => true,
                                    'class' => 'uk-height-small',
                                ],
                            ]),
                            'tag' => ($tag = [
                                'label' => trans('Limit by Tags'),
                                'description' => trans(
                                    'The template is only assigned to item with the selected tags. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple tags.'
                                ),
                                'type' => 'select',
                                'default' => [],
                                'options' => [['evaluate' => 'config.k2tags']],
                                'attrs' => [
                                    'multiple' => true,
                                    'class' => 'uk-height-small',
                                ],
                            ]),
                            'lang' => $languageField,
                        ],
                    ],
                ],
            ],

            'com_k2.category' => [
                'label' => trans('Category Items'),
                'group' => 'K2',
                'fieldset' => [
                    'default' => [
                        'fields' => [
                            'catid' =>
                                [
                                    'label' => trans('Limit by Categories'),
                                    'description' => trans(
                                        'The template is only assigned to the selected categories. Child categories are not included. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple categories.'
                                    ),
                                ] + $category,
                            'pages' => [
                                'label' => trans('Limit by Page Number'),
                                'description' => trans(
                                    'The template is only assigned to the selected pages.'
                                ),
                                'type' => 'select',
                                'options' => [
                                    trans('All pages') => '',
                                    trans('First page') => 'first',
                                    trans('All except first page') => 'except_first',
                                ],
                            ],
                            'lang' => $languageField,
                        ],
                    ],
                ],
            ],

            'com_k2.category.latest' => [
                'label' => trans('Category Latest Items'),
                'group' => 'K2',
                'fieldset' => [
                    'default' => [
                        'fields' => [
                            'catid' =>
                                [
                                    'label' => trans('Limit by Categories'),
                                    'description' => trans(
                                        'The template is only assigned to the selected categories. Child categories are not included. Use the <kbd>shift</kbd> or <kbd>ctrl/cmd</kbd> key to select multiple categories.'
                                    ),
                                ] + $category,
                            'lang' => $languageField,
                        ],
                    ],
                ],
            ],

            'com_k2.tag' => [
                'label' => trans('Tagged Items'),
                'group' => 'K2',
                'fieldset' => [
                    'default' => [
                        'fields' => [
                            'pages' => [
                                'label' => trans('Limit by Page Number'),
                                'description' => trans(
                                    'The template is only assigned to the selected pages.'
                                ),
                                'type' => 'select',
                                'options' => [
                                    trans('All pages') => '',
                                    trans('First page') => 'first',
                                    trans('All except first page') => 'except_first',
                                ],
                            ],
                            'lang' => $languageField,
                        ],
                    ],
                ],
            ],

            'com_k2.item.latest' => [
                'label' => trans('Latest Items'),
                'group' => 'K2',
                'fieldset' => [
                    'default' => [
                        'fields' => [
                            'lang' => $languageField,
                        ],
                    ],
                ],
            ],

        ];

        $config->add('customizer.templates', $templates);

        $config->add(
            'customizer.k2tags',
            array_map(function ($tag) {
                return ['value' => (string) $tag->id, 'text' => $tag->name];
            }, K2Helper::getTags())
        );

        $config->add(
            'customizer.k2categories',
            array_map(function ($cat) {
                return ['value' => (string) $cat->value, 'text' => $cat->text];
            }, K2Helper::getCategoriesTree())
        );
    }

    protected static function isView($view, $task, $name = null)
    {
        return $view->getName() === ($name ?? $task) && $view->getLayout() === $task;
    }
}