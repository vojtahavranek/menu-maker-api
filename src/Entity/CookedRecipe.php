<?php

namespace MenuMaker\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="MenuMaker\Repository\CookedRecipeRepository")
 */
class CookedRecipe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="MenuMaker\Entity\Recipe", inversedBy="cooked")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipe;

    /**
     * CookedRecipe constructor.
     *
     * @param $date
     */
    public function __construct(\DateTime $date = null)
    {
        if ($date === null) {
            $date = new \DateTime();
        }

        $this->date = $date;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setRecipe(?Recipe $recipe): void
    {
        $this->recipe = $recipe;
    }
}
