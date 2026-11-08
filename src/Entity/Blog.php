<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: BlogRepository::class)]
class Blog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    private static ?string $locale = 'pl';

    #[ORM\ManyToOne(inversedBy: 'blogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Topic $topic = null;


    #[ORM\Column(length: 255)]
    private ?string $title_pl = null;
    
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content_pl = null;


    #[ORM\Column(length: 255)]
    private ?string $title_en = null;
    
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content_en = null;


    #[ORM\Column(length: 255)]
    private ?string $title_de = null;
    
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content_de = null;


    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?bool $public = null;

    #[ORM\OneToMany(mappedBy: 'blog', targetEntity: Image::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setBlog($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            if ($image->getBlog() === $this) {
                $image->setBlog(null);
            }
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): static
    {
        $this->topic = $topic;

        return $this;
    }



    public function setTitlePl(string $title): static
    {
        $this->title_pl = $title;

        return $this;
    }

    public function getTitlePl(): ?string
    {
        return $this->title_pl;
    }

    public function setContentPl(string $content): static
    {
        $this->content_pl = $content;

        return $this;
    }

    public function getContentPl(): ?string
    {
        return $this->content_pl;
    }


    public function setTitleEn(string $title): static
    {
        $this->title_en = $title;

        return $this;
    }

    public function getTitleEn(): ?string
    {
        return $this->title_en;
    }

    public function getContentEn(): ?string
    {
        return $this->content_en;
    }

    public function setContentEn(string $content): static
    {
        $this->content_en = $content;

        return $this;
    }


    public function setTitleDe(string $title): static
    {
        $this->title_de = $title;

        return $this;
    }

    public function getTitleDe(): ?string
    {
        return $this->title_de;
    }

    public function getContentDe(): ?string
    {
        return $this->content_de;
    }

    public function setContentDe(string $content): static
    {
        $this->content_de = $content;

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

    public function isPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): static
    {
        $this->public = $public;

        return $this;
    }

    public function getTitle(string $locale = 'pl'): ?string
    {
        return match ($locale) {
            'pl' => $this->title_pl,
            'en' => $this->title_en,
            'de' => $this->title_de,
            default => $this->title_pl,
        };
    }

    public function getContent(string $locale = 'pl'): ?string
    {
        return match ($locale) {
            'pl' => $this->content_pl,
            'en' => $this->content_en,
            'de' => $this->content_de,
            default => $this->title_pl,
        };
    }
}
