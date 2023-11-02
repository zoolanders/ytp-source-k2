<?php

namespace YOOtheme\Source\K2\Listener;

use YOOtheme\Source\K2\K2ItemsHelper;

class LoadTemplateUrl
{
    public function handle(array $template): array
    {
        if (!str_starts_with($template['type'] ?? '', 'com_k2.')) {
            return $template;
        }

        [, $view] = explode('.', $template['type']);

        switch ($view) {
            case 'item':
                $items = K2ItemsHelper::getItems([
                    'limit' => 1
                ]);

                if (isset($items[0])) {
                    $template['url'] = '/' . \K2HelperRoute::getItemRoute($items[0]->id);
                }

                break;
            case 'category':
                $catid = $template['query']['catid'] ?? [];

                if (isset($catid[0])) {
                    $template['url'] = '/' . \K2HelperRoute::getCategoryRoute($catid[0]);
                }

                break;
        }

        return $template;
    }
}
