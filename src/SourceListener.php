<?php

namespace YOOtheme\Source\K2;

use function YOOtheme\trans;

class SourceListener
{
    public static function initSource($source)
    {
        $types = [
            ['K2Tag', Type\TagType::config()],
            ['K2Item', Type\ItemType::config()],
            ['K2ItemEvent', Type\ItemEventType::config()],
            ['K2ItemAttachment', Type\ItemAttachmentType::config()],
            ['K2Category', Type\CategoryType::config()],
        ];

        $query = [
            Type\TagQueryType::config(),
            Type\CategoryQueryType::config(),
            Type\ItemQueryType::config(),
            Type\ItemsQueryType::config(),
        ];

        foreach ($query as $args) {
            $source->queryType($args);
        }

        foreach ($types as $args) {
            $source->objectType(...$args);
        }

        foreach (K2Helper::getExtraFieldsGroups() as $group) {
            $fields = K2Helper::getExtraFieldsByGroup($group->id);
            static::configFields($source, 'K2Item', $group, $fields);
        }
    }

    protected static function configFields($source, $type, $group, array $fields)
    {
        // add field on type
        $source->objectType(
            $type,
            [
                'fields' => [
                    'field' => [
                        'type' => ($fieldType = "{$type}Fields"),
                        'metadata' => [
                            'label' => trans('Fields'),
                        ],
                        'extensions' => [
                            'call' => Type\FieldsType::class . '::field',
                        ],
                    ],
                ],
            ]
        );

        // configure field type
        $source->objectType($fieldType, Type\FieldsType::config($source, $type, $group, $fields));
    }
}
