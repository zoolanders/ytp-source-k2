<?php

namespace YOOtheme\Source\K2\Listener;

use function YOOtheme\trans;
use YOOtheme\Builder\BuilderConfig;
use YOOtheme\Source\K2\K2Helper;

class LoadBuilderConfig
{
    /**
     * @param BuilderConfig $config
     */
    public function handle($config): void
    {
        $languageField = [
            'label' => trans('Limit by Language'),
            'type' => 'select',
            'defaultIndex' => 0,
            'options' => [['evaluate' => 'yootheme.builder.languages']],
            'show' => 'yootheme.builder.languages.length > 2 || lang',
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
                                'options' => [['evaluate' => 'yootheme.builder["com_k2.categories"]']],
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
                                'options' => [['evaluate' => 'yootheme.builder["com_k2.tags"]']],
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

        $config->merge([
            'templates' => $templates,

            'com_k2.tags' => array_map(function ($tag) {
                return ['value' => (string) $tag->id, 'text' => $tag->name];
            }, K2Helper::getTags()),

            'com_k2.categories' => array_map(function ($cat) {
                return ['value' => (string) $cat->value, 'text' => $cat->text];
            }, K2Helper::getCategoriesTree())
        ]);
    }
}
