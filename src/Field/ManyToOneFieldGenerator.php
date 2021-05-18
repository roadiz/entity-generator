<?php
declare(strict_types=1);

namespace RZ\Roadiz\EntityGenerator\Field;

use RZ\Roadiz\Contracts\NodeType\NodeTypeFieldInterface;
use Symfony\Component\Yaml\Yaml;

class ManyToOneFieldGenerator extends AbstractFieldGenerator
{
    private array $configuration;

    public function __construct(NodeTypeFieldInterface $field, array $options = [])
    {
        parent::__construct($field, $options);

        if (null === $this->field->getDefaultValues() || empty($this->field->getDefaultValues())) {
            throw new \LogicException('Default values must be a valid YAML for '.ManyToOneFieldGenerator::class);
        }
        $this->configuration = Yaml::parse($this->field->getDefaultValues() ?? '');
    }

    /**
     * @inheritDoc
     */
    public function getFieldAnnotation(): string
    {
        /*
         * Many Users have One Address.
         * @ORM\ManyToOne(targetEntity="Address")
         * @ORM\JoinColumn(name="address_id", referencedColumnName="id", onDelete="SET NULL")
         */
        $ormParams = [
            'name' => '"' . $this->field->getName() . '_id"',
            'referencedColumnName' => '"id"',
            'onDelete' => '"SET NULL"',
        ];

        $serializer = '';
        if (!empty($this->getSerializationAnnotations())) {
            $serializer = PHP_EOL .
                static::ANNOTATION_PREFIX .
                implode(PHP_EOL . static::ANNOTATION_PREFIX, $this->getSerializationAnnotations());
        }

        return '
    /**
     * ' . implode("\n     * ", $this->getFieldAutodoc()) .'
     *' . $serializer . '
     * @var ' . $this->configuration['classname'] . '|null
     * @ORM\ManyToOne(targetEntity="'. $this->configuration['classname'] .'")
     * @ORM\JoinColumn(' . static::flattenORMParameters($ormParams) . ')
     */'.PHP_EOL;
    }

    protected function getFieldTypeDeclaration(): string
    {
        return '?'.$this->configuration['classname'];
    }

    protected function getFieldDefaultValueDeclaration(): string
    {
        return '= null';
    }

    /**
     * @inheritDoc
     */
    public function getFieldGetter(): string
    {
        return '
    /**
     * @return ' . $this->configuration['classname'] . '|null
     */
    public function '.$this->field->getGetterName().'(): ?' . $this->configuration['classname'] . '
    {
        return $this->' . $this->field->getVarName() . ';
    }'.PHP_EOL;
    }

    /**
     * @inheritDoc
     */
    public function getFieldSetter(): string
    {
        return '
    /**
     * @var ' . $this->configuration['classname'] . '|null $'.$this->field->getVarName().'
     * @return $this
     */
    public function '.$this->field->getSetterName().'(?' . $this->configuration['classname'] . ' $'.$this->field->getVarName().' = null)
    {
        $this->'.$this->field->getVarName().' = $'.$this->field->getVarName().';

        return $this;
    }'.PHP_EOL;
    }
}
