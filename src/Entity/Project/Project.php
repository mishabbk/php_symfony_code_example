<?php

namespace App\Entity\Project;

use App\Entity\Company\Sas;
use App\Entity\Document\DocumentTypeInterface;
use App\Entity\Employee\Employee;
use App\Entity\Meeting;
use App\Entity\Document\Document;
use App\Entity\Document\DocumentInterface;
use App\Entity\Project\Prospector\Prospector;
use App\Entity\Project\Report\Report;
use App\Entity\Property\Property;
use App\Entity\Ticket\Ticket;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="App\Repository\Project\ProjectRepository")
 * @UniqueEntity("name")
 */
class Project implements DocumentInterface, DocumentTypeInterface
{
    public const STATE_PENDING            = 'pending';
    public const STATE_FINISHED           = 'finished';
    public const STATE_ABANDONED          = 'abandoned';
    public const STATE_REJECTED           = 'rejected';
    public const STATE_WAITING_VALIDATION = 'waiting_validation';

    public const TYPE_ACQUISITION = 'acquisition';
    public const TYPE_DEVELOPMENT = 'development';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string")
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(name="type", type="string")
     * @Assert\NotBlank
     * @Assert\Choice({"acquisition", "development"})
     */
    private $type = self::TYPE_ACQUISITION;

    /**
     * @ORM\Column(name="investment_amount", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $investmentAmount;

    /**
     * @ORM\Column(name="sellout_price_estimated", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $selloutPriceEstimated;

    /**
     * @ORM\Column(name="sellout_price_real", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $selloutPriceReal;

    /**
     * @ORM\Column(name="margin_estimated", type="decimal", precision=8, scale=2, nullable=true)
     */
    private $marginEstimated;

    /**
     * @ORM\Column(name="margin_real", type="decimal", precision=8, scale=2, nullable=true)
     */
    private $marginReal;

    /**
     * @ORM\Column(name="date_end_estimated", type="datetime", nullable=true)
     */
    private $dateEndEstimated;

    /**
     * @ORM\Column(name="date_end_real", type="datetime", nullable=true)
     */
    private $dateEndReal;

    /**
     * @ORM\Column(name="risk_score", type="integer", nullable=true)
     */
    private $riskScore;

    /**
     * @ORM\Column(name="state", type="string")
     * @Assert\NotBlank
     * @Assert\Choice({"pending", "finished", "abandoned", "rejected", "waiting_validation"})
     */
    private $state = self::STATE_PENDING;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project\Prospector\Prospector", inversedBy="projects")
     * @ORM\JoinColumn(name="prospector_id", referencedColumnName="id", nullable=true)
     */
    private $prospector;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ticket\Ticket", mappedBy="project")
     */
    private $tickets;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company\Sas", cascade={"persist"}, inversedBy="projects")
     * @ORM\JoinColumn(name="company_sas_id", referencedColumnName="company_id", nullable=true)
     */
    private $companySas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Property\Property", mappedBy="project")
     */
    private $properties;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Meeting", mappedBy="project")
     */
    private $meetings;

    /**
     * @var Collection|Document[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Document\Document", mappedBy="project", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $documents;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Project\Report\Report", mappedBy="project")
     */
    private $reports;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Employee\Employee", inversedBy="projects")
     * @ORM\JoinTable(
     *     name="project_employees",
     *     joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="employee_id", referencedColumnName="person_id")}
     * )
     */
    private $employees;

    /**
     * Project constructor.
     */
    public function __construct()
    {
        $this->tickets    = new ArrayCollection();
        $this->properties = new ArrayCollection();
        $this->meetings   = new ArrayCollection();
        $this->documents  = new ArrayCollection();
        $this->reports    = new ArrayCollection();
        $this->employees  = new ArrayCollection();
    }

    public function __toString(): ?string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isAcquisition()
    {
        return self::TYPE_ACQUISITION === $this->getType();
    }

    public function getInvestmentAmount(): ?string
    {
        return $this->investmentAmount;
    }

    public function setInvestmentAmount(?string $investmentAmount): self
    {
        $this->investmentAmount = $investmentAmount;

        return $this;
    }

    public function getSelloutPriceEstimated(): ?string
    {
        return $this->selloutPriceEstimated;
    }

    public function setSelloutPriceEstimated(?string $selloutPriceEstimated): self
    {
        $this->selloutPriceEstimated = $selloutPriceEstimated;

        return $this;
    }

    public function getSelloutPriceReal(): ?string
    {
        return $this->selloutPriceReal;
    }

    public function setSelloutPriceReal(?string $selloutPriceReal): self
    {
        $this->selloutPriceReal = $selloutPriceReal;

        return $this;
    }

    public function getMarginEstimated(): ?string
    {
        return $this->marginEstimated;
    }

    public function setMarginEstimated(?string $marginEstimated): self
    {
        $this->marginEstimated = $marginEstimated;

        return $this;
    }

    public function getMarginReal(): ?string
    {
        return $this->marginReal;
    }

    public function setMarginReal(?string $marginReal): self
    {
        $this->marginReal = $marginReal;

        return $this;
    }

    public function getDateEndEstimated(): ?DateTime
    {
        return $this->dateEndEstimated;
    }

    public function setDateEndEstimated(?DateTime $dateEndEstimated): self
    {
        $this->dateEndEstimated = $dateEndEstimated;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDateEndReal(): ?DateTime
    {
        return $this->dateEndReal;
    }

    public function setDateEndReal(?DateTime $dateEndReal): self
    {
        $this->dateEndReal = $dateEndReal;

        return $this;
    }

    public function getRiskScore(): ?int
    {
        return $this->riskScore;
    }

    public function setRiskScore(?int $riskScore): self
    {
        $this->riskScore = $riskScore;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getProspector(): ?Prospector
    {
        return $this->prospector;
    }

    public function setProspector(?Prospector $prospector): self
    {
        $this->prospector = $prospector;

        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setProject($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->contains($ticket)) {
            $this->tickets->removeElement($ticket);
        }

        return $this;
    }

    /**
     * @return Sas|null
     */
    public function getCompanySas(): ?Sas
    {
        return $this->companySas;
    }

    /**
     * @param Sas|null $sas
     *
     * @return Project
     */
    public function setCompanySas(?Sas $sas): self
    {
        $this->companySas = $sas;

        return $this;
    }

    /**
     * @return Collection|Property[]
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function addProperty(Property $property): self
    {
        if (!$this->properties->contains($property)) {
            $this->properties[] = $property;
            $property->setProject($this);
        }

        return $this;
    }

    public function removeProperty(Property $property): self
    {
        if ($this->properties->contains($property)) {
            $this->properties->removeElement($property);
            // set the owning side to null (unless already changed)
            if ($property->getProject() === $this) {
                $property->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Meeting[]
     */
    public function getMeetings(): Collection
    {
        return $this->meetings;
    }

    public function addMeeting(Meeting $meeting): self
    {
        if (!$this->meetings->contains($meeting)) {
            $this->meetings[] = $meeting;
            $meeting->setProject($this);
        }

        return $this;
    }

    public function removeMeeting(Meeting $meeting): self
    {
        if ($this->meetings->contains($meeting)) {
            $this->meetings->removeElement($meeting);
            // set the owning side to null (unless already changed)
            if ($meeting->getProject() === $this) {
                $meeting->setProject(null);
            }
        }

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
            $document->setProject($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->contains($document)) {
            $this->documents->removeElement($document);
            // set the owning side to null (unless already changed)
            if ($document->getProject() === $this) {
                $document->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Report[]
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports[] = $report;
            $report->setProject($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->contains($report)) {
            $this->reports->removeElement($report);
            // set the owning side to null (unless already changed)
            if ($report->getProject() === $this) {
                $report->setProject(null);
            }
        }

        return $this;
    }

    public static function getEntityName(): string
    {
        return 'Project';
    }

    /**
     * @return Collection|Employee[]
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employee $employee): self
    {
        if (!$this->employees->contains($employee)) {
            $this->employees[] = $employee;
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): self
    {
        if ($this->employees->contains($employee)) {
            $this->employees->removeElement($employee);
        }

        return $this;
    }
}
