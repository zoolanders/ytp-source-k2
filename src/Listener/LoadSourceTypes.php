<?php

namespace YOOtheme\Source\K2\Listener;

use function YOOtheme\trans;
use YOOtheme\Builder\Source;
use YOOtheme\Source\K2\Type;
use YOOtheme\Source\K2\K2Helper;
use Joomla\CMS\Component\ComponentHelper;

class LoadSourceTypes
{
    /**
     * @param Source $source
     */
    public function handle($source): void
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
            Type\ItemQueryType::config(),
            Type\ItemsQueryType::config(),
            Type\AuthorsQueryType::config(),
            Type\CategoriesQueryType::config(),
            Type\CustomItemQueryType::config(),
            Type\CustomItemsQueryType::config(),
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

        if (ComponentHelper::getComponent('com_sigpro', true)->enabled) {
            $source->objectType('K2Item', Type\GalleryType::config());
            $source->objectType('K2GalleryItem', Type\GalleryItemType::config());
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
