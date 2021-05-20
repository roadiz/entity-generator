<?php
declare(strict_types=1);

namespace RZ\Roadiz\EntityGenerator\Field;

use RZ\Roadiz\Contracts\NodeType\NodeTypeFieldInterface;
use RZ\Roadiz\Contracts\NodeType\SerializableInterface;
use Symfony\Component\String\UnicodeString;

abstract class AbstractFieldGenerator
{
    const USE_NATIVE_JSON = 'use_native_json';
    const TAB = '    ';
    const ANNOTATION_PREFIX = AbstractFieldGenerator::TAB . ' * ';

    protected NodeTypeFieldInterface $field;
    protected array $options;

    /**
     * @param NodeTypeFieldInterface $field
     * @param array $options
     */
    public function __construct(NodeTypeFieldInterface $field, array $options = [])
    {
        $this->field = $field;
        $this->options = $options;
    }

    /**
     * @param array $ormParams
     *
     * @return string
     */
    public static function flattenORMParameters(array $ormParams): string
    {
        $flatParams = [];
        foreach ($ormParams as $key => $value) {
            $flatParams[] = $key . '=' . $value;
        }

        return implode(', ', $flatParams);
    }

    /**
     * Generate PHP code for current doctrine field.
     *
     * @return string
     */
    public function getField(): string
    {
        return $this->getFieldAnnotation().
            $this->getFieldDeclaration().
            $this->getFieldGetter().
            $this->getFieldAlternativeGetter().
            $this->getFieldSetter().PHP_EOL;
    }

    /**
     * @return array<string>
     */
    protected function getFieldAutodoc(): array
    {
        $docs = [
            $this->field->getLabel().'.',
        ];
        if (!empty($this->field->getDescription())) {
            $docs[] = $this->field->getDescription().'.';
        }
        if (!empty($this->field->getDefaultValues())) {
            $docs[] = 'Default values: ' . str_replace("\n", "\n     *     ", $this->field->getDefaultValues());
        }
        if (!empty($this->field->getGroupName())) {
            $docs[] = 'Group: ' . $this->field->getGroupName().'.';
        }
        return $docs;
    }

    /**
     * @return string
     */
    protected function getFieldAnnotation(): string
    {
        $autodoc = '';
        if (!empty($this->getFieldAutodoc())) {
            $autodoc = PHP_EOL .
                static::ANNOTATION_PREFIX .
                implode(PHP_EOL . static::ANNOTATION_PREFIX, $this->getFieldAutodoc());
        }
        return '
    /**' . $autodoc .'
     *
     * (Virtual field, this var is a buffer)
     * @Serializer\Exclude
     */'.PHP_EOL;
    }

    /**
     * Generate PHP property declaration block.
     */
    protected function getFieldDeclaration(): string
    {
        $type = $this->getFieldTypeDeclaration();
        if (!empty($type)) {
            $type .= ' ';
        }
        $defaultValue = $this->getFieldDefaultValueDeclaration();
        if (!empty($defaultValue)) {
            $defaultValue = ' ' . $defaultValue;
        }
        /*
         * Buffer var to get referenced entities (documents, nodes, cforms, doctrine entities)
         */
        return static::TAB . 'private ' . $type . '$'.$this->field->getVarName() . $defaultValue . ';'.PHP_EOL;
    }

    protected function getFieldTypeDeclaration(): string
    {
        return '';
    }

    protected function toPhpDocType(string $typeHint): string
    {
        $unicode = new UnicodeString($typeHint);
        return $unicode->startsWith('?') ?
            $unicode->trimStart('?')->append('|null')->toString() :
            $typeHint;
    }

    protected function getFieldDefaultValueDeclaration(): string
    {
        return '';
    }

    /**
     * Generate PHP alternative getter method block.
     *
     * @return string
     */
    abstract protected function getFieldGetter(): string;

    /**
     * Generate PHP alternative getter method block.
     *
     * @return string
     */
    protected function getFieldAlternativeGetter(): string
    {
        return '';
    }

    /**
     * Generate PHP setter method block.
     *
     * @return string
     */
    protected function getFieldSetter(): string
    {
        return '';
    }

    /**
     * Generate PHP annotation block for Doctrine table indexes.
     *
     * @return string
     */
    public function getFieldIndex(): string
    {
        return '';
    }

    /**
     * Generate PHP property initialization for class constructor.
     *
     * @return string
     */
    public function getFieldConstructorInitialization(): string
    {
        return '';
    }

    /**
     * @return bool
     */
    protected function excludeFromSerialization(): bool
    {
        if ($this->field instanceof SerializableInterface) {
            return $this->field->isExcludedFromSerialization();
        }
        return false;
    }

    protected function getSerializationExclusionExpression(): ?string
    {
        if ($this->field instanceof SerializableInterface &&
            null !== $this->field->getSerializationExclusionExpression()) {
            return (new UnicodeString($this->field->getSerializationExclusionExpression()))
                ->replace('"', '')
                ->replace('\\', '')
                ->trim()
                ->toString();
        }
        return null;
    }

    protected function getSerializationMaxDepth(): int
    {
        if ($this->field instanceof SerializableInterface && $this->field->getSerializationMaxDepth() > 0) {
            return $this->field->getSerializationMaxDepth();
        }
        return 2;
    }

    protected function getDefaultSerializationGroups(): array
    {
        return [
            'nodes_sources',
            'nodes_sources_'.($this->field->getGroupNameCanonical() ?: 'default')
        ];
    }

    protected function getSerializationGroups(): string
    {
        if ($this->field instanceof SerializableInterface && !empty($this->field->getSerializationGroups())) {
            $groups = $this->field->getSerializationGroups();
        } else {
            $groups = $this->getDefaultSerializationGroups();
        }
        return '{' . implode(', ', array_map(function (string $group) {
            return '"' . (new UnicodeString($group))
                    ->replaceMatches('/[^A-Za-z0-9]++/', '_')
                    ->trim('_')->toString() . '"';
        }, $groups)) . '}';
    }

    protected function getSerializationAnnotations(): array
    {
        if ($this->excludeFromSerialization()) {
            return ['@Serializer\Exclude()'];
        }
        $annotations = [];
        $annotations[] = '@Serializer\Groups(' . $this->getSerializationGroups() . ')';
        if ($this->getSerializationMaxDepth() > 0) {
            $annotations[] = '@Serializer\MaxDepth(' . $this->getSerializationMaxDepth() . ')';
        }
        if (null !== $this->getSerializationExclusionExpression()) {
            $annotations[] = '@Serializer\Exclude(if="' . $this->getSerializationExclusionExpression() . '")';
        }

        switch (true) {
            case $this->field->isBool():
                $annotations[] = '@Serializer\Type("bool")';
                break;
            case $this->field->isInteger():
                $annotations[] = '@Serializer\Type("int")';
                break;
            case $this->field->isDecimal():
                $annotations[] = '@Serializer\Type("double")';
                break;
            case $this->field->isColor():
            case $this->field->isEmail():
            case $this->field->isString():
            case $this->field->isCountry():
            case $this->field->isMarkdown():
            case $this->field->isText():
            case $this->field->isRichText():
            case $this->field->isEnum():
                $annotations[] = '@Serializer\Type("string")';
                break;
            case $this->field->isDateTime():
            case $this->field->isDate():
                $annotations[] = '@Serializer\Type("DateTime")';
                break;
        }
        return $annotations;
    }
}
