<?php

require_once __DIR__ . '/src/K2Helper.php';
require_once __DIR__ . '/src/SourceListener.php';
require_once __DIR__ . '/src/TemplateListener.php';
require_once __DIR__ . '/src/Type/TagType.php';
require_once __DIR__ . '/src/Type/EventType.php';
require_once __DIR__ . '/src/Type/ItemType.php';
require_once __DIR__ . '/src/Type/ItemEventType.php';
require_once __DIR__ . '/src/Type/ItemAttachmentType.php';
require_once __DIR__ . '/src/Type/ItemQueryType.php';
require_once __DIR__ . '/src/Type/ItemsQueryType.php';
require_once __DIR__ . '/src/Type/CategoryType.php';
require_once __DIR__ . '/src/Type/CategoryQueryType.php';
require_once __DIR__ . '/src/Type/TagQueryType.php';
require_once __DIR__ . '/src/Type/FieldsType.php';

return [

    'events' => [

        'builder.template' => [
            \YOOtheme\Source\K2\TemplateListener::class => 'matchTemplate',
        ],

        'customizer.init' => [
            \YOOtheme\Source\K2\TemplateListener::class => [
                ['initCustomizer'],
            ],
        ],

        'source.init' => [
            \YOOtheme\Source\K2\SourceListener::class => 'initSource',
        ],

    ]


];
