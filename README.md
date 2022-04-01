# K2 Source for YOOtheme Pro

## Getting Started

The K2 Source is a YOOtheme Pro module ready to be set in a Child Theme or wrapped into a plugin. Assuming the Child Theme is the prefered choice during development follow this simples steps for the first setup:

1. Create a folder `yootheme_mytheme` or with a sufix of your choice.
1. Enable the new Child Theme in the YOOtheme Pro Customizer `Advanced Settings`.
1. Place the contents of this repository into `yootheme_mytheme/modules/builder-source-k2`;
1. Create a `yootheme_mytheme/config.php` file with the following content:

```php
<?php

use function YOOtheme\app;

app()->load(__DIR__ . '/modules/*/bootstrap.php');

return [];
```

## Roadmap

 - [x] Item/s, Category/s, Tag `basic` Types and Page Queries.
 - [ ] Custom Item/s, Category/s, Queries.
 - [x] Templates.
 - [ ] Item Extra Fields (partially done).
 - [ ] Content Events Fields.
