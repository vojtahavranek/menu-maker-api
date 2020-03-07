<?php declare(strict_types=1);

namespace MenuMaker\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type()
 * @ORM\Entity(repositoryClass="MenuMaker\Repository\RecipeRepository")
 */
class Recipe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="MenuMaker\Entity\CookedRecipe", mappedBy="recipe", orphanRemoval=true)
     */
    private $cooked;

    public function __construct()
    {
        $this->cooked = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @Field()
     */
    public function getName(): string
    {
        return 'test name';
    }

    /**
     * @Field()
     */
    public function getDescription(): string
    {
        return 'test long long long description';
    }

    /**
     * @Field()
     */
    public function getRating(): float
    {
        return 4.77;
    }

    /**
     * @Field()
     */
    public function getImage(): string
    {
        return 'http://double-kill.vojtechhavranek.cz/images/chilli-chicken.jpg';
    }

    /**
     * @Field()
     */
    public function getAuthor(): string
    {
        return 'Vojtěch Havránek';
    }

    /**
     * @return Collection|CookedRecipe[]
     */
    public function getCooked(): Collection
    {
        return $this->cooked;
    }

    public function addCooked(CookedRecipe $cooked): self
    {
        if (!$this->cooked->contains($cooked)) {
            $this->cooked[] = $cooked;
            $cooked->setRecipe($this);
        }

        return $this;
    }

    public function removeCooked(CookedRecipe $cooked): self
    {
        if ($this->cooked->contains($cooked)) {
            $this->cooked->removeElement($cooked);
            // set the owning side to null (unless already changed)
            if ($cooked->getRecipe() === $this) {
                $cooked->setRecipe(null);
            }
        }

        return $this;
    }
}
