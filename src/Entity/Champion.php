<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ChampionRepository;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: ChampionRepository::class)]
#[ApiResource(paginationEnabled: false, operations:[new Get(), new GetCollection()])]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial', 'title' => 'partial', 'lolId' => 'exact'])]
class Champion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[SerializedName('dbId')]
    #[ApiProperty(identifier: false)]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[SerializedName('id')]
    #[ApiProperty(identifier: true)]
    private ?string $lolId = null;

    #[ORM\Column(length: 255)]
    private ?string $lolKey = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $banner = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $lore = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLolId(): ?string
    {
        return $this->lolId;
    }

    public function setLolId(string $lolId): self
    {
        $this->lolId = $lolId;

        return $this;
    }

    public function getLolKey(): ?string
    {
        return $this->lolKey;
    }

    public function setLolKey(string $lolKey): self
    {
        $this->lolKey = $lolKey;

        return $this;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBanner(): ?string
    {
        return $this->banner;
    }

    public function setBanner(?string $banner): self
    {
        $this->banner = $banner;

        return $this;
    }

    public function getLore(): ?string
    {
        return $this->lore;
    }

    public function setLore(?string $lore): self
    {
        $this->lore = $lore;

        return $this;
    }

    #[SerializedName('completeId')]
    public function getCompleteId(): string
    {
        return $this->lolId . '-' . $this->lolKey;
    }
}
