<?php

namespace App\Service\Document;

use App\Entity\Document\Document;
use App\Entity\Document\DocumentTypeInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class DocumentService.
 */
class DocumentService
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getTypableEntities(): array
    {
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $values   = [];
        foreach ($metadata as $classMetadata) {
            if ($classMetadata->getReflectionClass()->implementsInterface(DocumentTypeInterface::class)) {
                $value          = call_user_func(
                    [
                        $classMetadata->getReflectionClass()->getName(),
                        'getEntityName',
                    ]
                );
                $values[$value] = $value;
            }
        }

        ksort($values);

        return $values;
    }

    public function getParent(Document $document)
    {
        if ($document->getCustomerOffer()) {
            return $document->getCustomerOffer();
        } elseif ($document->getCompanySas()) {
            return $document->getCompanySas();
        } elseif ($document->getRestriction()) {
            return $document->getRestriction();
        } elseif ($document->getMessage()) {
            return $document->getMessage();
        } elseif ($document->getBond()) {
            return $document->getBond();
        } elseif ($document->getReport()) {
            return $document->getReport();
        } elseif ($document->getInsurance()) {
            return $document->getInsurance();
        } elseif ($document->getInvoice()) {
            return $document->getInvoice();
        } elseif ($document->getPropertyDate()) {
            return $document->getPropertyDate();
        } elseif ($document->getPropertyTask()) {
            return $document->getPropertyTask();
        } elseif ($document->getLotSimilar()) {
            return $document->getLotSimilar();
        } elseif ($document->getLotImage()) {
            return $document->getLotImage();
        } elseif ($document->getLot()) {
            return $document->getLot();
        } elseif ($document->getProperty()) {
            return $document->getProperty();
        } elseif ($document->getProject()) {
            return $document->getProject();
        } elseif ($document->getPerson()) {
            return $document->getPerson();
        }

        return null;
    }
}
