<?php

if ($args['show_link'] && $args['link_style']) {
    echo '<span class="uk-' . $args['link_style'] . '">';
}

echo implode($args['separator'], array_map(function ($tag) use ($args) {

    if (empty($args['show_link'])) {
        return $tag->name;
    }

    return "<a href=\"{$tag->link}\">{$tag->name}</a>";

}, $tags));

if ($args['show_link'] && $args['link_style']) {
    echo '</span>';
}
