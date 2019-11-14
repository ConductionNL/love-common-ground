<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrganisationRepository")
 *
 */
class Organisation
{
	/**
     *
     *
     * @var UuidInterface
     *
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
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     max = 255
     * )
     */
    private $github;

    /**
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     max = 255
     * )
     */
    private $gitlab;

    /**
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     max = 255
     * )
     */
    private $bitbucket;
    
    /**
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     max = 255
     * )
     */
    private $githubId;
    
    /**
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     max = 255
     * )
     */
    private $gitlabId;
    
    /**
     *
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     max = 255
     * )
     */
    private $bitbucketId;

    /**
     * @Groups({"read","write"})
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="organisations")
     * @MaxDepth(1)
     */
    private $users;
    
    /**
     * @Groups({"read","write"})
     * @ORM\ManyToMany(targetEntity="App\Entity\User")     
     * @ORM\JoinTable(
     *  name="organisation_admin"
     * )
     * @MaxDepth(1)
     */
    private $admins;

    /**
     * @Groups({"read","write"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $css;

    /**
     * @Groups({"read","write"})
     * @ORM\Column(type="text", nullable=true)
     */
    private $javascript;

    /**
     * @Groups({"read","write"})
     * @ORM\OneToMany(targetEntity="App\Entity\Team", mappedBy="organisation", orphanRemoval=true)
     * @MaxDepth(1)
     */
    private $teams;

    /**
     * @Groups({"read","write"})
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $subdomain;

    /**
     * @Groups({"read","write"})
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $vetted;

    /**
     * @Groups({"read","write"})
     * @MaxDepth(1)
     * @ORM\ManyToMany(targetEntity="App\Entity\Component", inversedBy="organisations")
     */
    private $components;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->admins = new ArrayCollection();
        $this->teams = new ArrayCollection();
        $this->components = new ArrayCollection();
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

    public function getGithub(): ?string
    {
        return $this->github;
    }

    public function setGithub(?string $github): self
    {
        $this->github = $github;

        return $this;
    }

    public function getGitlab(): ?string
    {
        return $this->gitlab;
    }

    public function setGitlab(?string $gitlab): self
    {
        $this->gitlab = $gitlab;

        return $this;
    }

    public function getBitbucket(): ?string
    {
        return $this->bitbucket;
    }

    public function setBitbucket(?string $bitbucket): self
    {
        $this->bitbucket = $bitbucket;

        return $this;
    }
    
    public function getGithubId(): ?string
    {
    	return $this->githubId;
    }
    
    public function setGithubId(?string $githubId): self
    {
    	$this->githubId= $githubId;
    	
    	return $this;
    }
    
    public function getGitlabId(): ?string
    {
    	return $this->gitlabId;
    }
    
    public function setGitlabId(?string $gitlabId): self
    {
    	$this->gitlabId= $gitlabId;
    	
    	return $this;
    }
    
    public function getBitbucketId(): ?string
    {
    	return $this->bitbucketId;
    }
    
    public function setBitbucketId(?string $bitbucketId): self
    {
    	$this->bitbucketId = $bitbucketId;
    	
    	return $this;
    }
    
    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }
    
    /**
     * @return Collection|Admin[]
     */
    public function getAdmins(): Collection
    {
    	return $this->admins;
    }
    
    public function addAdmin(User $admin): self
    {
    	if (!$this->admins->contains($admin)) {
    		$this->admins[] = $admin;
    	}
    	
    	return $this;
    }
    
    public function removeAdmin(User $admin): self
    {
    	if ($this->admins->contains($admin)) {
    		$this->admins->removeElement($admin);
    	}
    	
    	return $this;
    }

    public function getCss(): ?string
    {
        return $this->css;
    }

    public function setCss(?string $css): self
    {
        $this->css = $css;

        return $this;
    }

    public function getJavascript(): ?string
    {
        return $this->javascript;
    }

    public function setJavascript(?string $javascript): self
    {
        $this->javascript = $javascript;

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->setOrganisation($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->contains($team)) {
            $this->teams->removeElement($team);
            // set the owning side to null (unless already changed)
            if ($team->getOrganisation() === $this) {
                $team->setOrganisation(null);
            }
        }

        return $this;
    }

    public function getSubdomain(): ?bool
    {
        return $this->subdomain;
    }

    public function setSubdomain(?bool $subdomain): self
    {
        $this->subdomain = $subdomain;

        return $this;
    }

    public function getVetted(): ?bool
    {
        return $this->vetted;
    }

    public function setVetted(?bool $vetted): self
    {
        $this->vetted = $vetted;

        return $this;
    }

    /**
     * @return Collection|Component[]
     */
    public function getComponents(): Collection
    {
        return $this->components;
    }

    public function addComponent(Component $component): self
    {
        if (!$this->components->contains($component)) {
            $this->components[] = $component;
        }

        return $this;
    }

    public function removeComponent(Component $component): self
    {
        if ($this->components->contains($component)) {
            $this->components->removeElement($component);
        }

        return $this;
    }
}
