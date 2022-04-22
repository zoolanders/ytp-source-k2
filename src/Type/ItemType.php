<?php

namespace YOOtheme\Source\K2\Type;

use function YOOtheme\app;
use function YOOtheme\trans;
use Joomla\CMS\Factory;
use YOOtheme\File;
use YOOtheme\Path;
use YOOtheme\View;

class ItemType
{
    public static function config(): array
    {
        return [
            'fields' => [
                'title' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Title'),
                        'filters' => ['limit'],
                    ],
                ],

                'alias' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Alias'),
                        'filters' => ['limit'],
                    ],
                ],

                'content' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Content'),
                        'filters' => ['limit'],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::content',
                    ],
                ],

                'teaser' => [
                    'type' => 'String',
                    'args' => [
                        // 'show_excerpt' => [
                        //     'type' => 'Boolean',
                        // ],
                    ],
                    'metadata' => [
                        'label' => trans('Teaser'),
                        'arguments' => [
                        //     'show_excerpt' => [
                        //         'label' => trans('Excerpt'),
                        //         'description' => trans(
                        //             'Display the excerpt field if it has content, otherwise the intro text. To use an excerpt field, create a custom field with the name excerpt.'
                        //         ),
                        //         'type' => 'checkbox',
                        //         'default' => true,
                        //         'text' => 'Prefer excerpt over intro text',
                        //     ],
                        ],
                        'filters' => ['limit'],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::teaser',
                    ],
                ],

                'image' => [
                    'type' => 'String',
                    'args' => [
                        'size' => [
                            'type' => 'String',
                        ]
                    ],
                    'metadata' => [
                        'label' => trans('Image'),
                        'arguments' => [
                            'size' => [
                                'label' => trans('Size'),
                                'description' => trans('Choose the preferred image size.'),
                                'type' => 'select',
                                'default' => '',
                                'options' => [
                                    trans('Default') => '',
                                    trans('Inherit Leading Size') => 'leadingImgSize',
                                    trans('Inherit Primary Size') => 'primaryImgSize',
                                    trans('Inherit Secondary Size') => 'secondaryImgSize',
                                    trans('Extra Small') => 'XSmall',
                                    trans('Small') => 'Small',
                                    trans('Medium') => 'Medium',
                                    trans('Large') => 'Large',
                                    trans('Extra Large') => 'XLarge',
                                ],
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::imageUrl',
                    ],
                ],

                'image_caption' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Image Caption'),
                        'filters' => ['limit'],
                    ],
                ],

                'image_credits' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Image Credits'),
                        'filters' => ['limit'],
                    ],
                ],

                'video' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Media'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolveMedia',
                    ],
                ],

                'video_caption' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Media Caption'),
                        'filters' => ['limit'],
                    ],
                ],

                'video_credits' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Media Credits'),
                        'filters' => ['limit'],
                    ],
                ],

                'featured' => [
                    'type' => 'Boolean',
                    'metadata' => [
                        'label' => trans('Featured')
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::featured',
                    ],
                ],

                'publish_up' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Published'),
                        'filters' => ['date'],
                    ],
                ],

                'created' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Created'),
                        'filters' => ['date'],
                    ],
                ],

                'modified' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Modified'),
                        'filters' => ['date'],
                    ],
                ],

                'metaString' => [
                    'type' => 'String',
                    'args' => [
                        'format' => [
                            'type' => 'String',
                        ],
                        'separator' => [
                            'type' => 'String',
                        ],
                        'link_style' => [
                            'type' => 'String',
                        ],
                        'show_publish_date' => [
                            'type' => 'Boolean',
                        ],
                        'show_author' => [
                            'type' => 'Boolean',
                        ],
                        'show_taxonomy' => [
                            'type' => 'String',
                        ],
                        'date_format' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => trans('Meta'),
                        'arguments' => [
                            'format' => [
                                'label' => trans('Format'),
                                'description' => trans(
                                    'Display the meta text in a sentence or a horizontal list.'
                                ),
                                'type' => 'select',
                                'default' => 'list',
                                'options' => [
                                    trans('List') => 'list',
                                    trans('Sentence') => 'sentence',
                                ],
                            ],
                            'separator' => [
                                'label' => trans('Separator'),
                                'description' => trans('Set the separator between fields.'),
                                'default' => '|',
                                'enable' => 'arguments.format === "list"',
                            ],
                            'link_style' => [
                                'label' => trans('Link Style'),
                                'description' => trans('Set the link style.'),
                                'type' => 'select',
                                'default' => '',
                                'options' => [
                                    'Default' => '',
                                    'Muted' => 'link-muted',
                                    'Text' => 'link-text',
                                    'Heading' => 'link-heading',
                                    'Reset' => 'link-reset',
                                ],
                            ],
                            'show_publish_date' => [
                                'label' => trans('Display'),
                                'description' => trans('Show or hide fields in the meta text.'),
                                'type' => 'checkbox',
                                'default' => true,
                                'text' => trans('Show date'),
                            ],
                            'show_author' => [
                                'type' => 'checkbox',
                                'default' => true,
                                'text' => trans('Show author'),
                            ],
                            'show_taxonomy' => [
                                'type' => 'select',
                                'default' => 'category',
                                'options' => [
                                    trans('Hide Term List') => '',
                                    trans('Show Category') => 'category',
                                    trans('Show Tags') => 'tag',
                                ],
                            ],
                            'date_format' => [
                                'label' => trans('Date Format'),
                                'description' => trans(
                                    'Select a predefined date format or enter a custom format.'
                                ),
                                'type' => 'data-list',
                                'default' => '',
                                'options' => [
                                    'Aug 6, 1999 (M j, Y)' => 'M j, Y',
                                    'August 06, 1999 (F d, Y)' => 'F d, Y',
                                    '08/06/1999 (m/d/Y)' => 'm/d/Y',
                                    '08.06.1999 (m.d.Y)' => 'm.d.Y',
                                    '6 Aug, 1999 (j M, Y)' => 'j M, Y',
                                    'Tuesday, Aug 06 (l, M d)' => 'l, M d',
                                ],
                                'enable' => 'arguments.show_publish_date',
                                'attrs' => [
                                    'placeholder' => 'Default',
                                ],
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::metaString',
                    ],
                ],

                'tagString' => [
                    'type' => 'String',
                    'args' => [
                        'separator' => [
                            'type' => 'String',
                        ],
                        'show_link' => [
                            'type' => 'Boolean',
                        ],
                        'link_style' => [
                            'type' => 'String',
                        ],
                    ],
                    'metadata' => [
                        'label' => trans('Tags'),
                        'arguments' => [
                            'separator' => [
                                'label' => trans('Separator'),
                                'description' => trans('Set the separator between tags.'),
                                'default' => ', ',
                            ],
                            'show_link' => [
                                'label' => trans('Link'),
                                'type' => 'checkbox',
                                'default' => true,
                                'text' => trans('Show link'),
                            ],
                            'link_style' => [
                                'label' => trans('Link Style'),
                                'description' => trans('Set the link style.'),
                                'type' => 'select',
                                'default' => '',
                                'options' => [
                                    'Default' => '',
                                    'Muted' => 'link-muted',
                                    'Text' => 'link-text',
                                    'Heading' => 'link-heading',
                                    'Reset' => 'link-reset',
                                ],
                                'enable' => 'arguments.show_link',
                            ],
                        ],
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::tagString',
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

                'hits' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Hits'),
                    ],
                ],

                'event' => [
                    'type' => 'K2ItemEvent',
                    'metadata' => [
                        'label' => trans('Events'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::event',
                    ],
                ],

                'category' => [
                    'type' => 'K2Category',
                    'metadata' => [
                        'label' => 'K2 ' . trans('Category'),
                    ]
                ],

                'author' => [
                    'type' => 'User',
                    'metadata' => [
                        'label' => trans('Author'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::author',
                    ],
                ],

                'tags' => [
                    'type' => [
                        'listOf' => 'K2Tag',
                    ],
                    'metadata' => [
                        'label' => trans('Tags'),
                    ]
                ],

                'rating' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Rating'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::rating',
                    ],
                ],

                'rating_count' => [
                    'type' => 'String',
                    'metadata' => [
                        'label' => trans('Votes'),
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::ratingCount',
                    ],
                ],

                'attachments' => [
                    'type' => [
                        'listOf' => 'K2ItemAttachment',
                    ],
                    'metadata' => [
                        'label' => trans('Attachments')
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
                'label' => 'K2 ' . trans('Item'),
            ],
        ];
    }

    public static function content($item): string
    {
        // if (
        //     !$item->params->get('access-view') &&
        //     $item->params->get('show_noauth') &&
        //     Factory::getUser()->get('guest')
        // ) {
        //     return $item->introtext;
        // }

        // if (isset($item->text)) {
        //     return (!empty($item->toc) ? $item->toc : '') . $item->text;
        // }

        if ($item->params->get('itemIntroText', '1') === '1') {
            return "{$item->introtext} {$item->fulltext}";
        }

        if ($item->fulltext) {
            return $item->fulltext;
        }

        return $item->introtext;
    }

    public static function teaser($item, $args): string
    {
        // $args += ['show_excerpt' => true];

        // if (
        //     $args['show_excerpt'] &&
        //     // ($field = FieldsType::getField('excerpt', $item, 'com_k2.item')) &&
        //     Str::length($field->rawvalue)
        // ) {
        //     return $field->rawvalue;
        // }

        return $item->introtext;
    }

    public static function featured($item): bool
    {
        return (bool) $item->featured;
    }

    public static function link($item): string
    {
        return \K2HelperRoute::getItemRoute($item->id, $item->catid);
    }

    public static function author($item)
    {
        $user = Factory::getUser($item->created_by);

        if ($item->created_by_alias && $user) {
            $user = clone $user;
            $user->name = $item->created_by_alias;
        }

        return $user;
    }

    public static function rating($item)
    {
        $model = new \K2ModelItem();
        $vote = $model->getRating($item->id);

        if (!is_null($vote) && $vote->rating_count !== 0) {
            $votes = intval($vote->rating_sum) / intval($vote->rating_count);
            return number_format((double) $votes, 1);
        }

        return 0;
    }

    public static function ratingCount($item, $args): int
    {
        $model = new \K2ModelItem();
        $vote = $model->getRating($item->id);

        if (!is_null($vote)) {
            return intval($vote->rating_count);
        }

        return 0;
    }

    public static function event($item)
    {
        return $item;
    }

    public static function tagString($item, array $args): string
    {
        $tags = $item->tags;
        $args += ['separator' => ', ', 'show_link' => true, 'link_style' => ''];

        return app(View::class)->render(Path::get('../../templates/tags'), compact('tags', 'args'));
    }

    public static function metaString($item, array $args): string
    {
        $args += [
            'format' => 'list',
            'separator' => '|',
            'link_style' => '',
            'show_publish_date' => true,
            'show_author' => true,
            'show_taxonomy' => 'category',
            'date_format' => '',
        ];

        $tags = $args['show_taxonomy'] === 'tag' ? $item->tags : null;

        return app(View::class)->render(
            Path::get('../../templates/meta'),
            compact('item', 'tags', 'args')
        );
    }

    public static function imageUrl($item, array $args): ?string
    {
        $size = $args['size'] ?? '';
        $hash = md5("Image{$item->id}");
        $src = "media/k2/items/src/$hash.jpg";

        if (!File::exists($src)) {
            return null;
        }

        if (!$size) {
            return $src;
        }

        if ($inherited = $item->params->get($size)) {
            $size = $inherited;
        }

        if (!in_array($size, ['XSmall', 'Small', 'Medium', 'Large', 'Xlarge'])) {
            $size = 'Generic';
        }

        $cache = "media/k2/items/cache/{$hash}_{$size}.jpg";

        if (File::exists($cache)) {
            return $cache;
        }

        return null;
    }

    public static function resolveMedia($item, array $args): ?string
    {
        $src = "media/k2/{videos,audio}/$item->id.*";

        $medias = File::glob($src);

        if (!empty($medias)) {
            return array_shift($medias);
        }

        return $item->video;
    }
}
