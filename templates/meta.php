<?php

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use YOOtheme\Builder\Joomla\Source\UserHelper;
use YOOtheme\Path;

$author = $published = $category = $tag = '';

// Author
if ($args['show_author']) {

    $author = $item->created_by_alias ?: $item->author;

    if (!isset($item->contact_link)) {
        $item->contact_link = UserHelper::getContactLink($item->created_by);
    }

    if (!empty($item->contact_link)) {
        $author = HTMLHelper::_('link', $item->contact_link, $author);
    }
}

// Publish date
if ($args['show_publish_date'] && $item->publish_up !== Factory::getDbo()->getNullDate()) {
    $published = HTMLHelper::_('date', $item->publish_up, $args['date_format'] ?: Text::_('DATE_FORMAT_LC3'));
    $published = '<time datetime="' . HTMLHelper::_('date', $item->publish_up, 'c') . "\">{$published}</time>";
}

// Category
if ($args['show_taxonomy'] === 'category' && $item->category) {
    $category = HTMLHelper::_('link', $item->category->link, $item->category->name);
}

// Tag
if ($tags && $args['show_taxonomy'] === 'tag') {
    $tag = $view->render(Path::get('./tags'), [
        'tags' => $tags,
        'args' => [
            'separator' => ', ',
            'show_link' => true,
            'link_style' => $args['link_style'],
        ],
    ]);
}

if (!$published && !$author && !$category && !$tag) {
    return;
}

if ($args['link_style']) {
    echo "<span class=\"uk-{$args['link_style']}\">";
}

switch ($args['format']) {

    case 'list':

        echo implode(" {$args['separator']} ", array_filter([$published, $author, $category, $tag]));
        break;

    default: // sentence

        if ($author && $published) {
            Text::printf('TPL_YOOTHEME_META_AUTHOR_DATE', $author, $published);
        } elseif ($author) {
            Text::printf('TPL_YOOTHEME_META_AUTHOR', $author);
        } elseif ($published) {
            Text::printf('TPL_YOOTHEME_META_DATE', $published);
        }

        if ($category) {
            echo ' ';
            Text::printf('TPL_YOOTHEME_META_CATEGORY', $category);
        } elseif ($tag) {
            echo ' ';
            Text::printf('TPL_YOOTHEME_META_TAG', $tag);
        }
}

if ($args['link_style']) {
    echo '</span>';
}
