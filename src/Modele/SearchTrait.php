<?php

namespace App\Modele;

trait SearchTrait
{
    /** @var string|null */
    private $search;

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): self
    {
        $this->search = $search;

        return $this;
    }
}
