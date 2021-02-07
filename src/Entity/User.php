<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="users")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{

    public function __construct()
    {
        $this->rating = 0;
        $this->exp = 0;
        $this->last_activity = new DateTime('now');
        $this->premium = false;
        $this->created_at = new DateTime('now');
        $this->events = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->friends = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=14, nullable=true)
     */
    private $siret;

    /**
     * @ORM\Column(type="string", length=9, nullable=true)
     */
    private $siren;

    /**
     * @ORM\Column(type="integer", options={"default":0})
     */
    private $rating;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $exp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token_reset;

    /**
     * @ORM\Column(type="datetime",options={"default":"CURRENT_TIMESTAMP"})
     */
    private $last_activity;

    /**
     * @ORM\Column(type="boolean",options={"default":false})
     */
    private $premium;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $premium_end_date;

    /**
     * @ORM\Column(type="datetime",options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activation_token;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="participants")
     */
    private $events;
     /**
     * @ORM\ManyToMany(targetEntity=Conversation::class, mappedBy="participants")
     */
    private $conversations;

    /**
     * @ORM\OneToMany(targetEntity=Messages::class, mappedBy="user")
     */
    private $messages;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="friends")
     * @ORM\JoinTable(
     *     name="user_friends",
     *     joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@JoinColumn(name="friend_id", referencedColumnName="id")}
     * )
     */
    private $friends;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
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
        return (string)$this->email;
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

    /**
     * @param array $roles
     * @return $this
     */
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
        return (string)$this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
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
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return $this
     */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     * @return $this
     */
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSiret(): ?string
    {
        return $this->siret;
    }

    /**
     * @param string|null $siret
     * @return $this
     */
    public function setSiret(?string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSiren(): ?string
    {
        return $this->siren;
    }

    /**
     * @param string|null $siren
     * @return $this
     */
    public function setSiren(?string $siren): self
    {
        $this->siren = $siren;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getRating(): ?int
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     * @return $this
     */
    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     * @return $this
     */
    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     * @return $this
     */
    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getExp(): ?int
    {
        return $this->exp;
    }

    /**
     * @param int $exp
     * @return $this
     */
    public function setExp(int $exp): self
    {
        $this->exp = $exp;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTokenReset()
    {
        return $this->token_reset;
    }

    /**
     * @param $token_reset
     * @return $this
     */
    public function setTokenReset($token_reset): self
    {
        $this->token_reset = $token_reset;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getLastActivity(): ?DateTimeInterface
    {
        return $this->last_activity;
    }

    /**
     * @param DateTimeInterface $last_activity
     * @return $this
     */
    public function setLastActivity(DateTimeInterface $last_activity): self
    {
        $this->last_activity = $last_activity;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getPremium(): ?bool
    {
        return $this->premium;
    }

    /**
     * @param bool $premium
     * @return $this
     */
    public function setPremium(bool $premium): self
    {
        $this->premium = $premium;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getPremiumEndDate(): ?DateTimeInterface
    {
        return $this->premium_end_date;
    }

    /**
     * @param DateTimeInterface|null $premium_end_date
     * @return $this
     */
    public function setPremiumEndDate(?DateTimeInterface $premium_end_date): self
    {
        $this->premium_end_date = $premium_end_date;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->addParticipant($this);
        }
        return $this;
    }
 
    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            $event->removeParticipant($this);
        }

        return $this;
    }
  
    /*
     * @return Collection|Conversation[]
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
            $conversation->addParticipant($this);
        }

        return $this;
    }
  
    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            $conversation->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection|Messages[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Messages $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Messages $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getFriends(): Collection
    {
        return $this->friends;
    }

    public function addFriend(self $friend): self
    {
        if (!$this->friends->contains($friend)) {
            $this->friends[] = $friend;
        }

        return $this;
    }

    public function removeFriend(self $friend): self
    {
        $this->friends->removeElement($friend);

        return $this;
    }
}
