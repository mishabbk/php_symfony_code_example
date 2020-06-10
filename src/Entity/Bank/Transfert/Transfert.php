<?php

namespace App\Entity\Bank\Transfert;

use App\Entity\Bank\BankAccount;
use App\Entity\Property\Task\Invoice;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="bank_transfert")
 * @ORM\Entity(repositoryClass="App\Repository\Bank\Transfert\TransfertRepository")
 */
class Transfert
{
    public const MOVEMENT_IN  = 'in';
    public const MOVEMENT_OUT = 'out';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bank\BankAccount", inversedBy="transferts")
     * @ORM\JoinColumn(name="bank_account_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotBlank
     */
    private $bankAccount;

    /**
     * @ORM\Column(name="movement", type="string")
     *
     * @Assert\NotBlank
     * @Assert\Choice({"in", "out"})
     */
    private $movement;

    /**
     * @ORM\Column(name="date", type="datetime")
     *
     * @Assert\NotBlank
     */
    private $date;

    /**
     * @ORM\Column(name="amount", type="decimal", precision=15, scale=2)
     *
     * @Assert\NotBlank
     */
    private $amount;

    /**
     * @ORM\Column(name="reference", type="string")
     *
     * @Assert\NotBlank
     */
    private $reference;

    /**
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Property\Task\Invoice", inversedBy="transferts")
     */
    private $invoices;

    public function __construct()
    {
        $this->movement = self::MOVEMENT_IN;
        $this->invoices = new ArrayCollection();
    }

    public function __toString(): ?string
    {
        return $this->getReference();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBankAccount(): ?BankAccount
    {
        return $this->bankAccount;
    }

    /**
     * @return Transfert
     */
    public function setBankAccount(?BankAccount $bankAccount): self
    {
        $this->bankAccount = $bankAccount;

        return $this;
    }

    public function getMovement(): ?string
    {
        return $this->movement;
    }

    /**
     * @return Transfert
     */
    public function setMovement(string $movement): self
    {
        $this->movement = $movement;

        return $this;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    /**
     * @return Transfert
     */
    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    /**
     * @return Transfert
     */
    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    /**
     * @return Transfert
     */
    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @return Transfert
     */
    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection|Invoice[]
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    /**
     * @return Transfert
     */
    public function addInvoice(Invoice $invoice): self
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices->add($invoice);
        }

        return $this;
    }

    /**
     * @return Transfert
     */
    public function removeInvoice(Invoice $invoice): self
    {
        if ($this->invoices->contains($invoice)) {
            $this->invoices->removeElement($invoice);
        }

        return $this;
    }
}
