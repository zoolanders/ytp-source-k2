<?php

namespace YOOtheme\Source\K2;

require_once __DIR__ . '/src/K2Helper.php';
require_once __DIR__ . '/src/K2ItemsHelper.php';

require_once __DIR__ . '/src/Listener/LoadBuilderConfig.php';
require_once __DIR__ . '/src/Listener/LoadSourceTypes.php';
require_once __DIR__ . '/src/Listener/MatchTemplate.php';

require_once __DIR__ . '/src/Type/TagType.php';
require_once __DIR__ . '/src/Type/EventType.php';
require_once __DIR__ . '/src/Type/ItemType.php';
require_once __DIR__ . '/src/Type/ItemEventType.php';
require_once __DIR__ . '/src/Type/ItemAttachmentType.php';
require_once __DIR__ . '/src/Type/ItemQueryType.php';
require_once __DIR__ . '/src/Type/ItemsQueryType.php';
require_once __DIR__ . '/src/Type/CategoryType.php';
require_once __DIR__ . '/src/Type/CategoriesQueryType.php';
require_once __DIR__ . '/src/Type/TagQueryType.php';
require_once __DIR__ . '/src/Type/AuthorsQueryType.php';
require_once __DIR__ . '/src/Type/FieldsType.php';
require_once __DIR__ . '/src/Type/GalleryType.php';
require_once __DIR__ . '/src/Type/GalleryItemType.php';
require_once __DIR__ . '/src/Type/CustomItemQueryType.php';
require_once __DIR__ . '/src/Type/CustomItemsQueryType.php';

use YOOtheme\Builder\BuilderConfig;

return [

    'events' => [
        'source.init' => [Listener\LoadSourceTypes::class => '@handle'],
        'builder.template' => [Listener\MatchTemplate::class => '@handle'],
        BuilderConfig::class => [Listener\LoadBuilderConfig::class => '@handle'],
    ]

];
