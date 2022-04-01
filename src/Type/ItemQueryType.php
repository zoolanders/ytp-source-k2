<?php

namespace YOOtheme\Source\K2\Type;

use function YOOtheme\trans;
// use Joomla\CMS\Router\Router;
// use Joomla\Uri\Uri;
// use YOOtheme\Builder\Joomla\Source\ArticleHelper;

class ItemQueryType
{
    public static function config(): array
    {
        return [
            'fields' => [
                'k2Item' => [
                    'type' => 'K2Item',
                    'metadata' => [
                        'label' => 'K2 ' . trans('Item'),
                        'view' => ['com_k2.item'],
                        'group' => 'Page',
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::resolve',
                    ],
                ],
                // 'prevArticle' => [
                //     'type' => 'Article',
                //     'metadata' => [
                //         'label' => trans('Previous Article'),
                //         'view' => ['com_content.article'],
                //         'group' => 'Page',
                //     ],
                //     'extensions' => [
                //         'call' => __CLASS__ . '::resolvePreviousArticle',
                //     ],
                // ],
                // 'nextArticle' => [
                //     'type' => 'Article',
                //     'metadata' => [
                //         'label' => trans('Next Article'),
                //         'view' => ['com_content.article'],
                //         'group' => 'Page',
                //     ],
                //     'extensions' => [
                //         'call' => __CLASS__ . '::resolveNextArticle',
                //     ],
                // ],
            ],
        ];
    }

    public static function resolve($root)
    {
        if (isset($root['item'])) {
            return $root['item'];
        }
    }

    // public static function resolvePreviousArticle($root)
    // {
    //     $article = static::resolve($root);

    //     if (!$article) {
    //         return;
    //     }

    //     ArticleHelper::applyPageNavigation($article);

    //     if (!empty($article->prev)) {
    //         return static::getArticleFromUrl($article->prev);
    //     }
    // }

    // public static function resolveNextArticle($root)
    // {
    //     $article = static::resolve($root);

    //     if (!$article) {
    //         return;
    //     }

    //     ArticleHelper::applyPageNavigation($article);

    //     if (!empty($article->next)) {
    //         return static::getArticleFromUrl($article->next);
    //     }
    // }

    // protected static function getArticleFromUrl($url)
    // {
    //     $uri = new Uri($url);
    //     $vars = Router::getInstance('site')->parse($uri);
    //     $id = isset($vars['id']) ? $vars['id'] : 0;

    //     if (!$id) {
    //         return null;
    //     }

    //     $articles = ArticleHelper::get($id);
    //     return array_shift($articles);
    // }
}
