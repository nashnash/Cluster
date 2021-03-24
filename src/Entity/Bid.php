<?php

namespace App\Entity;

use App\Repository\BidRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BidRepository::class)
 */
class Bid
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", options={"default":0})
     */
    private $capital;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="bids")
     * @ORM\JoinColumn(nullable=true)
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bids")
     * @ORM\JoinColumn(nullable=true)
     */
    private $professional;

    /**
     * @ORM\Column(type="integer")
     * @ORM\JoinColumn(nullable=true)
     */
    private $nbPromotion;


    public function __construct()
    {
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCapital(): ?float
    {
        return $this->capital;
    }

    public function setCapital(float $capital): self
    {
        $this->capital = $capital;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getProfessional(): ?User
    {
        return $this->professional;
    }

    public function setProfessional(?User $professional): self
    {
        $this->professional = $professional;

        return $this;
    }

    public function getNbPromotion(): ?int
    {
        return $this->nbPromotion;
    }

    public function setNbPromotion(int $nbPromotion): self
    {
        $this->nbPromotion = $nbPromotion;

        return $this;
    }
   
}
