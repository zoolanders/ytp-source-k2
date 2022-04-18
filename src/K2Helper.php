<?php

namespace YOOtheme\Source\K2;

use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;
use YOOtheme\Path;

require_once JPATH_SITE.'/administrator/components/com_k2/models/extrafield.php';
require_once JPATH_SITE.'/administrator/components/com_k2/models/extrafields.php';

class K2Helper
{
    public static function getCategory(int $categoryId)
    {
        $user = Factory::getUser();
        $category = Table::getInstance('K2Category', 'Table');

        $category->load($categoryId);

        if (!$category->published || $category->trash) {
            return null;
        }

        if (!in_array($category->access, $user->getAuthorisedViewLevels())) {
            return null;
        }

        return $category;
    }

    public static function getExtraFieldsGroups(): array
    {
        $fieldsModel = new \K2ModelExtraFields();

        return $fieldsModel->getGroups();
    }

    public static function getExtraFieldsByGroup(int $groupId): array
    {
        $fieldModel = new \K2ModelExtraField();

        $fields = $fieldModel->getExtraFieldsByGroup($groupId);

        foreach ($fields as &$field) {
            self::processExtraField($field);
        }

        return $fields;
    }

    public static function countCategoryItems(int $categoryId): int
    {
        $model = new \K2ModelItemlist();

        return $model->countCategoryItems($categoryId);
    }

    public static function getCategoryFirstChildren(int $categoryId): array
    {
        $model = new \K2ModelItemlist();

        return $model->getCategoryFirstChildren($categoryId);
    }

    public static function getCategoryImage($category)
    {
        return \K2HelperUtilities::getCategoryImage(Path::basename($category->image), $category->params);
    }

    public static function getExtraFieldInfo(int $fieldId)
    {
        $model = new \K2ModelExtraField();

        $field = $model->getExtraFieldInfo($fieldId);

        self::processExtraField($field);

        return $field;
    }

    protected static function processExtraField(&$field)
    {
        $field->value = (object) (json_decode($field->value, true)[0] ?? []);
        $field->alias = $field->value->alias;
    }

    public static function getTags()
    {
        require_once JPATH_SITE.'/administrator/components/com_k2/models/tags.php';

        return (new \K2ModelTags())->getData();
    }

    public static function getCategoriesTree()
    {
        require_once JPATH_SITE.'/administrator/components/com_k2/models/categories.php';

        return (new \K2ModelCategories())->categoriesTree();
    }
}
