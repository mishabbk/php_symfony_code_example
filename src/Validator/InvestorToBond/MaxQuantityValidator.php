<?php

namespace App\Validator\InvestorToBond;

use App\Entity\Investor\InvestorToBond;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class MaxQuantityBondValidator.
 */
class MaxQuantityValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * MaxQuantityValidator constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Checks if the passed value is not superior.
     *
     * @param InvestorToBond $investorToBond The value that should be validated
     * @param Constraint     $constraint     The constraint for the validation
     */
    public function validate($investorToBond, Constraint $constraint)
    {
        $bond = $investorToBond->getBond();

        if (bccomp($investorToBond->getQuantity(), $bond->getQuantity()) > 0) {
            $this->context
                ->buildViolation($constraint->message)
                ->atPath('quantity')
                ->addViolation();
        }

        $quantityLeft = $bond->getBondQuantityLeft();
        if (empty($this->entityManager->getUnitOfWork()->getOriginalEntityData($investorToBond))) {
            $quantityLeft -= $investorToBond->getQuantity();
        }

        if ($quantityLeft < 0) {
            $this->context
                ->buildViolation($constraint->messageMaxAttributed, ['{{value}}' => $bond->getBondQuantityLeft()])
                ->atPath('quantity')
                ->addViolation();
        }
    }
}
