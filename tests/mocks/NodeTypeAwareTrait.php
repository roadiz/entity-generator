<?php
declare(strict_types=1);

namespace tests\mocks;

use Doctrine\Common\Collections\ArrayCollection;
use RZ\Roadiz\Contracts\NodeType\NodeTypeInterface;
use RZ\Roadiz\Contracts\NodeType\NodeTypeResolverInterface;

trait NodeTypeAwareTrait
{
    protected function getMockNodeType()
    {
        $mockNodeType = $this->newMockInstance(NodeTypeInterface::class);
        $mockNodeType->getMockController()->getFields = function() {
            return new ArrayCollection([
                (new NodeTypeField())
                    ->setName('foo_datetime')
                    ->setTypeName('datetime')
                    ->setDoctrineType('datetime')
                    ->setSerializationGroups([
                        'nodes_sources',
                        'nodes_sources_default',
                        'foo_datetime'
                    ])
                    ->setVirtual(false)
                    ->setLabel('Foo DateTime field')
                    ->setIndexed(true),
                (new NodeTypeField())
                    ->setName('foo')
                    ->setTypeName('string')
                    ->setVirtual(false)
                    ->setSerializationMaxDepth(1)
                    ->setLabel('Foo field')
                    ->setDescription('Maecenas sed diam eget risus varius blandit sit amet non magna')
                    ->setIndexed(false),
                (new NodeTypeField())
                    ->setName('fooIndexed')
                    ->setTypeName('string')
                    ->setVirtual(false)
                    ->setSerializationMaxDepth(1)
                    ->setLabel('Foo indexed field')
                    ->setDescription('Maecenas sed diam eget risus varius blandit sit amet non magna')
                    ->setIndexed(true),
                (new NodeTypeField())
                    ->setName('boolIndexed')
                    ->setTypeName('bool')
                    ->setDoctrineType('boolean')
                    ->setVirtual(false)
                    ->setSerializationMaxDepth(1)
                    ->setLabel('Bool indexed field')
                    ->setDescription('Maecenas sed diam eget risus varius blandit sit amet non magna')
                    ->setIndexed(true),
                (new NodeTypeField())
                    ->setName('foo_markdown')
                    ->setTypeName('markdown')
                    ->setDoctrineType('text')
                    ->setVirtual(false)
                    ->setSerializationMaxDepth(1)
                    ->setLabel('Foo markdown field')
                    ->setDescription('Maecenas sed diam eget risus varius blandit sit amet non magna')
                    ->setIndexed(false),
                (new NodeTypeField())
                    ->setName('foo_markdown_excluded')
                    ->setTypeName('markdown')
                    ->setDoctrineType('text')
                    ->setVirtual(false)
                    ->setExcludedFromSerialization(true)
                    ->setLabel('Foo excluded markdown field')
                    ->setDescription('Maecenas sed diam eget risus varius blandit sit amet non magna')
                    ->setIndexed(false),
                (new NodeTypeField())
                    ->setName('foo_decimal_excluded')
                    ->setTypeName('decimal')
                    ->setDoctrineType('decimal')
                    ->setVirtual(false)
                    ->setSerializationExclusionExpression('object.foo == \'test\'')
                    ->setLabel('Foo expression excluded decimal')
                    ->setDescription('Maecenas sed diam eget risus varius blandit sit amet non magna')
                    ->setIndexed(true),
                (new NodeTypeField())
                    ->setName('bar')
                    ->setTypeName('documents')
                    ->setSerializationMaxDepth(1)
                    ->setVirtual(true)
                    ->setLabel('Bar documents field')
                    ->setDescription('Maecenas sed diam eget risus varius blandit sit amet non magna')
                    ->setIndexed(false),
                (new NodeTypeField())
                    ->setName('the_forms')
                    ->setTypeName('custom_forms')
                    ->setVirtual(true)
                    ->setLabel('Custom forms field')
                    ->setIndexed(false),
                (new NodeTypeField())
                    ->setName('foo_bar')
                    ->setTypeName('nodes')
                    ->setVirtual(true)
                    ->setLabel('ForBar nodes field')
                    ->setDescription('Maecenas sed diam eget risus varius blandit sit amet non magna')
                    ->setIndexed(false),
                (new NodeTypeField())
                    ->setName('foo_bar_typed')
                    ->setTypeName('nodes')
                    ->setVirtual(true)
                    ->setLabel('ForBar nodes typed field')
                    ->setIndexed(false)
                    ->setDefaultValues('MockTwo'),
                (new NodeTypeField())
                    ->setName('foo_many_to_one')
                    ->setTypeName('many_to_one')
                    ->setVirtual(false)
                    ->setLabel('For many_to_one field')
                    ->setDefaultValues(<<<EOT
classname: \MyCustomEntity
displayable: getName
EOT)
                    ->setIndexed(false),
                (new NodeTypeField())
                    ->setName('foo_many_to_many')
                    ->setTypeName('many_to_many')
                    ->setVirtual(false)
                    ->setLabel('For many_to_many field')
                    ->setDefaultValues(<<<EOT
classname: \MyCustomEntity
displayable: getName
orderBy:
    - field: name
      direction: asc
EOT)
                    ->setIndexed(false),
                (new NodeTypeField())
                    ->setName('foo_many_to_many_proxied')
                    ->setTypeName('many_to_many')
                    ->setVirtual(false)
                    ->setSerializationMaxDepth(1)
                    ->setLabel('For many_to_many proxied field')
                    ->setDefaultValues(<<<EOT
classname: \MyCustomEntity
displayable: getName
orderBy:
    - field: name
      direction: asc
# Use a proxy entity
proxy:
    classname: Themes\MyTheme\Entities\PositionedCity
    self: nodeSource
    relation: city
    # This order will preserve position
    orderBy:
        - field: position
          direction: ASC
EOT)
                    ->setIndexed(false),
            ]);
        };
        $mockNodeType->getMockController()->getSourceEntityTableName = function() {
            return 'ns_mock';
        };
        $mockNodeType->getMockController()->getSourceEntityClassName = function() {
            return 'NSMock';
        };
        $mockNodeType->getMockController()->getName = function() {
            return 'Mock';
        };
        $mockNodeType->getMockController()->isReachable = function() {
            return true;
        };
        $mockNodeType->getMockController()->isPublishable = function() {
            return true;
        };
        return $mockNodeType;
    }

    protected function getMockNodeTypeResolver()
    {
        $mockNodeTypeResolver = $this->newMockInstance(NodeTypeResolverInterface::class);
        $test = $this;
        $mockNodeTypeResolver->getMockController()->get = function(string $nodeTypeName) use ($test) {
            $mockNodeType = $test->newMockInstance(NodeTypeInterface::class);
            $mockNodeType->getMockController()->getSourceEntityFullQualifiedClassName = function() use ($nodeTypeName) {
                return 'tests\mocks\GeneratedNodesSources\NS' . $nodeTypeName;
            };
            return $mockNodeType;
        };
        return $mockNodeTypeResolver;
    }
}
