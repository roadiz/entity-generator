<?php

declare(strict_types=1);

/*
 * THIS IS A GENERATED FILE, DO NOT EDIT IT
 * IT WILL BE RECREATED AT EACH NODE-TYPE UPDATE
 */
namespace tests\mocks\GeneratedNodesSourcesWithRepository;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Serializer\Annotation as SymfonySerializer;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * DO NOT EDIT
 * Generated custom node-source type by Roadiz.
 *
 * @ORM\Entity(repositoryClass="tests\mocks\GeneratedNodesSourcesWithRepository\Repository\NSMockRepository")
 * @ORM\Table(name="ns_mock", indexes={@ORM\Index(columns={"foo_datetime"}),@ORM\Index(columns={"foo_decimal_excluded"})})
 */
class NSMock extends \mock\Entity\NodesSources
{
    /**
     * Foo DateTime field.
     *
     * Symfony serializer annotations must be set on property
     * @SymfonySerializer\SerializedName(serializedName="fooDatetime")
     * @SymfonySerializer\Groups({"nodes_sources", "nodes_sources_default", "foo_datetime"})
     * @SymfonySerializer\MaxDepth(2)
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="datetime", nullable=true, name="foo_datetime")
     * @Serializer\Groups({"nodes_sources", "nodes_sources_default", "foo_datetime"})
     * @Serializer\MaxDepth(2)
     * @Serializer\Type("DateTime")
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
     * Symfony serializer annotations must be set on property
     * @SymfonySerializer\SerializedName(serializedName="foo")
     * @SymfonySerializer\Groups({"nodes_sources", "nodes_sources_default"})
     * @SymfonySerializer\MaxDepth(1)
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true, name="foo", length=250)
     * @Serializer\Groups({"nodes_sources", "nodes_sources_default"})
     * @Serializer\MaxDepth(1)
     * @Serializer\Type("string")
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
     * Symfony serializer annotations must be set on property
     * @SymfonySerializer\SerializedName(serializedName="fooMarkdown")
     * @SymfonySerializer\Groups({"nodes_sources", "nodes_sources_default"})
     * @SymfonySerializer\MaxDepth(1)
     *
     * @Gedmo\Versioned
     * @ORM\Column(type="text", nullable=true, name="foo_markdown")
     * @Serializer\Groups({"nodes_sources", "nodes_sources_default"})
     * @Serializer\MaxDepth(1)
     * @Serializer\Type("string")
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
     * Symfony serializer annotations must be set on property
     * @SymfonySerializer\Ignore()
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
     * Symfony serializer annotations must be set on property
     * @SymfonySerializer\SerializedName(serializedName="fooDecimalExcluded")
     * @SymfonySerializer\Groups({"nodes_sources", "nodes_sources_default"})
     * @SymfonySerializer\MaxDepth(2)
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
     * @Serializer\Exclude
     *
     * Symfony serializer annotations must be set on property
     * @SymfonySerializer\SerializedName(serializedName="bar")
     * @SymfonySerializer\Groups({"nodes_sources", "nodes_sources_default", "nodes_sources_documents"})
     * @SymfonySerializer\MaxDepth(1)
     *
     * (Virtual field, this var is a buffer)
     */
    private ?array $bar = null;

    /**
     * @return \mock\Entity\Document[] Documents array
     * @Serializer\Groups({"nodes_sources", "nodes_sources_default", "nodes_sources_documents"})
     * @Serializer\MaxDepth(1)
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("bar")
     * @Serializer\Type("array<mock\Entity\Document>")
     */
    public function getBar(): array
    {
        if (null === $this->bar) {
            if (
                null !== $this->objectManager &&
                null !== $this->getNode() &&
                null !== $this->getNode()->getNodeType()
            ) {
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
        if (
            null !== $this->objectManager &&
            null !== $this->getNode() &&
            null !== $this->getNode()->getNodeType()
        ) {
            $field = $this->getNode()->getNodeType()->getFieldByName("bar");
            if (null !== $field) {
                $nodeSourceDocument = new \mock\Entity\NodesSourcesDocument(
                    $this,
                    $document,
                    $field
                );
                if (!$this->hasNodesSourcesDocuments($nodeSourceDocument)) {
                    $this->objectManager->persist($nodeSourceDocument);
                    $this->addDocumentsByFields($nodeSourceDocument);
                    $this->bar = null;
                }
            }
        }
        return $this;
    }


    /**
     * Custom forms field.
     * @Serializer\Exclude
     *
     * Symfony serializer annotations must be set on property
     * @SymfonySerializer\SerializedName(serializedName="theForms")
     * @SymfonySerializer\Groups({"nodes_sources", "nodes_sources_default", "nodes_sources_custom_forms"})
     * @SymfonySerializer\MaxDepth(2)
     *
     * (Virtual field, this var is a buffer)
     */
    private ?array $theForms = null;

    /**
     * @return \mock\Entity\CustomForm[] CustomForm array
     * @Serializer\Groups({"nodes_sources", "nodes_sources_default", "nodes_sources_custom_forms"})
     * @Serializer\MaxDepth(2)
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("theForms")
     */
    public function getTheForms(): array
    {
        if (null === $this->theForms) {
            if (
                null !== $this->objectManager &&
                null !== $this->getNode() &&
                null !== $this->getNode()->getNodeType()
            ) {
                $this->theForms = $this->objectManager
                    ->getRepository(\mock\Entity\CustomForm::class)
                    ->findByNodeAndField(
                        $this->getNode(),
                        $this->getNode()->getNodeType()->getFieldByName("the_forms")
                    );
            } else {
                $this->theForms = [];
            }
        }
        return $this->theForms;
    }

    /**
     * @param \mock\Entity\CustomForm $customForm
     *
     * @return $this
     */
    public function addTheForms(\mock\Entity\CustomForm $customForm)
    {
        if (
            null !== $this->objectManager &&
            null !== $this->getNode() &&
            null !== $this->getNode()->getNodeType()
        ) {
            $field = $this->getNode()->getNodeType()->getFieldByName("the_forms");
            if (null !== $field) {
                $nodeCustomForm = new \mock\Entity\NodesSourcesCustomForm(
                    $this->getNode(),
                    $customForm,
                    $field
                );
                $this->objectManager->persist($nodeCustomForm);
                $this->getNode()->addCustomForm($nodeCustomForm);
                $this->theForms = null;
            }
        }
        return $this;
    }


    /**
     * fooBarSources NodesSources direct field buffer.
     * (Virtual field, this var is a buffer)
     *
     * ForBar nodes field.
     * Maecenas sed diam eget risus varius blandit sit amet non magna.
     * @Serializer\Exclude
     *
     * Symfony serializer annotations must be set on property
     * @SymfonySerializer\SerializedName(serializedName="fooBar")
     * @SymfonySerializer\Groups({"nodes_sources", "nodes_sources_default", "nodes_sources_nodes"})
     * @SymfonySerializer\MaxDepth(2)
     * @var \mock\Entity\NodesSources[]|null
     */
    private ?array $fooBarSources = null;

    /**
     * @return \mock\Entity\NodesSources[] fooBar nodes-sources array
     * @Serializer\Groups({"nodes_sources", "nodes_sources_default", "nodes_sources_nodes"})
     * @Serializer\MaxDepth(2)
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("fooBar")
     * @Serializer\Type("array<mock\Entity\NodesSources>")
     */
    public function getFooBarSources(): array
    {
        if (null === $this->fooBarSources) {
            if (
                null !== $this->objectManager &&
                null !== $this->getNode() &&
                null !== $this->getNode()->getNodeType()
            ) {
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
     * @param \mock\Entity\NodesSources[]|null $fooBarSources
     *
     * @return $this
     */
    public function setFooBarSources(?array $fooBarSources)
    {
        $this->fooBarSources = $fooBarSources;

        return $this;
    }


    /**
     * fooBarTypedSources NodesSources direct field buffer.
     * (Virtual field, this var is a buffer)
     *
     * ForBar nodes typed field.
     * Default values: MockTwo
     * @Serializer\Exclude
     *
     * Symfony serializer annotations must be set on property
     * @SymfonySerializer\SerializedName(serializedName="fooBarTyped")
     * @SymfonySerializer\Groups({"nodes_sources", "nodes_sources_default", "nodes_sources_nodes"})
     * @SymfonySerializer\MaxDepth(2)
     * @var \tests\mocks\GeneratedNodesSources\NSMockTwo[]|null
     */
    private ?array $fooBarTypedSources = null;

    /**
     * @return \tests\mocks\GeneratedNodesSources\NSMockTwo[] fooBarTyped nodes-sources array
     * @Serializer\Groups({"nodes_sources", "nodes_sources_default", "nodes_sources_nodes"})
     * @Serializer\MaxDepth(2)
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("fooBarTyped")
     * @Serializer\Type("array<mock\Entity\NodesSources>")
     */
    public function getFooBarTypedSources(): array
    {
        if (null === $this->fooBarTypedSources) {
            if (
                null !== $this->objectManager &&
                null !== $this->getNode() &&
                null !== $this->getNode()->getNodeType()
            ) {
                $this->fooBarTypedSources = $this->objectManager
                    ->getRepository(\tests\mocks\GeneratedNodesSources\NSMockTwo::class)
                    ->findByNodesSourcesAndFieldAndTranslation(
                        $this,
                        $this->getNode()->getNodeType()->getFieldByName("foo_bar_typed")
                    );
            } else {
                $this->fooBarTypedSources = [];
            }
        }
        return $this->fooBarTypedSources;
    }

    /**
     * @param \tests\mocks\GeneratedNodesSources\NSMockTwo[]|null $fooBarTypedSources
     *
     * @return $this
     */
    public function setFooBarTypedSources(?array $fooBarTypedSources)
    {
        $this->fooBarTypedSources = $fooBarTypedSources;

        return $this;
    }


    /**
     * For many_to_one field.
     * Default values: classname: \MyCustomEntity
     *     displayable: getName
     *
     * Symfony serializer annotations must be set on property
     * @SymfonySerializer\SerializedName(serializedName="fooManyToOne")
     * @SymfonySerializer\Groups({"nodes_sources", "nodes_sources_default"})
     * @SymfonySerializer\MaxDepth(2)
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
     * @SymfonySerializer\Groups({"nodes_sources", "nodes_sources_default"})
     * @SymfonySerializer\MaxDepth(2)
     * @var \Doctrine\Common\Collections\Collection<\MyCustomEntity>
     * @ORM\ManyToMany(targetEntity="\MyCustomEntity")
     * @ORM\OrderBy(value={"name":"asc"})
     * @ORM\JoinTable(name="node_type_foo_many_to_many", joinColumns={ @ORM\JoinColumn(name="node_type_id", referencedColumnName="id", onDelete="CASCADE") }, inverseJoinColumns={ @ORM\JoinColumn(name="foo_many_to_many_id", referencedColumnName="id", onDelete="CASCADE") })
     */
    private \Doctrine\Common\Collections\Collection $fooManyToMany;

    /**
     * @return \Doctrine\Common\Collections\Collection<\MyCustomEntity>
     */
    public function getFooManyToMany(): \Doctrine\Common\Collections\Collection
    {
        return $this->fooManyToMany;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection<\MyCustomEntity> $fooManyToMany
     * @return $this
     */
    public function setFooManyToMany($fooManyToMany)
    {
        if ($fooManyToMany instanceof \Doctrine\Common\Collections\Collection) {
            $this->fooManyToMany = $fooManyToMany;
        } else {
            $this->fooManyToMany = new \Doctrine\Common\Collections\ArrayCollection($fooManyToMany);
        }

        return $this;
    }


    /**
     * For many_to_many proxied field
     *
     * @Serializer\Exclude()
     * @SymfonySerializer\Ignore()
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
     * @SymfonySerializer\SerializedName(serializedName="fooManyToManyProxied")
     * @SymfonySerializer\Groups({"nodes_sources", "nodes_sources_default"})
     * @SymfonySerializer\MaxDepth(1)
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
     * @SymfonySerializer\Groups({"nodes_sources", "nodes_sources_default"})
     * @SymfonySerializer\SerializedName(serializedName="@type")
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
