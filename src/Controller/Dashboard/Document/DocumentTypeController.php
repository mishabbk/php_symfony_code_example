<?php

namespace App\Controller\Dashboard\Document;

use App\Entity\Document\DocumentType;
use App\Form\Document\DocumentTypeType;
use App\Handler\Document\DocumentHandler;
use App\Repository\Document\DocumentTypeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

/**
 * @Route("/dashboard/document-types")
 *
 * @IsGranted("DOCUMENT_TYPE_MANAGE")
 */
class DocumentTypeController extends AbstractController
{
    /** @var Breadcrumbs */
    private $breadcrumbs;

    /**
     * DocumentTypeController constructor.
     */
    public function __construct(Breadcrumbs $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * @Route("/", name="dashboard_document_type_index", methods={"GET"})
     */
    public function index(DocumentTypeRepository $repository, DocumentHandler $documentHandler): Response
    {
        $this->addBreadcrumb();

        $documentHandler->clear();

        return $this->render(
            'dashboard/documents/document_type/index.html.twig',
            [
                'types' => $repository->findAll(),
            ]
        );
    }

    /**
     * @Route("/create", name="dashboard_document_type_create", methods={"GET", "POST"})
     */
    public function create(Request $request): Response
    {
        $this->addBreadcrumb()->addItem('breadcrumb.add');

        return $this->upsert(
            new DocumentType(),
            $request
        );
    }

    /**
     * @Route("/{id}/edit", name="dashboard_document_type_edit", methods={"GET", "POST"}, requirements={"id": "\d+"})
     */
    public function edit(DocumentType $documentType, Request $request): Response
    {
        $this->addBreadcrumb()->addItem('breadcrumb.add');

        return $this->upsert(
            $documentType,
            $request
        );
    }

    private function upsert(DocumentType $documentType, Request $request): Response
    {
        $form = $this
            ->createForm(DocumentTypeType::class, $documentType)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($documentType);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dashboard_document_type_index');
        }

        return $this->render(
            'dashboard/documents/document_type/upsert.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Add breadcrumb.
     */
    private function addBreadcrumb(): Breadcrumbs
    {
        return $this->breadcrumbs
            ->addRouteItem('dashboard.breadcrumb', 'dashboard_dashboard')
            ->addRouteItem('document.type.breadcrumb', 'dashboard_document_type_index');
    }
}
