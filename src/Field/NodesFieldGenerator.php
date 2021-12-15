<?php

declare(strict_types=1);

namespace RZ\Roadiz\EntityGenerator\Field;

use RZ\Roadiz\Contracts\NodeType\NodeTypeFieldInterface;
use RZ\Roadiz\Contracts\NodeType\NodeTypeResolverInterface;
use Symfony\Component\String\UnicodeString;

class NodesFieldGenerator extends AbstractFieldGenerator
{
    private NodeTypeResolverInterface $nodeTypeResolver;

    /**
     * @param NodeTypeFieldInterface $field
     * @param NodeTypeResolverInterface $nodeTypeResolver
     * @param array $options
     */
    public function __construct(NodeTypeFieldInterface $field, NodeTypeResolverInterface $nodeTypeResolver, array $options = [])
    {
        parent::__construct($field, $options);
        $this->nodeTypeResolver = $nodeTypeResolver;
    }

    /**
     * Generate PHP code for current doctrine field.
     *
     * @return string
     */
    public function getField(): string
    {
        return $this->getFieldGetter() .
            $this->getFieldAlternativeGetter() .
            $this->getFieldSetter() . PHP_EOL;
    }

    protected function getSerializationAnnotations(): array
    {
        $annotations = parent::getSerializationAnnotations();
        $annotations[] = '@Serializer\VirtualProperty';
        $annotations[] = '@Serializer\SerializedName("' . $this->field->getVarName() . '")';
        $annotations[] = '@Serializer\Type("array<' .
            (new UnicodeString($this->options['parent_class']))->trimStart('\\')->toString() .
            '>")';
        // Add whitespace before each line for PHPDoc syntax
        return array_map(function ($line) {
            $line = trim($line);
            return !empty($line) ? ' ' . $line : '';
        }, $annotations);
    }

    protected function getDefaultSerializationGroups(): array
    {
        $groups = parent::getDefaultSerializationGroups();
        $groups[] = 'nodes_sources_nodes';
        return $groups;
    }

    /**
     * @return string
     */
    protected function getFieldSourcesName(): string
    {
        return $this->field->getVarName() . 'Sources';
    }
    /**
     * @return bool
     */
    protected function hasOnlyOneNodeType()
    {
        if (null !== $this->field->getDefaultValues()) {
            return count(explode(',', $this->field->getDefaultValues() ?? '')) === 1;
        }
        return false;
    }

    /**
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        if (null !== $this->field->getDefaultValues() && $this->hasOnlyOneNodeType() === true) {
            $nodeTypeName = trim(explode(',', $this->field->getDefaultValues() ?? '')[0]);

            $nodeType = $this->nodeTypeResolver->get($nodeTypeName);
            if (null !== $nodeType) {
                $className = $nodeType->getSourceEntityFullQualifiedClassName();
                return (new UnicodeString($className))->startsWith('\\') ?
                    $className :
                    '\\' . $className;
            }
        }
        return $this->options['parent_class'];
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

        $autodoc = '';
        $fieldAutoDoc = $this->getFieldAutodoc(true);
        if (!empty($fieldAutoDoc)) {
            $autodoc = PHP_EOL .
                static::ANNOTATION_PREFIX .
                implode(PHP_EOL . static::ANNOTATION_PREFIX, $fieldAutoDoc);
        }

        return '
    /**
     * ' . $this->getFieldSourcesName() . ' NodesSources direct field buffer.
     * (Virtual field, this var is a buffer)
     *' . $autodoc . '
     * @var ' . $this->getRepositoryClass() . '[]|null
     */
    private ?array $' . $this->getFieldSourcesName() . ' = null;

    /**
     * @return ' . $this->getRepositoryClass() . '[] ' . $this->field->getVarName() . ' nodes-sources array' . $serializer . '
     */
    public function ' . $this->field->getGetterName() . 'Sources(): array
    {
        if (null === $this->' . $this->getFieldSourcesName() . ') {
            if (
                null !== $this->objectManager &&
                null !== $this->getNode() &&
                null !== $this->getNode()->getNodeType()
            ) {
                $this->' . $this->getFieldSourcesName() . ' = $this->objectManager
                    ->getRepository(' . $this->getRepositoryClass() . '::class)
                    ->findByNodesSourcesAndFieldAndTranslation(
                        $this,
                        $this->getNode()->getNodeType()->getFieldByName("' . $this->field->getName() . '")
                    );
            } else {
                $this->' . $this->getFieldSourcesName() . ' = [];
            }
        }
        return $this->' . $this->getFieldSourcesName() . ';
    }' . PHP_EOL;
    }

    /**
     * @inheritDoc
     */
    public function getFieldSetter(): string
    {
        return '
    /**
     * @param ' . $this->getRepositoryClass() . '[]|null $' . $this->getFieldSourcesName() . '
     *
     * @return $this
     */
    public function ' . $this->field->getSetterName() . 'Sources(?array $' . $this->getFieldSourcesName() . ')
    {
        $this->' . $this->getFieldSourcesName() . ' = $' . $this->getFieldSourcesName() . ';

        return $this;
    }' . PHP_EOL;
    }
}
