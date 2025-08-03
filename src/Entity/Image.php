<?php
// src/Entity/Image.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Image
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $filePath = null;

    #[ORM\Column(type: 'integer')]
    private ?int $position = null;

    #[ORM\ManyToOne(targetEntity: Blog::class, inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Blog $blog = null;

    // getters & setters below
    public function getId(): ?int { return $this->id; }
    public function getFilePath(): ?string { return $this->filePath; }
    public function setFilePath(string $filePath): self { $this->filePath = $filePath; return $this; }

    public function getPosition(): ?int { return $this->position; }
    public function setPosition(int $position): self { $this->position = $position; return $this; }

    public function getBlog(): ?Blog { return $this->blog; }
    public function setBlog(?Blog $blog): self { $this->blog = $blog; return $this; }
}
