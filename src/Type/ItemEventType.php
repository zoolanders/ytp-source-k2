<?php

namespace YOOtheme\Source\K2\Type;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use YOOtheme\Builder\Joomla\Source\Type\EventType;

class ItemEventType extends EventType
{
    public static function resolve($item, $args, $context, $info)
    {
        $key = $info->fieldName;

        if (isset($item->event->$key)) {
            return $item->event->$key;
        }

        $marker = "<!-- article_{$item->id}_{$key} -->";

        Factory::getApplication()->registerEvent('onBeforeRender', function () use (
            $item,
            $key,
            $marker
        ) {
            if (!isset($item->event->$key)) {
                static::applyContentPlugins($item);
            }

            /**
             * @var $document HtmlDocument
             */
            $document = Factory::getDocument();
            $document->setBuffer(
                str_replace($marker, $item->event->$key, $document->getBuffer('component')),
                'component'
            );
        });

        return $marker;
    }

    protected static function applyContentPlugins($item)
    {
        $app = Factory::getApplication();

        // Process the content plugins.
        PluginHelper::importPlugin('content');

        $item->event = new \stdClass();

        // Joomla content plugins expect $item and $item->params to be passed as reference
        $results = $app->triggerEvent('onContentAfterTitle', [
            'com_content.item',
            &$item,
            &$item->params,
        ]);
        $item->event->afterDisplayTitle = trim(implode("\n", $results));

        $results = $app->triggerEvent('onContentBeforeDisplay', [
            'com_content.item',
            &$item,
            &$item->params,
        ]);
        $item->event->beforeDisplayContent = trim(implode("\n", $results));

        $results = $app->triggerEvent('onContentAfterDisplay', [
            'com_content.item',
            &$item,
            &$item->params,
        ]);
        $item->event->afterDisplayContent = trim(implode("\n", $results));
    }
}
