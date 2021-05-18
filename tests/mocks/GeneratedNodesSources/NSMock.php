<?php
declare(strict_types=1);
/*
 * THIS IS A GENERATED FILE, DO NOT EDIT IT
 * IT WILL BE RECREATED AT EACH NODE-TYPE UPDATE
 */
namespace tests\mocks\GeneratedNodesSources;

use JMS\Serializer\Annotation as Serializer;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * DO NOT EDIT
 * Generated custom node-source type by Roadiz.
 *
 * @ORM\Entity(repositoryClass="mock\Entity\Repository\NodesSourcesRepository")
 * @ORM\Table(name="ns_mock", indexes={@ORM\Index(columns={"foo_datetime"}),@ORM\Index(columns={"foo_decimal_excluded"})})
 */
class NSMock extends \mock\Entity\NodesSources
{
    /**
     * Foo DateTime field.
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="datetime", nullable=true, name="foo_datetime")
     * @Serializer\Groups({"nodes_sources", "nodes_sources_default", "foo_datetime"})
     * @Serializer\MaxDepth(2)
     */
    private $fooDatetime;

    /**
     * @return mixed
     */
    public function getFooDatetime()
    {
        return $this->fooDatetime;
    }

    /**
     * @param mixed $fooDatetime
     *
     * @return $this
     */
    public function setFooDatetime($fooDatetime)
    {
        $this->fooDatetime = $fooDatetime;

        return $this;
    }


    /**
     * Foo field.
     * Maecenas sed diam eget risus varius blandit sit amet non magna.
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true, name="foo", length=250)
     * @Serializer\Groups({"nodes_sources", "nodes_sources_default"})
     * @Serializer\MaxDepth(1)
     */
    private $foo;

    /**
     * @return mixed
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @param mixed $foo
     *
     * @return $this
     */
    public function setFoo($foo)
    {
        $this->foo = $foo;

        return $this;
    }


    /**
     * Foo markdown field.
     * Maecenas sed diam eget risus varius blandit sit amet non magna.
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="text", nullable=true, name="foo_markdown")
     * @Serializer\Groups({"nodes_sources", "nodes_sources_default"})
     * @Serializer\MaxDepth(1)
     */
    private $fooMarkdown;

    /**
     * @return mixed
     */
    public function getFooMarkdown()
    {
        return $this->fooMarkdown;
    }

    /**
     * @param mixed $fooMarkdown
     *
     * @return $this
     */
    public function setFooMarkdown($fooMarkdown)
    {
        $this->fooMarkdown = $fooMarkdown;

        return $this;
    }


    /**
     * Foo excluded markdown field.
     * Maecenas sed diam eget risus varius blandit sit amet non magna.
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="text", nullable=true, name="foo_markdown_excluded")
     * @Serializer\Exclude()
     */
    private $fooMarkdownExcluded;

    /**
     * @return mixed
     */
    public function getFooMarkdownExcluded()
    {
        return $this->fooMarkdownExcluded;
    }

    /**
     * @param mixed $fooMarkdownExcluded
     *
     * @return $this
     */
    public function setFooMarkdownExcluded($fooMarkdownExcluded)
    {
        $this->fooMarkdownExcluded = $fooMarkdownExcluded;

        return $this;
    }


    /**
     * Foo expression excluded decimal.
     * Maecenas sed diam eget risus varius blandit sit amet non magna.
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="decimal", nullable=true, name="foo_decimal_excluded", precision=18, scale=3)
     * @Serializer\Groups({"nodes_sources", "nodes_sources_default"})
     * @Serializer\MaxDepth(2)
     * @Serializer\Exclude(if="object.foo == 'test'")
     * @Serializer\Type("double")
     */
    private $fooDecimalExcluded;

    /**
     * @return mixed
     */
    public function getFooDecimalExcluded()
    {
        return $this->fooDecimalExcluded;
    }

    /**
     * @param mixed $fooDecimalExcluded
     *
     * @return $this
     */
    public function setFooDecimalExcluded($fooDecimalExcluded)
    {
        $this->fooDecimalExcluded = (double) $fooDecimalExcluded;

        return $this;
    }


    /**
     * Bar documents field.
     * Maecenas sed diam eget risus varius blandit sit amet non magna.
     *
     * (Virtual field, this var is a buffer)
     * @Serializer\Exclude
     */
    private $bar;

    /**
     * @return \mock\Entity\Document[] Documents array
     * @Serializer\Groups({"nodes_sources", "nodes_sources_default", "nodes_sources_documents"})
     * @Serializer\MaxDepth(1)
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("bar")
     */
    public function getBar()
    {
        if (null === $this->bar) {
            if (null !== $this->objectManager) {
                $this->bar = $this->objectManager
                    ->getRepository(\mock\Entity\Document::class)
                    ->findByNodeSourceAndField(
                        $this,
                        $this->getNode()->getNodeType()->getFieldByName("bar")
                    );
            } else {
                $this->bar = [];
            }
        }
        return $this->bar;
    }

    /**
     * @param \mock\Entity\Document $document
     *
     * @return $this
     */
    public function addBar(\mock\Entity\Document $document)
    {
        $field = $this->getNode()->getNodeType()->getFieldByName("bar");
        if (null !== $field) {
            $nodeSourceDocument = new \mock\Entity\NodesSourcesDocument(
                $this,
                $document,
                $field
            );
            $this->objectManager->persist($nodeSourceDocument);
            $this->addDocumentsByFields($nodeSourceDocument);
            $this->bar = null;
        }
        return $this;
    }


    /**
     * ForBar nodes field.
     * Maecenas sed diam eget risus varius blandit sit amet non magna.
     *
     * (Virtual field, this var is a buffer)
     * @Serializer\Exclude
     */
    private $fooBar;

    /**
     * @return \mock\Entity\Node[] fooBar array
     * @deprecated Use getFooBarSources() instead to directly handle node-sources
     * @Serializer\Exclude
     */
    public function getFooBar()
    {
        trigger_error(
            'Method ' . __METHOD__ . ' is deprecated and will be removed in Roadiz v1.6. Use getFooBarSources instead to deal with NodesSources.',
            E_USER_DEPRECATED
        );

        if (null === $this->fooBar) {
            if (null !== $this->objectManager) {
                $this->fooBar = $this->objectManager
                    ->getRepository(\mock\Entity\Node::class)
                    ->findByNodeAndFieldAndTranslation(
                        $this->getNode(),
                        $this->getNode()->getNodeType()->getFieldByName("fooBar"),
                        $this->getTranslation()
                    );
            } else {
                $this->fooBar = [];
            }
        }
        return $this->fooBar;
    }
    /**
     * fooBarSources NodesSources direct field buffer.
     * (Virtual field, this var is a buffer)
     * @Serializer\Exclude
     * @var \mock\Entity\NodesSources[]|null
     */
    private $fooBarSources;

    /**
     * @return \mock\Entity\NodesSources[] fooBar nodes-sources array
     * @Serializer\Groups({"nodes_sources", "nodes_sources_default", "nodes_sources_nodes"})
     * @Serializer\MaxDepth(2)
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("fooBar")
     */
    public function getFooBarSources()
    {
        if (null === $this->fooBarSources) {
            if (null !== $this->objectManager) {
                $this->fooBarSources = $this->objectManager
                    ->getRepository(\mock\Entity\NodesSources::class)
                    ->findByNodesSourcesAndFieldAndTranslation(
                        $this,
                        $this->getNode()->getNodeType()->getFieldByName("foo_bar")
                    );
            } else {
                $this->fooBarSources = [];
            }
        }
        return $this->fooBarSources;
    }


    /**
     * @return string
     * @Serializer\VirtualProperty
     * @Serializer\Groups({"nodes_sources", "nodes_sources_default"})
     * @Serializer\SerializedName("@type")
     */
    public function getNodeTypeName(): string
    {
        return 'Mock';
    }

    /**
     * $this->nodeType->isReachable() proxy.
     *
     * @return bool Does this nodeSource is reachable over network?
     */
    public function isReachable(): bool
    {
        return true;
    }

    /**
     * $this->nodeType->isPublishable() proxy.
     *
     * @return bool Does this nodeSource is publishable with date and time?
     */
    public function isPublishable(): bool
    {
        return true;
    }

    public function __toString()
    {
        return '[NSMock] ' . parent::__toString();
    }
}
