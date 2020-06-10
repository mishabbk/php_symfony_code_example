<?php

namespace App\Entity\Document;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="document_type_to_entity")
 * @ORM\Entity(repositoryClass="App\Repository\Document\TypeToEntityRepository")
 */
class TypeToEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="entity", type="string")
     */
    private $entity;

    /**
     * @ORM\ManyToOne(targetEntity="DocumentType", inversedBy="typeToEntities")
     * @ORM\JoinColumn(name="document_type_id", referencedColumnName="id")
     */
    private $documentType;

    /**
     * @ORM\Column(name="required", type="boolean")
     */
    private $required = false;

    /**
     * @ORM\Column(name="requirement", type="string", nullable=true)
     */
    private $requirement;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    public function __toString(): ?string
    {
        return $this->getEntity();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntity(): ?string
    {
        return $this->entity;
    }

    public function setEntity(string $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    public function getRequired(): ?bool
    {
        return $this->required;
    }

    public function setRequired(bool $required): self
    {
        $this->required = $required;

        return $this;
    }

    public function getDocumentType(): ?DocumentType
    {
        return $this->documentType;
    }

    public function setDocumentType(?DocumentType $documentType): self
    {
        $this->documentType = $documentType;

        return $this;
    }

    public function getRequirement(): ?string
    {
        return $this->requirement;
    }

    public function setRequirement(?string $requirement)
    {
        $this->requirement = $requirement;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description)
    {
        $this->description = $description;

        return $this;
    }
}
