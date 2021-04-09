<?php
declare(strict_types=1);

namespace RZ\Roadiz\EntityGenerator\Field;

class NonVirtualFieldGenerator extends AbstractFieldGenerator
{
    /**
     * Generate PHP annotation block for Doctrine table indexes.
     *
     * @return string
     */
    public function getFieldIndex(): string
    {
        if ($this->field->isIndexed()) {
            return '@ORM\Index(columns={"'.$this->field->getName().'"})';
        }

        return '';
    }

    /**
     * @return string
     */
    protected function getDoctrineType(): string
    {
        if ($this->field->isMultiProvider() &&
            $this->options[AbstractFieldGenerator::USE_NATIVE_JSON] === true) {
            return 'json';
        }
        return $this->field->getDoctrineType();
    }

    /**
     * @return int|null String field length, returns NULL if length is irrelevant.
     */
    protected function getFieldLength(): ?int
    {
        /*
         * Only set length for string (VARCHAR) type
         */
        if ($this->getDoctrineType() !== 'string') {
            return null;
        }
        switch (true) {
            case $this->field->isColor():
                return 10;
            case $this->field->isCountry():
                return 5;
            case $this->field->isPassword():
            case $this->field->isGeoTag():
                return 128;
            default:
                return 250;
        }
    }

    /**
     * @inheritDoc
     */
    public function getFieldAnnotation(): string
    {
        $serializationType = '';
        $exclusion = $this->excludeFromSerialization() ?
            '@Serializer\Exclude()' :
            '@Serializer\Groups({"nodes_sources", "nodes_sources_'.($this->field->getGroupNameCanonical() ?: 'default').'"})';
        $ormParams = [
            'type' => '"' . $this->getDoctrineType() . '"',
            'nullable' => 'true',
            'name' => '"' . $this->field->getName() . '"',
        ];

        $fieldLength = $this->getFieldLength();
        if (null !== $fieldLength && $fieldLength > 0) {
            $ormParams['length'] = $fieldLength;
        }

        if ($this->field->isDecimal()) {
            $ormParams['precision'] = 18;
            $ormParams['scale'] = 3;
            $serializationType = '@Serializer\Type("double")';
        } elseif ($this->field->isBool()) {
            $ormParams['nullable'] = 'false';
            $ormParams['options'] = '{"default" = false}';
            $serializationType = '@Serializer\Type("boolean")';
        } elseif ($this->field->isInteger()) {
            $serializationType = '@Serializer\Type("integer")';
        }

        return '
    /**
     * ' . implode("\n     * ", $this->getFieldAutodoc()) .'
     *
     * @Gedmo\Versioned
     * @ORM\Column(' . static::flattenORMParameters($ormParams) . ')
     * ' . $exclusion . '
     * ' . $serializationType . '
     */'.PHP_EOL;
    }

    /**
     * @inheritDoc
     */
    public function getFieldDeclaration(): string
    {
        if ($this->field->isBool()) {
            return '    private $'.$this->field->getVarName().' = false;'.PHP_EOL;
        } elseif ($this->field->isInteger()) {
            return '    private $'.$this->field->getVarName().' = 0;'.PHP_EOL;
        } else {
            return '    private $'.$this->field->getVarName().';'.PHP_EOL;
        }
    }

    /**
     * @inheritDoc
     */
    public function getFieldGetter(): string
    {
        $assignation = '$this->'.$this->field->getVarName();

        return '
    /**
     * @return mixed
     */
    public function '.$this->field->getGetterName().'()
    {
        return '.$assignation.';
    }'.PHP_EOL;
    }

    /**
     * @inheritDoc
     */
    public function getFieldSetter(): string
    {
        $assignation = '$'.$this->field->getVarName();

        if ($this->field->isBool()) {
            $assignation = '(boolean) $'.$this->field->getVarName();
        }
        if ($this->field->isInteger()) {
            $assignation = '(int) $'.$this->field->getVarName();
        }
        if ($this->field->isDecimal()) {
            $assignation = '(double) $'.$this->field->getVarName();
        }

        return '
    /**
     * @param mixed $'.$this->field->getVarName().'
     *
     * @return $this
     */
    public function '.$this->field->getSetterName().'($'.$this->field->getVarName().')
    {
        $this->'.$this->field->getVarName().' = '.$assignation.';

        return $this;
    }'.PHP_EOL;
    }
}
