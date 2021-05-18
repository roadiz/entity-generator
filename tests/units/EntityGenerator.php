<?php
declare(strict_types=1);

namespace tests\units\RZ\Roadiz\EntityGenerator;

use atoum;
use Doctrine\Common\Collections\ArrayCollection;
use RZ\Roadiz\Contracts\NodeType\NodeTypeInterface;
use RZ\Roadiz\Contracts\NodeType\NodeTypeResolverInterface;
use tests\mocks\NodeTypeField;

class EntityGenerator extends atoum
{
    public function testGetClassContent ()
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
                    ->setName('foo_bar')
                    ->setTypeName('nodes')
                    ->setVirtual(true)
                    ->setLabel('ForBar nodes field')
                    ->setDescription('Maecenas sed diam eget risus varius blandit sit amet non magna')
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
        $mockNodeTypeResolver = $this->newMockInstance(NodeTypeResolverInterface::class);

//        $dumpInstance = $this->newTestedInstance($mockNodeType, $mockNodeTypeResolver, [
//            'parent_class' => '\mock\Entity\NodesSources',
//            'node_class' => '\mock\Entity\Node',
//            'translation_class' => '\mock\Entity\Translation',
//            'document_class' => '\mock\Entity\Document',
//            'document_proxy_class' => '\mock\Entity\NodesSourcesDocument',
//            'custom_form_class' => '\mock\Entity\CustomForm',
//            'custom_form_proxy_class' => '\mock\Entity\NodesSourcesCustomForm',
//            'repository_class' => '\mock\Entity\Repository\NodesSourcesRepository',
//            'namespace' => '\tests\mocks\GeneratedNodesSources',
//            'use_native_json' => true
//        ]);
//        var_dump($dumpInstance->getClassContent());

        $this
            // creation of a new instance of the tested class
            ->given($this->newTestedInstance($mockNodeType, $mockNodeTypeResolver, [
                'parent_class' => '\mock\Entity\NodesSources',
                'node_class' => '\mock\Entity\Node',
                'translation_class' => '\mock\Entity\Translation',
                'document_class' => '\mock\Entity\Document',
                'document_proxy_class' => '\mock\Entity\NodesSourcesDocument',
                'custom_form_class' => '\mock\Entity\CustomForm',
                'custom_form_proxy_class' => '\mock\Entity\NodesSourcesCustomForm',
                'repository_class' => '\mock\Entity\Repository\NodesSourcesRepository',
                'namespace' => '\tests\mocks\GeneratedNodesSources',
                'use_native_json' => true
            ]))
            ->then
            ->string($this->testedInstance->getClassContent())
            ->isEqualTo(file_get_contents(dirname(__DIR__) . '/mocks/GeneratedNodesSources/NSMock.php'))
        ;

        /**
         * TEST without leading slashs
         */
        $this
            // creation of a new instance of the tested class
            ->given($this->newTestedInstance($mockNodeType, $mockNodeTypeResolver, [
                'parent_class' => 'mock\Entity\NodesSources',
                'node_class' => 'mock\Entity\Node',
                'translation_class' => 'mock\Entity\Translation',
                'document_class' => 'mock\Entity\Document',
                'document_proxy_class' => 'mock\Entity\NodesSourcesDocument',
                'custom_form_class' => 'mock\Entity\CustomForm',
                'custom_form_proxy_class' => 'mock\Entity\NodesSourcesCustomForm',
                'repository_class' => 'mock\Entity\Repository\NodesSourcesRepository',
                'namespace' => 'tests\mocks\GeneratedNodesSources',
                'use_native_json' => true
            ]))
            ->then
            ->string($this->testedInstance->getClassContent())
            ->isEqualTo(file_get_contents(dirname(__DIR__) . '/mocks/GeneratedNodesSources/NSMock.php'))
        ;
    }
}