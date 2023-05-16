<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ChampionRepository;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ChampionRepository::class)]
#[ApiResource(paginationEnabled: false, operations:[new Get(), new GetCollection()], normalizationContext: ['groups' => ['champ:read']])]
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
    #[Groups('champ:read')]
    private ?string $lolId = null;

    #[ORM\Column(length: 255)]
    #[Groups('champ:read')]
    private ?string $lolKey = null;

    #[ORM\Column(length: 255)]
    #[Groups('champ:read')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups('champ:read')]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('champ:read')]
    private ?string $banner = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups('champ:read')]
    private ?string $lore = null;

    #[ORM\OneToMany(mappedBy: 'champion', targetEntity: Avis::class)]
    #[Groups('champ:read')]
    private Collection $avis;

    public function __construct()
    {
        $this->avis = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis->add($avi);
            $avi->setChampion($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getChampion() === $this) {
                $avi->setChampion(null);
            }
        }

        return $this;
    }
}
