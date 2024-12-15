# K2 Source for YOOtheme Pro

A [YOOtheme Pro](https://yootheme.com/page-builder) Source for Joomla Component [K2](https://github.com/getk2/k2) with Views Templating support.

## Getting Started

The K2 Source is a YOOtheme Pro module ready to be set in a Child Theme or wrapped into a plugin. Assuming the Child Theme is the prefered choice follow these steps for the initial setup:

1. Create a folder `yootheme_mytheme` or with a sufix of your choice.
1. Enable the new Child Theme in the YOOtheme Pro Customizer `Advanced Settings`.
1. Place the contents of this repository into `yootheme_mytheme/modules/builder-source-k2`.
1. Create a `yootheme_mytheme/config.php` file with the following content:

```php
<?php

use function YOOtheme\app;

app()->load(__DIR__ . '/modules/*/bootstrap.php');

return [];
```

## Requirements

- PHP 7.2+
- Joomla 3.9+
- YOOtheme Pro 4.0+
- K2 2.11+

## Mentions

Developed and maintained by [ZOOlanders](https://www.zoolanders.com), this project would not have been possible without the collaboration of [JoomlaWorks](https://www.joomlaworks.net) and sponsorship from Philippe Marty (Atelier 51).
