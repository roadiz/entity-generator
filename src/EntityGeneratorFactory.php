<?php

declare(strict_types=1);

namespace RZ\Roadiz\EntityGenerator;

use RZ\Roadiz\Contracts\NodeType\NodeTypeInterface;
use RZ\Roadiz\Contracts\NodeType\NodeTypeResolverInterface;
use RZ\Roadiz\EntityGenerator\Field\DefaultValuesResolverInterface;

final class EntityGeneratorFactory
{
    private NodeTypeResolverInterface $nodeTypeResolverBag;
    private DefaultValuesResolverInterface $defaultValuesResolver;
    private array $options;

    public function __construct(
        NodeTypeResolverInterface $nodeTypeResolverBag,
        DefaultValuesResolverInterface $defaultValuesResolver,
        array $options
    ) {
        $this->nodeTypeResolverBag = $nodeTypeResolverBag;
        $this->defaultValuesResolver = $defaultValuesResolver;
        $this->options = $options;
    }

    public function create(NodeTypeInterface $nodeType): EntityGeneratorInterface
    {
        return new EntityGenerator($nodeType, $this->nodeTypeResolverBag, $this->defaultValuesResolver, $this->options);
    }

    public function createWithCustomRepository(NodeTypeInterface $nodeType): EntityGeneratorInterface
    {
        $options = $this->options;
        $options['repository_class'] =
            $options['namespace'] .
            '\\Repository\\' .
            $nodeType->getSourceEntityClassName() . 'Repository';

        return new EntityGenerator($nodeType, $this->nodeTypeResolverBag, $this->defaultValuesResolver, $options);
    }

    public function createCustomRepository(NodeTypeInterface $nodeType): RepositoryGeneratorInterface
    {
        $options = [
            'entity_namespace' => $this->options['namespace'],
            'parent_class' => 'RZ\Roadiz\CoreBundle\Repository\NodesSourcesRepository',
        ];
        $options['namespace'] = $this->options['namespace'] . '\\Repository';
        $options['class_name'] = $nodeType->getSourceEntityClassName() . 'Repository';

        return new RepositoryGenerator($nodeType, $options);
    }
}
