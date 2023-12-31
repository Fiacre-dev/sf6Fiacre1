<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use App\traits\TimeStampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
#[ORM\HasLifecycleCallbacks()]
class Personne
{
    use TimeStampTrait; 
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $age = null;

    // #[ORM\Column(length: 50, nullable: true)]
    // private ?string $job = null;

    #[ORM\OneToOne(inversedBy: 'personne', cascade: ['persist', 'remove'])]
    private ?Profile $profile = null;

    #[ORM\ManyToMany(targetEntity: Hobby::class)]
    private Collection $Hobbies;

    #[ORM\ManyToOne(inversedBy: 'personnes')]
    private ?Job $job = null;


    public function __construct()
    {
        $this->Hobbies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    // public function getJob(): ?string
    // {
    //     return $this->job;
    // }

    // public function setJob(?string $job): static
    // {
    //     $this->job = $job;

    //     return $this;
    // }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): static
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * @return Collection<int, Hobby>
     */
    public function getHobbies(): Collection
    {
        return $this->Hobbies;
    }

    public function addHobby(Hobby $hobby): static
    {
        if (!$this->Hobbies->contains($hobby)) {
            $this->Hobbies->add($hobby);
        }

        return $this;
    }

    public function removeHobby(Hobby $hobby): static
    {
        $this->Hobbies->removeElement($hobby);

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): static
    {
        $this->job = $job;

        return $this;
    }


}
