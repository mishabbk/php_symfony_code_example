<?php

namespace App\Entity\Project\Report;

use App\Entity\Document\Document;
use App\Entity\Document\DocumentInterface;
use App\Entity\Person;
use App\Entity\Project\Project;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="project_report")
 * @ORM\Entity(repositoryClass="App\Repository\Project\Report\ReportRepository")
 */
class Report implements DocumentInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project\Project", inversedBy="reports")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotBlank
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="reports")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotBlank
     */
    private $person;

    /**
     * @ORM\Column(name="title", type="string")
     *
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(name="content", type="text")
     *
     * @Assert\NotBlank
     */
    private $content;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Document\Document", mappedBy="report", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $documents;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setReport($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->contains($document)) {
            $this->documents->removeElement($document);
            // set the owning side to null (unless already changed)
            if ($document->getReport() === $this) {
                $document->setReport(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function __toString(): ?string
    {
        return $this->title;
    }
}
