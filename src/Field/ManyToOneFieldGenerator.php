<?php

declare(strict_types=1);

namespace RZ\Roadiz\EntityGenerator\Field;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Property;

final class ManyToOneFieldGenerator extends AbstractConfigurableFieldGenerator
{
    protected function addFieldAttributes(Property $property, PhpNamespace $namespace, bool $exclude = false): self
    {
        parent::addFieldAttributes($property, $namespace, $exclude);

        /*
         * Many Users have One Address.
         * @\Doctrine\ORM\Mapping\ManyToOne(targetEntity="Address")
         * @\Doctrine\ORM\Mapping\JoinColumn(name="address_id", referencedColumnName="id", onDelete="SET NULL")
         */
        $ormParams = [
            'name' => $this->field->getName().'_id',
            'referencedColumnName' => 'id',
            'onDelete' => 'SET NULL',
        ];
        $property->addAttribute('Doctrine\ORM\Mapping\ManyToOne', [
            'targetEntity' => new Literal($this->getFullyQualifiedClassName().'::class'),
        ]);
        $property->addAttribute('Doctrine\ORM\Mapping\JoinColumn', $ormParams);

        if (true === $this->options['use_api_platform_filters']) {
            $property->addAttribute('ApiPlatform\Metadata\ApiFilter', [
                0 => new Literal($namespace->simplifyName('\ApiPlatform\Doctrine\Orm\Filter\SearchFilter').'::class'),
                'strategy' => 'exact',
            ]);
        }

        $this->addSerializationAttributes($property);

        return $this;
    }

    public function addFieldAnnotation(Property $property): self
    {
        $this->addFieldAutodoc($property);

        return $this;
    }

    protected function getFieldTypeDeclaration(): string
    {
        return '?'.$this->getFullyQualifiedClassName();
    }

    protected function getFieldDefaultValueDeclaration(): Literal|string|null
    {
        return new Literal('null');
    }

    public function addFieldGetter(ClassType $classType, PhpNamespace $namespace): self
    {
        $classType->addMethod($this->field->getGetterName())
            ->setReturnType($this->getFieldTypeDeclaration())
            ->setPublic()
            ->setBody(<<<PHP
return \$this->{$this->field->getVarName()};
PHP
            );

        return $this;
    }

    public function addFieldSetter(ClassType $classType): self
    {
        $setter = $classType->addMethod($this->field->getSetterName())
            ->setReturnType('static')
            ->addComment('@return $this')
            ->setPublic();
        $setter->addParameter($this->field->getVarName())
            ->setType($this->getFieldTypeDeclaration())
            ->setNullable();

        $setter->setBody(<<<PHP
\$this->{$this->field->getVarName()} = \${$this->field->getVarName()};
return \$this;
PHP
        );

        return $this;
    }
}
