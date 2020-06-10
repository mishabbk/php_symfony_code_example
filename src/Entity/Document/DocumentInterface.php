<?php

namespace App\Entity\Document;

use Doctrine\Common\Collections\Collection;

/**
 * Class DocumentInterface.
 */
interface DocumentInterface
{
    public function getDocuments(): Collection;

    public function addDocument(Document $document);

    public function removeDocument(Document $document);
}
