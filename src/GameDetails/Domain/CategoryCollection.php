<?php

namespace App\GameDetails\Domain;

use Webmozart\Assert\Assert;

class CategoryCollection implements \JsonSerializable
{
    /**
     * @var Category[]
     */
    private array $categories = [];

    /**
     * @param Category[] $categories
     */
    public function __construct(
        array $categories,
    ) {
        foreach ($categories as $category) {
            $this->add($category);
        }
    }

    public function add(Category $category): void
    {
        $this->categories[] = $category;
    }

    public static function fromArray(array $data): self
    {
        Assert::allIsArray($data);

        return new self(
            array_map(
                static fn(array $categoryData) => Category::fromArray($categoryData),
                $data,
            )
        );
    }

    public function toArray(): array
    {
        return $this->categories;
    }

    public function jsonSerialize(): array
    {
        return array_values(
            $this->toArray(),
        );
    }
}