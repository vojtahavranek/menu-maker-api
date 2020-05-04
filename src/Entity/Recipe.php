<?php declare(strict_types=1);

namespace MenuMaker\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use MenuMaker\Entity\Traits\SluggableTrait;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;

/**
 * @Type
 * @ORM\Entity(repositoryClass="MenuMaker\Repository\RecipeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Recipe
{
    use SluggableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(length=128, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(length=256)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="MenuMaker\Entity\CookedRecipe", mappedBy="recipe", orphanRemoval=true)
     */
    private $cooked;

    public function __construct(string $name, ?string $description, ?string $image)
    {
        $this->cooked = new ArrayCollection();
        $this->name = $name;
        $this->description = $description;
        $this->image = $image;
    }

    /**
     * @Field()
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @Field()
     */
    public function getDescription(): string
    {
        return $this->description;
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
        return $this->image;
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

    public function cook(\DateTime $date = null): CookedRecipe
    {
        $cookedRecipe = new CookedRecipe($date);

        $this->cooked[] = $cookedRecipe;
        $cookedRecipe->setRecipe($this);

        return $cookedRecipe;
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
