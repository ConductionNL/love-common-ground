<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="socialuser")
 * 
 * @ORM\Entity(repositoryClass="App\Repository\ExampleEntityRepository")
 *
 */
class User implements UserInterface
{
    /**
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
	 * @ORM\Column(type="string", length=255, nullable=true)
	 * @Assert\Length(
     *     max = 255
     * )
     */
	private $username;
	
	/**
     * @Groups({"read","write"})
	 * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     max = 255
     * )
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
	private $avatar;

    /**
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Length(
     *     max = 255
     * )
     * @Assert\NotBlank
     */
    private $email;

    /**
     * @Groups({"read","write"})
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @Groups({"read","write"})
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @Groups({"read","write"})
     * @ORM\ManyToMany(targetEntity="App\Entity\Organisation", mappedBy="users")
     * @MaxDepth(1)
     */
    private $organisations;

    /**
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(
     *     max = 255
     * )
     */
    private $githubId;
    
    /**
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length (
     *    max = 255
     * )
     */
    private $githubToken;

    /**
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length (
     *    max = 255
     * )
     */
    private $gitlabId;
    
    /**
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length (
     *    max = 255
     * )
     */
    private $gitlabToken;

    /**
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length (
     *    max = 255
     * )
     */
    private $bitbucketId;

    /**
     * @Groups({"read","write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length (
     *    max = 255
     * )
     */
    private $bitbucketToken;

    /**
     * @Groups({"read","write"})
     * @ORM\ManyToMany(targetEntity="App\Entity\Team", mappedBy="members")
     * @MaxDepth(1)
     */
    private $teams;

    public function __construct()
    {
        $this->organisations = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $organisation->addUser($this);
        }

        return $this;
    }

    public function removeOrganisation(Organisation $organisation): self
    {
        if ($this->organisations->contains($organisation)) {
            $this->organisations->removeElement($organisation);
            $organisation->removeUser($this);
        }

        return $this;
    }

    public function getGithubId(): ?string
    {
        return $this->githubId;
    }

    public function setGithubId(?string $githubId): self
    {
        $this->githubId = $githubId;

        return $this;
    }

    public function getGitlabId(): ?string
    {
        return $this->gitlabId;
    }

    public function setGitlabId(?string $gitlabId): self
    {
        $this->gitlabId = $gitlabId;

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

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
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

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getGithubToken(): ?string
    {
        return $this->githubToken;
    }

    public function setGithubToken(?string $githubToken): self
    {
        $this->githubToken = $githubToken;

        return $this;
    }

    public function getGitlabToken(): ?string
    {
        return $this->gitlabToken;
    }

    public function setGitlabToken(?string $gitlabToken): self
    {
        $this->gitlabToken = $gitlabToken;

        return $this;
    }

    public function getBitbucketToken(): ?string
    {
        return $this->bitbucketToken;
    }

    public function setBitbucketToken(?string $bitbucketToken): self
    {
        $this->bitbucketToken = $bitbucketToken;

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
            $team->addMember($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->contains($team)) {
            $this->teams->removeElement($team);
            $team->removeMember($this);
        }

        return $this;
    }
}
