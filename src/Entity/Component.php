<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComponentRepository")
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}, "enable_max_depth"=true},
 *     denormalizationContext={"groups"={"write"}, "enable_max_depth"=true}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ExampleEntityRepository")
 */
class Component
{
	/**
     *
     *
     * @var UuidInterface
     *
     * @ApiProperty(
     * 	   identifier=true,
     *     attributes={
     *         "openapi_context"={
     *         	   "description" = "The UUID identifier of this object",
     *             "type"="string",
     *             "format"="uuid",
     *             "example"="e2984465-190a-4562-829e-a8cca81aa35d"
     *         }
     *     }
     * )
     *
     * @Groups({"read"})
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
	 */
	private $id;
	
	/**
     * @Groups({"read","write"})
	 * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     max = 255
     * )
     * @Assert\NotBlank
	 */
	private $name;
	
	/**
     * @Groups({"read","write"})
	 * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     max = 255
     * )
	 */
	private $version;
	
	/**
     * @Groups({"read","write"})
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $description;
	
	/**
     * @Groups({"read","write"})
	 * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     max = 255
     * )
	 */
	private $logo;
	
	/**
     * @Groups({"read","write"})
     * @Gedmo\Slug(fields={"name"}, unique=true)
	 * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     max = 255
     * )
     * @Assert\NotBlank
	 */
	private $slug;
    
    /**
     * @Groups({"read","write"})
     * @ORM\Column(type="integer", length=11)
     * @Assert\Length(
     *     max = 11
     * )
     * @Assert\NotBlank
     */
    private $gitId;
    
    /**
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     max = 255
     * )
     * @Assert\NotBlank
     */
    private $gitType;

    /**
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     max = 255
     * )
     * @Assert\NotBlank
     */
    private $git;

    /**
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     max = 255
     * )
     */
    private $openapi;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Organisation", mappedBy="components",cascade={"persist"})
     * @Groups({"read","write"})
     * @MaxDepth(1)
     */
    private $organisations;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Organisation")
     * @ORM\JoinColumn(nullable=false)
     * @MaxDepth(1)
     * @Groups({"read","write"})
     */
    private $owner;

    public function __construct()
    {
        $this->organisations = new ArrayCollection();
    }
    
    public function getId()
    {
    	return $this->id;
    }
    
	public function getName(): ?string
                                 	{
                                 		return $this->name;
                                 	}
	
	public function setName(string $name): self
                                 	{
                                 		$this->name = $name;
                                 		return $this;
                                 	}
	
	public function getVersion(): ?string
                                 	{
                                 		return $this->version;
                                 	}
	
	public function setVersion(string $version): self
                                 	{
                                 		$this->version= $version;
                                 		return $this;
                                 	}
	
	public function getDescription(): ?string
                                 	{
                                 		return $this->description;
                                 	}
	
	public function setDescription(?string $description): self
                                 	{
                                 		$this->description = $description;
                                                                     		
                                 		return $this;
                                 	}
	
	public function getLogo(): ?string
                                 	{
                                 		return $this->logo;
                                 	}
	
	public function setLogo(?string $logo): self
                                 	{
                                 		$this->logo = $logo;
                                                                     		
                                 		return $this;
                                 	}
	
	public function getSlug(): ?string
                                 	{
                                 		return $this->slug;
                                 	}
	
	public function setSlug(string $slug): self
                                 	{
                                 		$this->slug = $slug;
                                                                     		
                                 		return $this;
                                 	}

    
    public function getGitId(): ?string
    {
    	return $this->gitId;
    }
    
    public function setGitId(string $gitId): self
    {
    	$this->gitId = $gitId;
    	
    	return $this;
    }
    
    public function getGitType(): ?string
    {
        return $this->gitType;
    }

    public function setGitType(string $gitType): self
    {
        $this->gitType = $gitType;

        return $this;
    }

    public function getGit(): ?string
    {
        return $this->git;
    }

    public function setGit(string $git): self
    {
        $this->git = $git;

        return $this;
    }

    public function getOpenapi(): ?string
    {
        return $this->openapi;
    }

    public function setOpenapi(?string $openapi): self
    {
        $this->openapi = $openapi;

        return $this;
    }

    /**
     * @return Collection|Organisation[]
     */
    public function getOrganisations(): Collection
    {
        return $this->organisations;
    }

    public function addOrganisation(Organisation $organisation): self
    {
        if (!$this->organisations->contains($organisation)) {
            $this->organisations[] = $organisation;
            $organisation->addComponent($this);
        }

        return $this;
    }

    public function removeOrganisation(Organisation $organisation): self
    {
        if ($this->organisations->contains($organisation)) {
            $this->organisations->removeElement($organisation);
            $organisation->removeComponent($this);
        }

        return $this;
    }

    public function getOwner(): ?Organisation
    {
        return $this->owner;
    }

    public function setOwner(?Organisation $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
