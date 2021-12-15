<?php

declare(strict_types=1);

namespace RZ\Roadiz\EntityGenerator\Field;

class YamlFieldGenerator extends NonVirtualFieldGenerator
{
    protected function getSerializationAnnotations(): array
    {
        $annotations = parent::getSerializationAnnotations();
        $annotations[] = '@Serializer\VirtualProperty';
        $annotations[] = '@Serializer\SerializedName("' . $this->field->getVarName() . '")';
        $annotations[] = '@SymfonySerializer\SerializedName("' . $this->field->getVarName() . '")';
        // Add whitespace before each line for PHPDoc syntax
        return array_map(function ($line) {
            $line = trim($line);
            return !empty($line) ? ' ' . $line : '';
        }, $annotations);
    }

    protected function getDefaultSerializationGroups(): array
    {
        $groups = parent::getDefaultSerializationGroups();
        $groups[] = 'nodes_sources_yaml';
        return $groups;
    }

    /**
     * @return string
     */
    public function getFieldAlternativeGetter(): string
    {
        $serializer = '';
        if (!empty($this->getSerializationAnnotations())) {
            $serializer = PHP_EOL .
                static::ANNOTATION_PREFIX .
                implode(PHP_EOL . static::ANNOTATION_PREFIX, $this->getSerializationAnnotations());
        }
        $assignation = '$this->' . $this->field->getVarName();
        return '
    /**
     * @return object|array|null' . $serializer . '
     */
    public function ' . $this->field->getGetterName() . 'AsObject()
    {
        if (null !== ' . $assignation . ') {
            return \Symfony\Component\Yaml\Yaml::parse(' . $assignation . ');
        }
        return null;
    }' . PHP_EOL;
    }
}
