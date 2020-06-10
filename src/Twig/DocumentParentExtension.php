<?php

namespace App\Twig;

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
use App\Service\Document\DocumentService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFilter;

class DocumentParentExtension extends AbstractExtension
{
    /** @var DocumentService */
    private $documentService;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /**
     * DocumentParentExtension constructor.
     */
    public function __construct(DocumentService $documentService, UrlGeneratorInterface $urlGenerator)
    {
        $this->documentService = $documentService;
        $this->urlGenerator = $urlGenerator;
    }

    public function getFilters(): array
    {
        return [

            new TwigFilter(
                'document_parent_link',
                [
                    $this,
                    'getDocumentParent',
                    [
                        'is_safe' => [
                            'html'
                        ]
                    ]
                ]
            ),
        ];
    }

    public function getDocumentParent($document)
    {
        $parent = $this->documentService->getParent($document);
        if ($parent instanceof Offer) {
            $path = $this->urlGenerator->generate(
                'dashboard_offer_edit',
                [
                    'id' => $parent->getId(),
                ]
            );
            $name = $parent->__toString();
        } elseif ($parent instanceof Sas) {
            $path = $this->urlGenerator->generate(
                'dashboard_company_sas_view_document',
                [
                    'id' => $parent->getCompany()->getId(),
                ]
            );
            $name = $parent->__toString();
        } elseif ($parent instanceof Restriction) {
            $path = $this->urlGenerator->generate(
                'dashboard_property_urbanism_restriction_edit',
                [
                    'id' => $parent->getId(),
                ]
            );
            $name = $parent->__toString();
        } elseif ($parent instanceof Message) {
            $path = $this->urlGenerator->generate(
                'dashboard_ticket_edit',
                [
                    'id' => $parent->getTicket()->getId(),
                ]
            );
            $name = $parent->getTicket()->__toString();
        } elseif ($parent instanceof Bond) {
            $path = $this->urlGenerator->generate(
                'dashboard_bond_view_document',
                [
                    'id' => $parent->getId(),
                ]
            );
            $name = $parent->__toString();
        } elseif ($parent instanceof Report) {
            $path = $this->urlGenerator->generate(
                'dashboard_project_report_edit',
                [
                    'id' => $parent->getId(),
                ]
            );
            $name = $parent->__toString();
        } elseif ($parent instanceof Insurance) {
            $path = $this->urlGenerator->generate(
                'dashboard_enterprise_insurance_edit',
                [
                    'id' => $parent->getId(),
                ]
            );
            $name = $parent->__toString();
        } elseif ($parent instanceof Invoice) {
            $path = $this->urlGenerator->generate(
                'dashboard_property_task_invoice_edit',
                [
                    'id' => $parent->getId(),
                ]
            );
            $name = $parent->__toString();
        } elseif ($parent instanceof Date) {
            $path = $this->urlGenerator->generate(
                'dashboard_property_date_edit',
                [
                    'id' => $parent->getId(),
                ]
            );
            $name = $parent->__toString();
        } elseif ($parent instanceof Task) {
            $path = $this->urlGenerator->generate(
                'dashboard_property_task_edit',
                [
                    'id' => $parent->getId(),
                ]
            );
            $name = $parent->__toString();
        } elseif ($parent instanceof LotSimilar) {
            $path = $this->urlGenerator->generate(
                'dashboard_property_lot_view_document',
                [
                    'id' => $parent->getLot()->getId(),
                ]
            );
            $name = $parent->getLot()->__toString();
        } elseif ($parent instanceof Lot) {
            $path = $this->urlGenerator->generate(
                'dashboard_property_lot_view_document',
                [
                    'id' => $parent->getId(),
                ]
            );
            $name = $parent->__toString();
        } elseif ($parent instanceof Property) {
            $path = $this->urlGenerator->generate(
                'dashboard_property_view_document',
                [
                    'id' => $parent->getId(),
                ]
            );
            $name = $parent->__toString();
        } elseif ($parent instanceof Project) {
            $path = $this->urlGenerator->generate(
                'dashboard_project_view_document',
                [
                    'id' => $parent->getId(),
                ]
            );
            $name = $parent->__toString();
        } elseif ($parent instanceof Person) {
            $path = $this->urlGenerator->generate(
                'dashboard_person_view_document',
                [
                    'id' => $parent->getId(),
                ]
            );
            $name = $parent->getFullName();
        }

        if (isset($path) && isset($name)){
            return new Markup('<a href="'.$path.'">'.$name.'</a>', 'UTF-8');
        }

        return null;
    }
}
