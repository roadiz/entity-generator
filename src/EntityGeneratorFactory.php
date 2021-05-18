<?php
declare(strict_types=1);

namespace RZ\Roadiz\EntityGenerator;

use RZ\Roadiz\Contracts\NodeType\NodeTypeInterface;
use RZ\Roadiz\Contracts\NodeType\NodeTypeResolverInterface;

final class EntityGeneratorFactory
{
    private NodeTypeResolverInterface $nodeTypeResolverBag;
    private array $options;

    /**
     * @param NodeTypeResolverInterface $nodeTypeResolverBag
     * @param array $options
     */
    public function __construct(NodeTypeResolverInterface $nodeTypeResolverBag, array $options)
    {
        $this->nodeTypeResolverBag = $nodeTypeResolverBag;
        $this->options = $options;
    }

    public function create(NodeTypeInterface $nodeType): EntityGenerator
    {
        return new EntityGenerator($nodeType, $this->nodeTypeResolverBag, $this->options);
    }
}
