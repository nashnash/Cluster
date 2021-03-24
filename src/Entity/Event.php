<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 * @ORM\Table(name="event")
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThanOrEqual("today UTC",
     *     message="La date de début doit être supérieure ou égale à la date du jour!")
     * @Assert\Expression("this.getDateStart() <= this.getDateEnd()",
     *     message="La date de début doit être inférieure ou égale à la date de fin!"
     * )
     */
    private $date_start;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThanOrEqual("today UTC",
     *     message="La date de fin doit être supérieure ou égale à la date du jour!")
     * @Assert\Expression(
     *     "this.getDateEnd() >= this.getDateStart()",
     *     message="La date de fin doit être supérieure ou égale à la date de début!"
     * )
     */
    private $date_end;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull()
     * @Assert\NotBlank()
     */
    private $location;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(
     *      min = 10,
     *      max = 500,
     *      minMessage = "Votre description doit contenir au moins 10 caractères",
     *      maxMessage = "Votre description doit contenir au plus 500 caractères")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive(message="Il doit y avoir au moins un participant")
     */
    private $nb_participants;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated_at;
    
    /**
     * @ORM\ManyToMany(targetEntity=Restriction::class, inversedBy="events")
     */
    private $restrictions;

    /**
     * @ORM\Column(type="float")
     * @Assert\PositiveOrZero
     */
    private $price;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="change", field={"status"})
     */
    private $updatedStatus;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="events")
     * @ORM\JoinTable(
     *     name="event_user",
     *     joinColumns={@JoinColumn(name="event_id", referencedColumnName="id")},
     *     inverseJoinColumns={@JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    private $participants;

    /**
     * @ORM\OneToMany(targetEntity=Bid::class, mappedBy="event")
     */
    private $bids;

    /**
     * @ORM\Column(type="integer",options={"default":0},nullable=true)
     */
    private $numberOfVisits;

    /**
     * Event constructor.
     */
    public function __construct()
    {
        $this->restrictions = new ArrayCollection();
        $this->participants = new ArrayCollection();
        $this->bids = new ArrayCollection();
    }


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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->date_start;
    }

    /**
     * @param \DateTimeInterface $date_start
     * @return $this
     */
    public function setDateStart(\DateTimeInterface $date_start): self
    {
        $this->date_start = $date_start;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    /**
     * @param \DateTimeInterface $date_end
     * @return $this
     */
    public function setDateEnd(\DateTimeInterface $date_end): self
    {
        $this->date_end = $date_end;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string $location
     * @return $this
     */
    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNbParticipants(): ?int
    {
        return $this->nb_participants;
    }

    /**
     * @param int $nb_participants
     * @return $this
     */
    public function setNbParticipants(int $nb_participants): self
    {
        $this->nb_participants = $nb_participants;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    /*public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }*/

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    /*public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }*/

    /**
     * @return Collection|Restriction[]
     */
    public function getRestrictions(): Collection
    {
        return $this->restrictions;
    }

    /**
     * @param Restriction $restriction
     * @return $this
     */
    public function addRestriction(Restriction $restriction): self
    {
        if (!$this->restrictions->contains($restriction)) {
            $this->restrictions[] = $restriction;
        }

        return $this;
    }

    /**
     * @param Restriction $restriction
     * @return $this
     */
    public function removeRestriction(Restriction $restriction): self
    {
        $this->restrictions->removeElement($restriction);

        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getUpdatedStatus(): ?\DateTimeInterface
    {
        return $this->updatedStatus;
    }

    public function setUpdatedStatus(?\DateTimeInterface $updatedStatus): self
    {
        $this->updatedStatus = $updatedStatus;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    /**
     * @return Collection|Bid[]
     */
    public function getBids(): Collection
    {
        return $this->bids;
    }

    public function addBid(Bid $bid): self
    {
        if (!$this->bids->contains($bid)) {
            $this->bids[] = $bid;
            $bid->setEvent($this);
        }

        return $this;
    }

    public function removeBid(Bid $bid): self
    {
        if ($this->bids->removeElement($bid)) {
            // set the owning side to null (unless already changed)
            if ($bid->getEvent() === $this) {
                $bid->setEvent(null);
            }
        }

        return $this;
    }

    public function getNumberOfVisits(): ?int
    {
        return $this->numberOfVisits;
    }

    public function setNumberOfVisits(int $numberOfVisits): self
    {
        $this->numberOfVisits = $numberOfVisits;

        return $this;
    }
}