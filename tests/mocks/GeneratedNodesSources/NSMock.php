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
    private ?\DateTime $fooDatetime = null;

    /**
     * @return \DateTime|null
     */
    public function getFooDatetime(): ?\DateTime
    {
        return $this->fooDatetime;
    }

    /**
     * @param \DateTime|null $fooDatetime
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
    private ?string $foo = null;

    /**
     * @return string|null
     */
    public function getFoo(): ?string
    {
        return $this->foo;
    }

    /**
     * @param string|null $foo
     *
     * @return $this
     */
    public function setFoo($foo)
    {
        $this->foo = null !== $foo ?
            (string) $foo :
            null;

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
    private ?string $fooMarkdown = null;

    /**
     * @return string|null
     */
    public function getFooMarkdown(): ?string
    {
        return $this->fooMarkdown;
    }

    /**
     * @param string|null $fooMarkdown
     *
     * @return $this
     */
    public function setFooMarkdown($fooMarkdown)
    {
        $this->fooMarkdown = null !== $fooMarkdown ?
            (string) $fooMarkdown :
            null;

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
    private ?string $fooMarkdownExcluded = null;

    /**
     * @return string|null
     */
    public function getFooMarkdownExcluded(): ?string
    {
        return $this->fooMarkdownExcluded;
    }

    /**
     * @param string|null $fooMarkdownExcluded
     *
     * @return $this
     */
    public function setFooMarkdownExcluded($fooMarkdownExcluded)
    {
        $this->fooMarkdownExcluded = null !== $fooMarkdownExcluded ?
            (string) $fooMarkdownExcluded :
            null;

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
    private $fooDecimalExcluded = null;

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
        $this->fooDecimalExcluded = $fooDecimalExcluded;

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
     * For many_to_one field.
     * Default values: classname: \MyCustomEntity
     *     displayable: getName
     *
     * @Serializer\Groups({"nodes_sources", "nodes_sources_default"})
     * @Serializer\MaxDepth(2)
     * @var \MyCustomEntity|null
     * @ORM\ManyToOne(targetEntity="\MyCustomEntity")
     * @ORM\JoinColumn(name="foo_many_to_one_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private ?\MyCustomEntity $fooManyToOne = null;

    /**
     * @return \MyCustomEntity|null
     */
    public function getFooManyToOne(): ?\MyCustomEntity
    {
        return $this->fooManyToOne;
    }

    /**
     * @var \MyCustomEntity|null $fooManyToOne
     * @return $this
     */
    public function setFooManyToOne(?\MyCustomEntity $fooManyToOne = null)
    {
        $this->fooManyToOne = $fooManyToOne;

        return $this;
    }


    /**
     * For many_to_many field
     *
     * @Serializer\Groups({"nodes_sources", "nodes_sources_default"})
     * @Serializer\MaxDepth(2)
     * @var \Doctrine\Common\Collections\Collection<\MyCustomEntity>|array<\MyCustomEntity>
     * @ORM\ManyToMany(targetEntity="\MyCustomEntity")
     * @ORM\OrderBy(value={"name":"asc"})
     * @ORM\JoinTable(name="node_type_foo_many_to_many", joinColumns={ @ORM\JoinColumn(name="node_type_id", referencedColumnName="id") }, inverseJoinColumns={ @ORM\JoinColumn(name="foo_many_to_many_id", referencedColumnName="id") })
     */
    private $fooManyToMany;

    /**
     * @return \Doctrine\Common\Collections\Collection<\MyCustomEntity>
     */
    public function getFooManyToMany(): \Doctrine\Common\Collections\Collection
    {
        return $this->fooManyToMany;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection<\MyCustomEntity>|array<\MyCustomEntity> $fooManyToMany
     * @return $this
     */
    public function setFooManyToMany($fooManyToMany)
    {
        $this->fooManyToMany = $fooManyToMany;

        return $this;
    }


    /**
     * For many_to_many proxied field
     *
     * @Serializer\Exclude()
     * @var \Doctrine\Common\Collections\ArrayCollection<\Themes\MyTheme\Entities\PositionedCity>
     * @ORM\OneToMany(targetEntity="\Themes\MyTheme\Entities\PositionedCity", mappedBy="nodeSource", orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\OrderBy(value={"position":"ASC"})
     */
    private $fooManyToManyProxiedProxy;

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getFooManyToManyProxiedProxy()
    {
        return $this->fooManyToManyProxiedProxy;
    }

    /**
     * @Serializer\Groups({"nodes_sources", "nodes_sources_default"})
     * @Serializer\MaxDepth(1)
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("fooManyToManyProxied")
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getFooManyToManyProxied()
    {
        return $this->fooManyToManyProxiedProxy->map(function (\Themes\MyTheme\Entities\PositionedCity $proxyEntity) {
            return $proxyEntity->getCity();
        });
    }

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $fooManyToManyProxiedProxy
     * @Serializer\VirtualProperty()
     * @return $this
     */
    public function setFooManyToManyProxiedProxy($fooManyToManyProxiedProxy = null)
    {
        $this->fooManyToManyProxiedProxy = $fooManyToManyProxiedProxy;

        return $this;
    }
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection|null $fooManyToManyProxied
     * @return $this
     */
    public function setFooManyToManyProxied($fooManyToManyProxied = null)
    {
        foreach ($this->getFooManyToManyProxiedProxy() as $item) {
            $item->setNodeSource(null);
        }
        $this->fooManyToManyProxiedProxy->clear();
        if (null !== $fooManyToManyProxied) {
            $position = 0;
            foreach ($fooManyToManyProxied as $singleFooManyToManyProxied) {
                $proxyEntity = new \Themes\MyTheme\Entities\PositionedCity();
                $proxyEntity->setNodeSource($this);
                if ($proxyEntity instanceof \RZ\Roadiz\Core\AbstractEntities\PositionedInterface) {
                    $proxyEntity->setPosition(++$position);
                }
                $proxyEntity->setCity($singleFooManyToManyProxied);
                $this->fooManyToManyProxiedProxy->add($proxyEntity);
            }
        }

        return $this;
    }


    public function __construct(\mock\Entity\Node $node, \mock\Entity\Translation $translation)
    {
        parent::__construct($node, $translation);

        $this->fooManyToMany = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fooManyToManyProxiedProxy = new \Doctrine\Common\Collections\ArrayCollection();
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
