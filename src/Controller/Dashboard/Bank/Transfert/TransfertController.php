<?php

namespace App\Controller\Dashboard\Bank\Transfert;

use App\Entity\Bank\Transfert\Transfert;
use App\Form\Bank\Transfert\BankTransfertSearchType;
use App\Form\Bank\Transfert\TransfertType;
use App\Modele\Bank\FilterSearchBankTransfert;
use App\Repository\Bank\Transfert\TransfertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

/**
 * Class TransfertController.
 *
 * @Route("/dashboard/bank-transfert")
 */
class TransfertController extends AbstractController
{
    /**
     * @var Breadcrumbs
     */
    private $breadcrumbs;

    /**
     * TransfertController constructor.
     */
    public function __construct(Breadcrumbs $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * @Route("/", name="dashboard_bank_transfert_index", methods={"GET"})
     */
    public function index(Request $request, TransfertRepository $repository): Response
    {
        $this->addBreadcrumb();

        $search     = new FilterSearchBankTransfert();
        $searchForm = $this->createForm(BankTransfertSearchType::class, $search)->handleRequest($request);

        return $this->render(
            'dashboard/bank/transfert/transfert/index.html.twig',
            [
                'transferts' => $repository->search(
                    $search,
                    $request->query->getInt('page', 1)
                ),
                'searchForm' => $searchForm->createView(),
            ]
        );
    }

    /**
     * @Route("/create", name="dashboard_bank_transfert_create", methods={"GET", "POST"})
     */
    public function create(Request $request): Response
    {
        $this->addBreadcrumb()->addItem('breadcrumb.add');

        return $this->upsert(new Transfert(), $request);
    }

    /**
     * @Route("/{id}/edit", name="dashboard_bank_transfert_edit", methods={"GET", "POST"}, requirements={"id": "\d+"})
     */
    public function edit(Transfert $transfert, Request $request): Response
    {
        $this->addBreadcrumb()->addItem('breadcrumb.edit');

        return $this->upsert($transfert, $request);
    }

    private function upsert(Transfert $transfert, Request $request): Response
    {
        $form = $this
            ->createForm(TransfertType::class, $transfert)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($transfert);
            $em->flush();

            return $this->redirectToRoute('dashboard_bank_transfert_index');
        }

        return $this->render(
            'dashboard/bank/transfert/transfert/upsert.html.twig',
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
            ->addRouteItem('bank.transfert.breadcrumb', 'dashboard_bank_transfert_index');
    }
}
