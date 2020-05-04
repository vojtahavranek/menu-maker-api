<?php declare(strict_types=1);

namespace MenuMaker\Entity\Traits;

use Doctrine\ORM\Event\PreUpdateEventArgs;

trait SluggableTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $slug;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function generateSlug(string $string, string $prefix = ''): string
    {
        $slug = $this->slugify($string);

        if (!empty($prefix)) {
            $slug = '/' . $prefix . '/' . $slug /*. '-' . $id*/;
        }

        $this->slug = $slug;



        return $this->slug;
    }

    /** @ORM\PreUpdate() */
    public function updateSlug(PreUpdateEventArgs $event): void
    {
        if (!$event->hasChangedField('name')) {
            return;
        }

        $this->slug = $this->generateSlug($this->getName(), 'recipe');
    }

    /** @ORM\PrePersist() */
    public function createSlug(): void
    {
        $this->slug = $this->generateSlug($this->getName(), 'recipe');
    }

    private function slugify(string $text): string
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
