<?php

namespace App\Modele;

use App\Entity\Project\Project;

trait SearchProjectTrait
{
    /**
     * @var Project|null
     */
    private $project;

    /**
     * Get project.
     */
    public function getProject(): ?Project
    {
        return $this->project;
    }

    /**
     * Set project.
     */
    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }
}
