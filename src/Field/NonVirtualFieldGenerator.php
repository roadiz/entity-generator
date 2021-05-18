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
        } elseif ($this->field->isBool()) {
            $ormParams['nullable'] = 'false';
            $ormParams['options'] = '{"default" = false}';
        }

        $autodoc = '';
        if (!empty($this->getFieldAutodoc())) {
            $autodoc = PHP_EOL .
                static::ANNOTATION_PREFIX .
                implode(PHP_EOL . static::ANNOTATION_PREFIX, $this->getFieldAutodoc());
        }

        $serializer = '';
        if (empty($this->getFieldAlternativeGetter()) && !empty($this->getSerializationAnnotations())) {
            $serializer = PHP_EOL .
                static::ANNOTATION_PREFIX .
                implode(PHP_EOL . static::ANNOTATION_PREFIX, $this->getSerializationAnnotations());
        }

        return '
    /**' . $autodoc .'
     *
     * @Gedmo\Versioned
     * @ORM\Column(' . static::flattenORMParameters($ormParams) . ')' . $serializer . '
     */'.PHP_EOL;
    }

    protected function getFieldTypeDeclaration(): string
    {
        switch (true) {
            case $this->field->isBool():
                return 'bool';
            case $this->field->isInteger():
                return '?int';
            case $this->field->isColor():
            case $this->field->isEmail():
            case $this->field->isString():
            case $this->field->isCountry():
            case $this->field->isMarkdown():
            case $this->field->isText():
            case $this->field->isRichText():
            case $this->field->isEnum():
                return '?string';
            case $this->field->isDateTime():
            case $this->field->isDate():
                return '?\DateTime';
            default:
                return '';
        }
    }

    protected function getFieldDefaultValueDeclaration(): string
    {
        switch (true) {
            case $this->field->isBool():
                return '= false';
            default:
                return '= null';
        }
    }

    /**
     * @inheritDoc
     */
    public function getFieldGetter(): string
    {
        $type = $this->getFieldTypeDeclaration();
        if (empty($type)) {
            $docType = 'mixed';
            $typeHint = '';
        } else {
            $docType = $this->toPhpDocType($type);
            $typeHint = ': ' . $type;
        }
        $assignation = '$this->'.$this->field->getVarName();

        return '
    /**
     * @return '.$docType.'
     */
    public function '.$this->field->getGetterName().'()'.$typeHint.'
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
        $nullable = true;
        $casting = '';

        switch (true) {
            case $this->field->isBool():
                $casting = '(boolean) ';
                $nullable = false;
                break;
            case $this->field->isInteger():
                $casting = '(int) ';
                break;
            case $this->field->isColor():
            case $this->field->isEmail():
            case $this->field->isString():
            case $this->field->isCountry():
            case $this->field->isMarkdown():
            case $this->field->isText():
            case $this->field->isRichText():
            case $this->field->isEnum():
                $casting = '(string) ';
                break;
        }

        $type = $this->getFieldTypeDeclaration();
        if (empty($type)) {
            $docType = 'mixed';
        } else {
            $docType = $this->toPhpDocType($type);
        }

        if ($nullable && !empty($casting)) {
            $assignation = '$this->'.$this->field->getVarName().' = null !== $'.$this->field->getVarName().' ?
            '.$casting.$assignation.' :
            null;';
        } else {
            $assignation = '$this->'.$this->field->getVarName().' = '.$assignation.';';
        }

        return '
    /**
     * @param '.$docType.' $'.$this->field->getVarName().'
     *
     * @return $this
     */
    public function '.$this->field->getSetterName().'($'.$this->field->getVarName().')
    {
        '.$assignation.'

        return $this;
    }'.PHP_EOL;
    }
}
