<?php

namespace App\Entity\Bank;

use App\Entity\Bank\Transfert\Transfert;
use App\Entity\Notary\Act\Act;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="bank_account")
 * @ORM\Entity(repositoryClass="App\Repository\Bank\BankAccountRepository")
 *
 * @UniqueEntity("iban")
 */
class BankAccount
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="iban", type="string", unique=true)
     * @Assert\NotBlank
     * @Assert\Iban
     */
    private $iban;

    /**
     * @ORM\Column(name="bic", type="string", nullable=true)
     * @Assert\Bic
     */
    private $bic;

    /**
     * @ORM\Column(name="account_holder", type="string", nullable=true)
     */
    private $accountHolder;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Bank\Bank")
     * @ORM\JoinColumn(name="bank_id", referencedColumnName="id", nullable=true)
     */
    private $bank;

    /**
     * @ORM\Column(name="opening_date", type="datetime", nullable=true)
     */
    private $openingDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bank\Transfert\Transfert", mappedBy="bankAccount")
     */
    private $transferts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Notary\Act\Act", mappedBy="bankAccount")
     */
    private $notaryAct;

    /**
     * BankAccount constructor.
     */
    public function __construct()
    {
        $this->transferts = new ArrayCollection();
        $this->notaryAct = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getIban().' '.$this->getBic();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(string $iban): self
    {
        $this->iban = $iban;

        return $this;
    }

    public function getBic(): ?string
    {
        return $this->bic;
    }

    public function setBic(?string $bic): self
    {
        $this->bic = $bic;

        return $this;
    }

    public function getAccountHolder(): ?string
    {
        return $this->accountHolder;
    }

    public function setAccountHolder(?string $accountHolder): self
    {
        $this->accountHolder = $accountHolder;

        return $this;
    }

    public function getBank(): ?Bank
    {
        return $this->bank;
    }

    /**
     * @return BankAccount
     */
    public function setBank(?Bank $bank): self
    {
        $this->bank = $bank;

        return $this;
    }

    public function getOpeningDate(): ?DateTime
    {
        return $this->openingDate;
    }

    /**
     * @return BankAccount
     */
    public function setOpeningDate(?DateTime $openingDate): self
    {
        $this->openingDate = $openingDate;

        return $this;
    }

    /**
     * @return Collection|Transfert[]
     */
    public function getTransferts(): Collection
    {
        return $this->transferts;
    }

    public function addTransfert(Transfert $transfert): self
    {
        if (!$this->transferts->contains($transfert)) {
            $this->transferts->add($transfert);
            $transfert->setBankAccount($this);
        }

        return $this;
    }

    public function removeTransfert(Transfert $transfert): self
    {
        if ($this->transferts->contains($transfert)) {
            $this->transferts->removeElement($transfert);
        }

        return $this;
    }

    /**
     * @return Collection|Act[]
     */
    public function getNotaryAct(): Collection
    {
        return $this->notaryAct;
    }

    public function addNotaryAct(Act $notaryAct): self
    {
        if (!$this->notaryAct->contains($notaryAct)) {
            $this->notaryAct[] = $notaryAct;
            $notaryAct->setBankAccount($this);
        }

        return $this;
    }

    public function removeNotaryAct(Act $notaryAct): self
    {
        if ($this->notaryAct->contains($notaryAct)) {
            $this->notaryAct->removeElement($notaryAct);
            // set the owning side to null (unless already changed)
            if ($notaryAct->getBankAccount() === $this) {
                $notaryAct->setBankAccount(null);
            }
        }

        return $this;
    }
}
