<?php
declare(strict_types=1);

namespace RZ\Roadiz\EntityGenerator\Field;

use Symfony\Component\String\UnicodeString;
use Symfony\Component\Yaml\Yaml;

class ManyToManyFieldGenerator extends AbstractFieldGenerator
{
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
        $configuration = Yaml::parse($this->field->getDefaultValues() ?? '');
        $joinColumnParams = [
            'name' => '"'.$entityA.'_id"',
            'referencedColumnName' => '"id"'
        ];
        $inverseJoinColumns = [
            'name' => '"'.$entityB.'_id"',
            'referencedColumnName' => '"id"'
        ];
        $ormParams = [
            'name' => '"'. $entityA .'_' . $entityB . '"',
            'joinColumns' => '{ @ORM\JoinColumn(' . static::flattenORMParameters($joinColumnParams) . ') }',
            'inverseJoinColumns' => '{ @ORM\JoinColumn(' . static::flattenORMParameters($inverseJoinColumns) . ') }',
        ];
        $orderByClause = '';
        if (count($configuration['orderBy']) > 0) {
            // use default order for Collections
            $orderBy = [];
            foreach ($configuration['orderBy'] as $order) {
                $orderBy[$order['field']] = $order['direction'];
            }
            $orderByClause = '@ORM\OrderBy(value='.json_encode($orderBy).')';
        }

        return '
    /**
     * ' . $this->field->getLabel() .'
     *
     * @Serializer\Groups({"nodes_sources", "nodes_sources_'.($this->field->getGroupNameCanonical() ?: 'default').'"})
     * @var \Doctrine\Common\Collections\ArrayCollection<' . $configuration['classname'] . '>
     * @ORM\ManyToMany(targetEntity="'. $configuration['classname'] .'")
     * ' . $orderByClause . '
     * @ORM\JoinTable(' . static::flattenORMParameters($ormParams) . ')
     */'.PHP_EOL;
    }

    /**
     * @inheritDoc
     */
    public function getFieldGetter(): string
    {
        return '
    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function '.$this->field->getGetterName().'()
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
     * @var \Doctrine\Common\Collections\ArrayCollection $'.$this->field->getVarName().'
     * @return $this
     */
    public function '.$this->field->getSetterName().'($'.$this->field->getVarName().' = null)
    {
        $this->'.$this->field->getVarName().' = $'.$this->field->getVarName().';

        return $this;
    }'.PHP_EOL;
    }

    /**
     * @inheritDoc
     */
    public function getFieldConstructorInitialization(): string
    {
        return '$this->' . $this->field->getVarName() . ' = new \Doctrine\Common\Collections\ArrayCollection();';
    }
}
