<?php

namespace App\Entity\Document;

use App\Entity\Bond\Bond;
use App\Entity\Company\Sas;
use App\Entity\Customer\Offer;
use App\Entity\Enterprise\Insurance\Insurance;
use App\Entity\Person;
use App\Entity\Project\Project;
use App\Entity\Project\Report\Report;
use App\Entity\Property\Date\Date;
use App\Entity\Property\Lot;
use App\Entity\Property\LotSimilar;
use App\Entity\Property\Property;
use App\Entity\Property\Task\Invoice;
use App\Entity\Property\Task\Task;
use App\Entity\Property\Urbanism\Restriction;
use App\Entity\Ticket\Message;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table(name="document")
 * @ORM\Entity(repositoryClass="App\Repository\Document\DocumentRepository")
 * @Vich\Uploadable
 */
class Document
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="DocumentType", cascade={"persist"}, inversedBy="documents")
     * @ORM\JoinColumn(name="document_type_id", referencedColumnName="id", nullable=true)
     */
    private $type;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="document", fileNameProperty="fileName", size="size", mimeType="mimeType", originalName="originalName")
     *
     * @var File|null
     */
    private $file;

    /**
     * @ORM\Column(name="file_name", type="string")
     *
     * @var string|null
     */
    private $fileName;

    /**
     * @ORM\Column(name="size", type="integer")
     *
     * @var int|null
     */
    private $size;

    /**
     * @ORM\Column(name="mime_type", type="string")
     *
     * @var string|null
     */
    private $mimeType;

    /**
     * @ORM\Column(name="original_name", type="string")
     *
     * @var string|null
     */
    private $originalName;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @var DateTimeInterface|null
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @var DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company\Sas", inversedBy="documents")
     * @ORM\JoinColumn(name="company_sas_id", referencedColumnName="company_id", nullable=true)
     */
    private $companySas;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Property\Date\Date", inversedBy="documents")
     * @ORM\JoinColumn(name="property_date_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $propertyDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="documents")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $person;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Property\Urbanism\Restriction", inversedBy="documents")
     * @ORM\JoinColumn(name="restriction_id", referencedColumnName="id", nullable=true)
     */
    private $restriction;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project\Project", inversedBy="documents")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=true)
     */
    private $project;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ticket\Message", inversedBy="documents")
     * @ORM\JoinColumn(name="message_id", referencedColumnName="id", nullable=true)
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bond\Bond", inversedBy="documents")
     * @ORM\JoinColumn(name="bond_id", referencedColumnName="id", nullable=true)
     */
    private $bond;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project\Report\Report", inversedBy="documents")
     */
    private $report;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Enterprise\Insurance\Insurance", inversedBy="documents")
     * @ORM\JoinColumn(name="insurance_id", referencedColumnName="id", nullable=true)
     */
    private $insurance;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Property\Property", inversedBy="documents")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id", nullable=true)
     */
    private $property;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Property\Lot", inversedBy="documents")
     * @ORM\JoinColumn(name="property_lot_id", referencedColumnName="id", nullable=true)
     */
    private $lot;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Property\LotSimilar", inversedBy="documents")
     * @ORM\JoinColumn(name="property_lot_similar_id", referencedColumnName="id", nullable=true)
     */
    private $lotSimilar;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Property\Lot", inversedBy="images")
     * @ORM\JoinColumn(name="property_lot_image_id", referencedColumnName="id", nullable=true)
     */
    private $lotImage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Property\Task\Invoice", inversedBy="documents")
     * @ORM\JoinColumn(name="property_task_invoice_id", referencedColumnName="id", nullable=true)
     */
    private $invoice;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Property\Task\Task", inversedBy="documents")
     * @ORM\JoinColumn(name="property_task_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $propertyTask;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer\Offer", inversedBy="documents")
     * @ORM\JoinColumn(name="customer_offer_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    private $customerOffer;

    /**
     * Document constructor.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function __toString()
    {
        return $this->getName() ?: $this->getOriginalName() ?: $this->getFileName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?DocumentType
    {
        return $this->type;
    }

    public function setType(?DocumentType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function setFile(?File $file = null): self
    {
        $this->file = $file;

        if (null !== $file) {
            $this->updatedAt = new DateTimeImmutable();
        }

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(?string $originalName): self
    {
        $this->originalName = $originalName;

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

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCompanySas(): ?Sas
    {
        return $this->companySas;
    }

    public function setCompanySas(?Sas $companySas): self
    {
        $this->companySas = $companySas;

        return $this;
    }

    public function getPropertyDate(): ?Date
    {
        return $this->propertyDate;
    }

    public function setPropertyDate(?Date $propertyDate): self
    {
        $this->propertyDate = $propertyDate;
        if ($propertyDate && null === $this->getProject() && $propertyDate->getProperty()) {
            $this->setProject(
                $propertyDate->getProperty()->getProject()
            );
        }

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

    public function getRestriction(): ?Restriction
    {
        return $this->restriction;
    }

    public function setRestriction(?Restriction $restriction): self
    {
        $this->restriction = $restriction;
        if ($restriction && null === $this->getProject() && $restriction->getProperty()) {
            $this->setProject(
                $restriction->getProperty()->getProject()
            );
        }

        return $this;
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

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(?Message $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getBond(): ?Bond
    {
        return $this->bond;
    }

    public function setBond(?Bond $bond): self
    {
        $this->bond = $bond;
        if ($bond && null === $this->getProject() && $bond->getProperty()) {
            $this->setProject(
                $bond->getProperty()->getProject()
            );
        }

        return $this;
    }

    public function getReport(): ?Report
    {
        return $this->report;
    }

    public function setReport(?Report $report): self
    {
        $this->report = $report;
        if ($report && null === $this->getProject()) {
            $this->setProject(
                $report->getProject()
            );
        }

        return $this;
    }

    public function getInsurance(): ?Insurance
    {
        return $this->insurance;
    }

    public function setInsurance(?Insurance $insurance): self
    {
        $this->insurance = $insurance;

        return $this;
    }

    public function getDownloadableName(): ?string
    {
        $name = $this->__toString();
        if (!preg_match('/\.[0-9a-z]{3,4}\z/iUs', $name)) {
            $name .= $this->getExtension();
        }

        return $name;
    }

    public function getExtension(): ?string
    {
        return preg_replace('/\A.+(\.[0-9a-z]{3,4})\z/iUs', '$1', $this->getFileName());
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): self
    {
        $this->property = $property;

        return $this;
    }

    public function getLot(): ?Lot
    {
        return $this->lot;
    }

    public function setLot(?Lot $lot): self
    {
        $this->lot = $lot;

        return $this;
    }

    public function getLotSimilar(): ?LotSimilar
    {
        return $this->lotSimilar;
    }

    public function setLotSimilar(?LotSimilar $lotSimilar): self
    {
        $this->lotSimilar = $lotSimilar;

        return $this;
    }

    public function getLotImage(): ?Lot
    {
        return $this->lotImage;
    }

    public function setLotImage(?Lot $lot): self
    {
        $this->lotImage = $lot;

        return $this;
    }

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getPropertyTask(): ?Task
    {
        return $this->propertyTask;
    }

    public function setPropertyTask(?Task $propertyTask)
    {
        $this->propertyTask = $propertyTask;

        return $this;
    }


    public function getCustomerOffer(): ?Offer
    {
        return $this->customerOffer;
    }

    public function setCustomerOffer(?Offer $customerOffer): self
    {
        $this->customerOffer = $customerOffer;

        return $this;
    }
}
