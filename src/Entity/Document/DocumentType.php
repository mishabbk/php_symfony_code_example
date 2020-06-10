<?php

namespace App\Entity\Document;

use App\Entity\AbstractType;
use App\Entity\Company\Sas;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="document_type")
 * @ORM\Entity(repositoryClass="App\Repository\Document\DocumentTypeRepository")
 */
class DocumentType extends AbstractType
{
    /**
     * @ORM\OneToMany(targetEntity="TypeToEntity", mappedBy="documentType", cascade={"all"}, orphanRemoval=true)
     */
    private $typeToEntities;

    /**
     * @ORM\OneToMany(targetEntity="Document", mappedBy="type", cascade={"persist"})
     */
    private $documents;

    /**
     * DocumentType constructor.
     */
    public function __construct()
    {
        $this->typeToEntities = new ArrayCollection();
        $this->documents      = new ArrayCollection();
    }

    /**
     * @return TypeToEntity[]
     */
    public function getTypeToEntities(): Collection
    {
        return $this->typeToEntities;
    }

    /**
     * @return DocumentType
     */
    public function addTypeToEntity(TypeToEntity $typeToEntity): self
    {
        if (!$this->typeToEntities->contains($typeToEntity)) {
            $this->typeToEntities[] = $typeToEntity;
            $typeToEntity->setDocumentType($this);
        }

        return $this;
    }

    /**
     * @return DocumentType
     */
    public function removeTypeToEntity(TypeToEntity $typeToEntity): self
    {
        if ($this->typeToEntities->contains($typeToEntity)) {
            $this->typeToEntities->removeElement($typeToEntity);

            if ($typeToEntity->getDocumentType() === $this) {
                $typeToEntity->setDocumentType(null);
            }
        }

        return $this;
    }

    /**
     * @return Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setType($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->contains($document)) {
            $this->documents->removeElement($document);

            if ($document->getType() === $this) {
                $document->setType(null);
            }
        }

        return $this;
    }

    public function getDocumentsSas(Sas $sas): Collection
    {
        return $this->documents->filter(function (Document $document) use ($sas) {
            return $document->getCompanySas() && $document->getCompanySas()->getCompany()->getId() == $sas->getCompany()->getId();
        });
    }
}
