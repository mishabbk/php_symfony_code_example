<?php

namespace App\Controller\Dashboard\Document;

use App\Entity\Document\Document;
use App\Form\Document\AjaxSelectDocumentTypeType;
use App\Form\Document\DocumentSearchType;
use App\Form\Document\EditDocumentNameType;
use App\Form\Document\ModalEditDocumentType;
use App\Modele\Document\FilterSearchDocument;
use App\Repository\Document\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\DownloadHandler;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

/**
 * @Route("/dashboard/document")
 */
class DocumentController extends AbstractController
{
    /** @var Breadcrumbs */
    private $breadcrumbs;

    public function __construct(Breadcrumbs $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * @Route("/", name="dashboard_document_index", methods={"GET"})
     */
    public function index(DocumentRepository $repository, Request $request): Response
    {
        $this->addBreadcrumb();

        $search = new FilterSearchDocument();
        $form   = $this->createForm(
            DocumentSearchType::class,
            $search
        )->handleRequest($request);

        return $this->render(
            'dashboard/documents/document/index.html.twig',
            [
                'documents'  => $repository->search(
                    $search,
                    $request->query->getInt('page', 1)
                ),
                'searchForm' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/download", name="dashboard_document_download", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function viewDownloadDocument(Document $document, DownloadHandler $downloadHandler)
    {
        return $downloadHandler->downloadObject($document, 'file', null, $document->getDownloadableName());
    }

    /**
     * @Route("/{id}/ajax/select-document-type", name="dashboard_document_ajax_select_document_type", methods={"GET", "POST"}, requirements={"id": "\d+"})
     */
    public function ajaxSelectDocumentType(Document $document, Request $request)
    {
        $form = $this
            ->createForm(AjaxSelectDocumentTypeType::class, $document)
            ->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $em->persist($document);
                $em->flush();

                return new JsonResponse(
                    ['success' => true],
                    Response::HTTP_OK
                );
            }

            $html = $this->renderView(
                'dashboard/project/project/view/document/_document_select_type_form.html.twig',
                [
                    'form' => $form->createView(),
                ]
            );

            return new Response($html, Response::HTTP_BAD_REQUEST);
        }

        return $this->render(
            'dashboard/project/project/view/document/_document_select_type_form.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/delete", name="dashboard_document_delete", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function deleteDocument(Document $document, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($document);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/{id}/modal/edit/document", name="dashboard_document_modal_edit_document", methods={"POST"}, requirements={"id": "\d+"})
     */
    public function modalEditDocument(Document $document, Request $request)
    {
        $form = $this
            ->createForm(ModalEditDocumentType::class, $document)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($document);
            $em->flush();

            return new JsonResponse(
                [
                    'value' => $document->getId(),
                    'name'  => (string) $document->getName(),
                    'type'  => $document->getType() ? $document->getType()->getId() : null,
                ],
                Response::HTTP_OK
            );
        }

        return $this->render(
            'dashboard/project/project/view/document/document_edit.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/name-edit", name="dashboard_document_name_edit", methods={"POST"}, requirements={"id": "\d+"})
     */
    public function editDocumentName(Document $document, Request $request): Response
    {
        $form = $this
            ->createForm(EditDocumentNameType::class, $document)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($document);
            $em->flush();
        }

        return $this->render(
            'dashboard/documents/document/list/item.html.twig',
            [
                'document' => $document,
            ]
        );
    }

    private function addBreadcrumb(): Breadcrumbs
    {
        return $this->breadcrumbs
            ->addRouteItem('dashboard.breadcrumb', 'dashboard_dashboard')
            ->addRouteItem('document.breadcrumb', 'dashboard_document_index');
    }
}
