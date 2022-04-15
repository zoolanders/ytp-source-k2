<?php

namespace YOOtheme\Source\K2\Type;

use function YOOtheme\trans;
use YOOtheme\File;

class GalleryType
{
    public static function config(): array
    {
        return [
            'fields' => [
                'gallery' => [
                    'type' => [
                        'listOf' => 'K2GalleryItem',
                    ],
                    'metadata' => [
                        'label' => trans('Image Gallery (SIG Pro)')
                    ],
                    'extensions' => [
                        'call' => __CLASS__ . '::gallery',
                    ],
                ],
            ],
        ];
    }

    public static function gallery($item): array
    {
        require_once JPATH_SITE.'/administrator/components/com_sigpro/helper.php';
        require_once JPATH_SITE.'/administrator/components/com_sigpro/models/model.php';
        require_once JPATH_SITE.'/administrator/components/com_sigpro/models/gallery.php';

        if (File::exists(\SigProHelper::getPath('k2') . '/' . $item->id)) {
            $model = new \SigProModelGallery();
            $model->setState('type', 'k2');
            $model->setState('folder', $item->id);

            return $model->getData()->images ?? [];
        }

        return [];
    }
}
