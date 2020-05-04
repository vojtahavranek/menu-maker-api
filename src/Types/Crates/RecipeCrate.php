<?php declare(strict_types=1);

namespace MenuMaker\Types\Crates;

class RecipeCrate
{
    private $name;
    private $description;
    private $image;

    public function __construct(string $name, ?string $description, ?string $image)
    {
        $this->name = $name;
        $this->description = $description;
        $this->image = $image;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
}