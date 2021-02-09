<?php

namespace App\Entity;

use App\Repository\EventPicturesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventPicturesRepository::class)
 */
class EventPictures
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url_image;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="eventPictures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_event;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrlImage(): ?string
    {
        return $this->url_image;
    }

    public function setUrlImage(string $url_image): self
    {
        $this->url_image = $url_image;

        return $this;
    }

    public function getIdEvent(): ?Event
    {
        return $this->id_event;
    }

    public function setIdEvent(?Event $id_event): self
    {
        $this->id_event = $id_event;

        return $this;
    }
}
