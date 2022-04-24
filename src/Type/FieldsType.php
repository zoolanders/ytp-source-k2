<?php

namespace YOOtheme\Source\K2\Type;

use function YOOtheme\trans;
use YOOtheme\Builder\Source;
use YOOtheme\Source\K2\K2Helper;
use YOOtheme\Str;

class FieldsType
{
    public static function config(Source $source, string $type, $group, array $fields): array
    {
        return [
            'fields' => array_filter(
                array_reduce(
                    $fields,
                    function ($fields, $field) use ($source, $type, $group) {
                        return $fields +
                            static::configFields(
                                $field,
                                [
                                    'type' => 'String',
                                    'name' => Str::camelCase($field->alias),
                                    'metadata' => [
                                        'label' => $field->name,
                                        'group' => $group->name,
                                    ],
                                    'extensions' => [
                                        'call' => [
                                            'func' => "{$type}.fields@resolve",
                                            'args' => ['id' => $field->id]
                                        ]
                                    ],
                                ],
                                $source,
                                $type
                            );
                    },
                    []
                )
            ),

            'extensions' => [
                'bind' => [
                    "{$type}.fields" => [
                        'class' => __CLASS__
                    ],
                ],
            ],
        ];
    }

    protected static function configFields($field, array $config, Source $source, $type)
    {
        $fields = [];

        $fieldType = Str::upperFirst($field->type);

        $config = is_callable($callback = [__CLASS__, "config{$fieldType}"])
            ? $callback($field, $config, $source, $type)
            : static::configGenericField($field, $config);

        $fields[$field->name] = $config;

        if (is_callable($callback = [__CLASS__, "config{$fieldType}String"])) {
            $fields[$field->name . 'String'] = $callback($field, $config, $type);
        }

        return $fields;
    }

    protected static function configGenericField($field, array $config): array
    {
        return $config;
    }

    protected static function configTextfield($field, array $config)
    {
        return array_replace_recursive($config, ['metadata' => ['filters' => ['limit']]]);
    }

    protected static function configTextarea($field, array $config)
    {
        return array_replace_recursive($config, ['metadata' => ['filters' => ['limit']]]);
    }

    protected static function configDate($field, array $config)
    {
        return array_replace_recursive($config, ['metadata' => ['filters' => ['date']]]);
    }

    protected static function configLink($field, array $config)
    {
        return array_replace_recursive($config, [
            'args' => [
                'value' => [
                    'type' => 'String',
                ],
            ],
            'metadata' => [
                'arguments' => [
                    'value' => [
                        'label' => trans('Value'),
                        'description' => trans('Choose the value that should be mapped.'),
                        'type' => 'select',
                        'default' => 'url',
                        'options' => [
                            'Url' => 'url',
                            'Text' => 'text'
                        ]
                    ],
                ]
            ]
        ]);
    }

    protected static function configMultipleSelect($field, array $config)
    {
        return array_replace_recursive($config, [
            'args' => [
                'separator' => [
                    'type' => 'String',
                ],
            ],
            'metadata' => [
                'arguments' => [
                    'separator' => [
                        'label' => trans('Separator'),
                        'default' => ', '
                    ],
                ]
            ]
        ]);
    }

    public static function field($item, $args, $ctx, $info)
    {
        return $item;
    }

    public function resolve($item, $args, $ctx, $info)
    {
        if (!isset($item->id) || !($field = K2Helper::getExtraFieldInfo($args['id']))) {
            return;
        }

        return $this->resolveField($field, $item, $args);
    }

    public function resolveField($field, $item, array $args)
    {
        $fieldType = Str::upperFirst($field->type) . 'Field';
        $fields = array_combine(array_column((array) $item->extraFields, 'id'), (array) $item->extraFields);

        if (is_callable($callback = [$this, "resolve{$fieldType}"])) {
            return $callback($field, $fields, $args);
        }

        $value = $this->resolveGenericField($field, $fields, $args);

        return $value ?? $field->value->value;
    }

    public function resolveGenericField($field, $fields, array $args)
    {
        return $fields[$field->id]->value ?? $field->value->value;
    }

    public function resolveImageField($field, $fields, array $args)
    {
        $value = $fields[$field->id]->value;

        $start = strpos($value, 'src="') + 5;
        $length = strpos($value, '" alt') - $start;

        return substr($value, $start, $length);
    }

    public function resolveLinkField($field, $fields, array $args)
    {
        $value = $fields[$field->id];

        return $value->{$args['value'] ?? 'url'} ?? '';
    }

    public function resolveMultipleSelectField($field, $fields, array $args)
    {
        $values = explode(', ', $fields[$field->id]->value ?? '');
        $separator = $args['separator'] ?? ', ';

        return implode($separator, $values);
    }
}
