<?php

namespace App\Entity;

use App\Traits\Entity\DatesTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfileImageRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProfileImageRepository::class)]
#[Vich\Uploadable]
class ProfileImage
{
    // Traits calls
    use DatesTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Vich\UploadableField(mapping: 'user_image', fileNameProperty: 'file_name', size: 'imageSize')]
    private ?File $file = null;

    #[ORM\Column(nullable: true)]
    private ?string $file_name = null;

    #[ORM\Column(nullable: true)]
    private ?int $fileSize = null;

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(?string $file_name): void
    {
        $this->file_name = $file_name;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file = null): void
    {
        $this->file = $file;

        if (null !== $file) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function setFileSize(?int $fileSize): void
    {
        $this->fileSize = $fileSize;
    }

    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function __construct()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
        $this->setUpdatedAt(new \DateTimeImmutable());
    }

    public function __toString(): string
    {
        return $this->file_name;
    }
}
