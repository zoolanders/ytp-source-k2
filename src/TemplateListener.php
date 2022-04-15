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

            $item = $view->item;

            return [
                'type' => 'com_k2.item',
                'query' => [
                    'catid' => $item->category->id,
                    'tag' => array_column($item->tags, 'id'),
                    'lang' => $document->language,
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