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

    protected function getSerializationAnnotations(): array
    {
        $annotations = parent::getSerializationAnnotations();
        $annotations[] = '@Serializer\VirtualProperty';
        $annotations[] = '@Serializer\SerializedName("'.$this->field->getVarName().'")';
        $annotations[] = '@SymfonySerializer\SerializedName("'.$this->field->getVarName().'")';
        $annotations[] = '@Serializer\Type("array<'.
            (new UnicodeString($this->options['parent_class']))->trimStart('\\')->toString().
            '>")';
        return $annotations;
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
        return $this->field->getVarName().'Sources';
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

        return '
    /**
     * @return '.$this->options['node_class'].'[] '.$this->field->getVarName().' array
     * @deprecated Use '.$this->field->getGetterName().'Sources() instead to directly handle node-sources
     * @Serializer\Exclude
     * @SymfonySerializer\Ignore()
     */
    public function '.$this->field->getGetterName().'()
    {
        trigger_error(
            \'Method \' . __METHOD__ . \' is deprecated and will be removed in Roadiz v1.6. Use '.$this->field->getGetterName().'Sources instead to deal with NodesSources.\',
            E_USER_DEPRECATED
        );

        if (null === $this->' . $this->field->getVarName() . ') {
            if (null !== $this->objectManager &&
                null !== $this->getNode() &&
                null !== $this->getNode()->getNodeType()) {
                $this->' . $this->field->getVarName() . ' = $this->objectManager
                    ->getRepository('.$this->options['node_class'].'::class)
                    ->findByNodeAndFieldAndTranslation(
                        $this->getNode(),
                        $this->getNode()->getNodeType()->getFieldByName("'.$this->field->getVarName().'"),
                        $this->getTranslation()
                    );
            } else {
                $this->' . $this->field->getVarName() . ' = [];
            }
        }
        return $this->' . $this->field->getVarName() . ';
    }
    /**
     * ' . $this->getFieldSourcesName() .' NodesSources direct field buffer.
     * (Virtual field, this var is a buffer)
     * @Serializer\Exclude
     * @SymfonySerializer\Ignore()
     * @var '.$this->getRepositoryClass().'[]|null
     */
    private $'.$this->getFieldSourcesName().';

    /**
     * @return '.$this->getRepositoryClass().'[] '.$this->field->getVarName().' nodes-sources array' . $serializer . '
     */
    public function '.$this->field->getGetterName().'Sources()
    {
        if (null === $this->' . $this->getFieldSourcesName() . ') {
            if (null !== $this->objectManager &&
                null !== $this->getNode() &&
                null !== $this->getNode()->getNodeType()) {
                $this->' . $this->getFieldSourcesName() . ' = $this->objectManager
                    ->getRepository('. $this->getRepositoryClass() .'::class)
                    ->findByNodesSourcesAndFieldAndTranslation(
                        $this,
                        $this->getNode()->getNodeType()->getFieldByName("'.$this->field->getName().'")
                    );
            } else {
                $this->' . $this->getFieldSourcesName() . ' = [];
            }
        }
        return $this->' . $this->getFieldSourcesName() . ';
    }'.PHP_EOL;
    }

    /**
     * @inheritDoc
     */
    public function getFieldSetter(): string
    {
        return '
    /**
     * @param '.$this->getRepositoryClass().'[]|null $'.$this->getFieldSourcesName().'
     *
     * @return $this
     */
    public function '.$this->field->getSetterName().'Sources(?array $'.$this->getFieldSourcesName().')
    {
        $this->' . $this->getFieldSourcesName() . ' = $'.$this->getFieldSourcesName().';

        return $this;
    }'.PHP_EOL;
    }
}
