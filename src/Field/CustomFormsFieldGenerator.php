<?php

declare(strict_types=1);

namespace RZ\Roadiz\EntityGenerator\Field;

class CustomFormsFieldGenerator extends AbstractFieldGenerator
{
    protected function getSerializationAnnotations(): array
    {
        $annotations = parent::getSerializationAnnotations();
        $annotations[] = '@Serializer\VirtualProperty';
        $annotations[] = '@Serializer\SerializedName("' . $this->field->getVarName() . '")';
        // Add whitespace before each line for PHPDoc syntax
        return array_map(function ($line) {
            $line = trim($line);
            return !empty($line) ? ' ' . $line : '';
        }, $annotations);
    }

    protected function getDefaultSerializationGroups(): array
    {
        $groups = parent::getDefaultSerializationGroups();
        $groups[] = 'nodes_sources_custom_forms';
        return $groups;
    }

    protected function getFieldTypeDeclaration(): string
    {
        return '?array';
    }

    protected function getFieldDefaultValueDeclaration(): string
    {
        return 'null';
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
     * @return ' . $this->options['custom_form_class'] . '[] CustomForm array' . $serializer . '
     */
    public function ' . $this->field->getGetterName() . '(): array
    {
        if (null === $this->' . $this->field->getVarName() . ') {
            if (
                null !== $this->objectManager &&
                null !== $this->getNode() &&
                null !== $this->getNode()->getNodeType()
            ) {
                $this->' . $this->field->getVarName() . ' = $this->objectManager
                    ->getRepository(' . $this->options['custom_form_class'] . '::class)
                    ->findByNodeAndField(
                        $this->getNode(),
                        $this->getNode()->getNodeType()->getFieldByName("' . $this->field->getName() . '")
                    );
            } else {
                $this->' . $this->field->getVarName() . ' = [];
            }
        }
        return $this->' . $this->field->getVarName() . ';
    }' . PHP_EOL;
    }

    /**
     * Generate PHP setter method block.
     *
     * @return string
     */
    protected function getFieldSetter(): string
    {
        return '
    /**
     * @param ' . $this->options['custom_form_class'] . ' $customForm
     *
     * @return $this
     */
    public function add' . ucfirst($this->field->getVarName()) . '(' . $this->options['custom_form_class'] . ' $customForm)
    {
        if (
            null !== $this->objectManager &&
            null !== $this->getNode() &&
            null !== $this->getNode()->getNodeType()
        ) {
            $field = $this->getNode()->getNodeType()->getFieldByName("' . $this->field->getName() . '");
            if (null !== $field) {
                $nodeCustomForm = new ' . $this->options['custom_form_proxy_class'] . '(
                    $this->getNode(),
                    $customForm,
                    $field
                );
                $this->objectManager->persist($nodeCustomForm);
                $this->getNode()->addCustomForm($nodeCustomForm);
                $this->' . $this->field->getVarName() . ' = null;
            }
        }
        return $this;
    }' . PHP_EOL;
    }
}
