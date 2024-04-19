<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Traits\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\Entity\DatesTrait;
use App\Repository\ProfileImageRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProfileImageRepository::class)]
#[Vich\Uploadable]
class ProfileImage
{
    // Traits calls
    use IdTrait;
    use DatesTrait;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[Vich\UploadableField(mapping: 'user_image', fileNameProperty: 'file_name', size: 'fileSize')]
    #[Assert\Image(
        allowLandscape: true,
        allowPortrait: true,
        maxSize: '5M',
        maxSizeMessage: 'Le fichier est trop volumineux. La taille maximale autorisée est de {{ limit }} Mo.',
        mimeTypes: ['image/jpeg', 'image/webp', 'image/jpg'],
        mimeTypesMessage: 'Le format de l\'image n\'est pas valide. Les formats valides sont {{ types }}.'
    )]
    private ?File $file = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Sequentially([
        new Assert\Length(min: 5, max: 255, minMessage: 'Ce champ doit contenir au moins {{ limit }} caractères.', maxMessage: 'Ce champ est limité à {{ limit }} caractères.'),
        new Assert\Regex(pattern: '/^[a-zA-Z0-9.\s\(\)\-\'\"\p{L}]{5,255}+$/u', message: 'Ce champ contient des caractères non autorisés.')
    ])]
    private ?string $file_name = null;

    private ?int $fileSize = null;

    #[ORM\OneToOne(
        inversedBy: 'profileImage',
        cascade: ['persist', 'remove'],
        orphanRemoval: true,
        targetEntity: User::class
    )]
    private ?User $user = null;

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

    public function __construct()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
        $this->setUpdatedAt(new \DateTimeImmutable());
    }

    public function __toString(): string
    {
        return $this->file_name ?? '';
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setProfileImage(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getProfileImage() !== $this) {
            $user->setProfileImage($this);
        }

        $this->user = $user;

        return $this;
    }
}
