<?php

declare(strict_types=1);

namespace RZ\Roadiz\EntityGenerator\Field;

use RZ\Roadiz\Contracts\NodeType\NodeTypeFieldInterface;
use Symfony\Component\String\UnicodeString;
use Symfony\Component\Yaml\Yaml;

class ManyToManyFieldGenerator extends AbstractFieldGenerator
{
    private array $configuration;

    public function __construct(NodeTypeFieldInterface $field, array $options = [])
    {
        parent::__construct($field, $options);

        if (null === $this->field->getDefaultValues() || empty($this->field->getDefaultValues())) {
            throw new \LogicException('Default values must be a valid YAML for ' . ManyToManyFieldGenerator::class);
        }
        $this->configuration = Yaml::parse($this->field->getDefaultValues() ?? '');
    }

    /**
     * @inheritDoc
     */
    public function getFieldAnnotation(): string
    {
        /*
         * Many Users have Many Groups.
         * @ManyToMany(targetEntity="Group")
         * @JoinTable(name="users_groups",
         *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
         *      inverseJoinColumns={@JoinColumn(name="group_id", referencedColumnName="id")}
         */
        $entityA = (new UnicodeString($this->field->getNodeTypeName()))
            ->ascii()
            ->snake()
            ->lower()
            ->trim('-')
            ->trim('_')
            ->trim()
            ->toString()
        ;
        $entityB = $this->field->getName();
        $joinColumnParams = [
            'name' => '"' . $entityA . '_id"',
            'referencedColumnName' => '"id"',
            'onDelete' => '"CASCADE"'
        ];
        $inverseJoinColumns = [
            'name' => '"' . $entityB . '_id"',
            'referencedColumnName' => '"id"',
            'onDelete' => '"CASCADE"'
        ];
        $ormParams = [
            'name' => '"' . $entityA . '_' . $entityB . '"',
            'joinColumns' => '{ @ORM\JoinColumn(' . static::flattenORMParameters($joinColumnParams) . ') }',
            'inverseJoinColumns' => '{ @ORM\JoinColumn(' . static::flattenORMParameters($inverseJoinColumns) . ') }',
        ];
        $orderByClause = '';
        if (count($this->configuration['orderBy']) > 0) {
            // use default order for Collections
            $orderBy = [];
            foreach ($this->configuration['orderBy'] as $order) {
                $orderBy[$order['field']] = $order['direction'];
            }
            $orderByClause = '@ORM\OrderBy(value=' . json_encode($orderBy) . ')';
        }

        $serializer = '';
        if (!empty($this->getSerializationAnnotations())) {
            $serializer = PHP_EOL .
                static::ANNOTATION_PREFIX .
                implode(PHP_EOL . static::ANNOTATION_PREFIX, $this->getSerializationAnnotations());
        }

        return '
    /**
     * ' . $this->field->getLabel() . '
     *' . $serializer . '
     * @var \Doctrine\Common\Collections\Collection<' . $this->configuration['classname'] . '>
     * @ORM\ManyToMany(targetEntity="' . $this->configuration['classname'] . '")
     * ' . $orderByClause . '
     * @ORM\JoinTable(' . static::flattenORMParameters($ormParams) . ')
     */' . PHP_EOL;
    }

    protected function getFieldTypeDeclaration(): string
    {
        return '\Doctrine\Common\Collections\Collection';
    }

    /**
     * @inheritDoc
     */
    public function getFieldGetter(): string
    {
        return '
    /**
     * @return \Doctrine\Common\Collections\Collection<' . $this->configuration['classname'] . '>
     */
    public function ' . $this->field->getGetterName() . '(): \Doctrine\Common\Collections\Collection
    {
        return $this->' . $this->field->getVarName() . ';
    }' . PHP_EOL;
    }

    /**
     * @inheritDoc
     */
    public function getFieldSetter(): string
    {
        return '
    /**
     * @var \Doctrine\Common\Collections\Collection<' . $this->configuration['classname'] . '> $' . $this->field->getVarName() . '
     * @return $this
     */
    public function ' . $this->field->getSetterName() . '($' . $this->field->getVarName() . ')
    {
        if ($' . $this->field->getVarName() . ' instanceof \Doctrine\Common\Collections\Collection) {
            $this->' . $this->field->getVarName() . ' = $' . $this->field->getVarName() . ';
        } else {
            $this->' . $this->field->getVarName() . ' = new \Doctrine\Common\Collections\ArrayCollection($' . $this->field->getVarName() . ');
        }

        return $this;
    }' . PHP_EOL;
    }

    /**
     * @inheritDoc
     */
    public function getFieldConstructorInitialization(): string
    {
        return '$this->' . $this->field->getVarName() . ' = new \Doctrine\Common\Collections\ArrayCollection();';
    }

    protected function getSerializationAnnotations(): array
    {
        $annotations = parent::getSerializationAnnotations();
        $annotations[] = '@SymfonySerializer\Groups(' . $this->getSerializationGroups() . ')';
        if ($this->getSerializationMaxDepth() > 0) {
            $annotations[] = '@SymfonySerializer\MaxDepth(' . $this->getSerializationMaxDepth() . ')';
        }
        // Add whitespace before each line for PHPDoc syntax
        return array_map(function ($line) {
            $line = trim($line);
            return !empty($line) ? ' ' . $line : '';
        }, $annotations);
    }
}
