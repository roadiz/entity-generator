<?php
declare(strict_types=1);

namespace RZ\Roadiz\EntityGenerator\Field;

use Symfony\Component\String\UnicodeString;

class DocumentsFieldGenerator extends AbstractFieldGenerator
{
    protected function getSerializationAnnotations(): array
    {
        $annotations = parent::getSerializationAnnotations();
        $annotations[] = '@Serializer\VirtualProperty';
        $annotations[] = '@Serializer\SerializedName("'.$this->field->getVarName().'")';
        $annotations[] = '@Serializer\Type("array<'.
            (new UnicodeString($this->options['document_class']))->trimStart('\\')->toString().
            '>")';
        return $annotations;
    }

    protected function getDefaultSerializationGroups(): array
    {
        $groups = parent::getDefaultSerializationGroups();
        $groups[] = 'nodes_sources_documents';
        return $groups;
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
     * @return '.$this->options['document_class'].'[] Documents array' . $serializer . '
     */
    public function '.$this->field->getGetterName().'()
    {
        if (null === $this->' . $this->field->getVarName() . ') {
            if (null !== $this->objectManager &&
                null !== $this->getNode() &&
                null !== $this->getNode()->getNodeType()) {
                $this->' . $this->field->getVarName() . ' = $this->objectManager
                    ->getRepository('.$this->options['document_class'].'::class)
                    ->findByNodeSourceAndField(
                        $this,
                        $this->getNode()->getNodeType()->getFieldByName("'.$this->field->getName().'")
                    );
            } else {
                $this->' . $this->field->getVarName() . ' = [];
            }
        }
        return $this->' . $this->field->getVarName() . ';
    }'.PHP_EOL;
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
     * @param '.$this->options['document_class'].' $document
     *
     * @return $this
     */
    public function add'.ucfirst($this->field->getVarName()).'('.$this->options['document_class'].' $document)
    {
        if (null !== $this->objectManager &&
            null !== $this->getNode() &&
            null !== $this->getNode()->getNodeType()) {
            $field = $this->getNode()->getNodeType()->getFieldByName("'.$this->field->getName().'");
            if (null !== $field) {
                $nodeSourceDocument = new '.$this->options['document_proxy_class'].'(
                    $this,
                    $document,
                    $field
                );
                $this->objectManager->persist($nodeSourceDocument);
                $this->addDocumentsByFields($nodeSourceDocument);
                $this->' . $this->field->getVarName() . ' = null;
            }
        }
        return $this;
    }'.PHP_EOL;
    }
}
