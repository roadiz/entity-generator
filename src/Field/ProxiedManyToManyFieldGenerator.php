<?php

declare(strict_types=1);

namespace RZ\Roadiz\EntityGenerator\Field;

use RZ\Roadiz\Contracts\NodeType\NodeTypeFieldInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\String\UnicodeString;

class ProxiedManyToManyFieldGenerator extends AbstractFieldGenerator
{
    protected array $configuration;

    public function __construct(NodeTypeFieldInterface $field, array $options = [])
    {
        parent::__construct($field, $options);

        if (empty($this->field->getDefaultValues())) {
            throw new \LogicException('Default values must be a valid YAML for ' . ProxiedManyToManyFieldGenerator::class);
        }
        $this->configuration = Yaml::parse($this->field->getDefaultValues());
    }

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

    /**
     * Generate PHP property declaration block.
     */
    protected function getFieldDeclaration(): string
    {
        /*
         * Buffer var to get referenced entities (documents, nodes, cforms, doctrine entities)
         */
        return '    private $' . $this->getProxiedVarName() . ';' . PHP_EOL;
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
        $orderByClause = '';
        if (isset($this->configuration['proxy']['orderBy']) && count($this->configuration['proxy']['orderBy']) > 0) {
            // use default order for Collections
            $orderBy = [];
            foreach ($this->configuration['proxy']['orderBy'] as $order) {
                $orderBy[$order['field']] = $order['direction'];
            }
            $orderByClause = '@ORM\OrderBy(value=' . json_encode($orderBy) . ')';
        }
        $ormParams = [
            'targetEntity' => '"' . $this->getProxyClassname() . '"',
            'mappedBy' => '"' . $this->configuration['proxy']['self'] . '"',
            'orphanRemoval' => 'true',
            'cascade' => '{"persist", "remove"}'
        ];

        return '
    /**
     * ' . $this->field->getLabel() . '
     *
     * @Serializer\Exclude()
     * @SymfonySerializer\Ignore()
     * @var \Doctrine\Common\Collections\ArrayCollection<' . $this->getProxyClassname() . '>
     * @ORM\OneToMany(' . static::flattenORMParameters($ormParams) . ')
     * ' . $orderByClause . '
     */' . PHP_EOL;
    }

    /**
     * @inheritDoc
     */
    public function getFieldGetter(): string
    {
        $serializer = '';
        if (!empty($this->getSerializationAnnotations())) {
            $serializer = PHP_EOL .
                static::ANNOTATION_PREFIX .
                implode(PHP_EOL . static::ANNOTATION_PREFIX, $this->getSerializationAnnotations());
        }

        return '
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function ' . $this->getProxiedGetterName() . '()
    {
        return $this->' . $this->getProxiedVarName() . ';
    }

    /**' . $serializer . '
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function ' . $this->field->getGetterName() . '()
    {
        return $this->' . $this->getProxiedVarName() . '->map(function (' . $this->getProxyClassname() . ' $proxyEntity) {
            return $proxyEntity->' . $this->getProxyRelationGetterName() . '();
        });
    }' . PHP_EOL;
    }

    /**
     * @inheritDoc
     */
    public function getFieldSetter(): string
    {
        return '
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $' . $this->getProxiedVarName() . '
     * @Serializer\VirtualProperty()
     * @return $this
     */
    public function ' . $this->getProxiedSetterName() . '($' . $this->getProxiedVarName() . ' = null)
    {
        $this->' . $this->getProxiedVarName() . ' = $' . $this->getProxiedVarName() . ';

        return $this;
    }
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection|null $' . $this->field->getVarName() . '
     * @return $this
     */
    public function ' . $this->field->getSetterName() . '($' . $this->field->getVarName() . ' = null)
    {
        foreach ($this->' . $this->getProxiedGetterName() . '() as $item) {
            $item->' . $this->getProxySelfSetterName() . '(null);
        }
        $this->' . $this->getProxiedVarName() . '->clear();
        if (null !== $' . $this->field->getVarName() . ') {
            $position = 0;
            foreach ($' . $this->field->getVarName() . ' as $single' . ucwords($this->field->getVarName()) . ') {
                $proxyEntity = new ' . $this->getProxyClassname() . '();
                $proxyEntity->' . $this->getProxySelfSetterName() . '($this);
                if ($proxyEntity instanceof \RZ\Roadiz\Core\AbstractEntities\PositionedInterface) {
                    $proxyEntity->setPosition(++$position);
                }
                $proxyEntity->' . $this->getProxyRelationSetterName() . '($single' . ucwords($this->field->getVarName()) . ');
                $this->' . $this->getProxiedVarName() . '->add($proxyEntity);
            }
        }

        return $this;
    }' . PHP_EOL;
    }

    /**
     * @inheritDoc
     */
    public function getFieldConstructorInitialization(): string
    {
        return '$this->' . $this->getProxiedVarName() . ' = new \Doctrine\Common\Collections\ArrayCollection();';
    }

    /**
     * @return string
     */
    protected function getProxiedVarName(): string
    {
        return $this->field->getVarName() . 'Proxy';
    }
    /**
     * @return string
     */
    protected function getProxiedSetterName(): string
    {
        return $this->field->getSetterName() . 'Proxy';
    }
    /**
     * @return string
     */
    protected function getProxiedGetterName(): string
    {
        return $this->field->getGetterName() . 'Proxy';
    }
    /**
     * @return string
     */
    protected function getProxySelfSetterName(): string
    {
        return 'set' . ucwords($this->configuration['proxy']['self']);
    }
    /**
     * @return string
     */
    protected function getProxyRelationSetterName(): string
    {
        return 'set' . ucwords($this->configuration['proxy']['relation']);
    }
    /**
     * @return string
     */
    protected function getProxyRelationGetterName(): string
    {
        return 'get' . ucwords($this->configuration['proxy']['relation']);
    }

    /**
     * @return string
     */
    protected function getProxyClassname(): string
    {

        return (new UnicodeString($this->configuration['proxy']['classname']))->startsWith('\\') ?
            $this->configuration['proxy']['classname'] :
            '\\' . $this->configuration['proxy']['classname'];
    }
}
