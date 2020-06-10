<?php

namespace App\Controller\Dashboard\Bank;

use App\Component\HttpFoundation\AutocompleteResponse;
use App\Entity\Bank\Bank;
use App\Form\Bank\BankType;
use App\Form\Bank\SearchType;
use App\Handler\Bank\BankHandler;
use App\Modele\Bank\FilterSearchBank;
use App\Repository\Bank\BankRepository;
use App\Service\EntityIdentifierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

/**
 * Class BankController.
 *
 * @Route("/dashboard/bank")
 */
class BankController extends AbstractController
{
    /**
     * @var BankHandler
     */
    private $bankHandler;

    /**
     * @var EntityIdentifierService
     */
    private $identifierService;

    /**
     * @var Breadcrumbs
     */
    private $breadcrumbs;

    /**
     * BankController constructor.
     */
    public function __construct(
        BankHandler $bankHandler,
        EntityIdentifierService $identifierService,
        Breadcrumbs $breadcrumbs
    ) {
        $this->bankHandler       = $bankHandler;
        $this->identifierService = $identifierService;
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * @Route("/", name="dashboard_bank_index", methods={"GET"})
     */
    public function index(Request $request, BankRepository $repository): Response
    {
        $this->addBreadcrumb();

        $search     = new FilterSearchBank();
        $searchForm = $this->createForm(
            SearchType::class,
            $search
        )->handleRequest($request);

        return $this->render(
            'dashboard/bank/bank/index.html.twig',
            [
                'banks' => $repository->search(
                    $search,
                    $request->query->getInt('page', 1)
                ),
                'searchForm' => $searchForm->createView(),
            ]
        );
    }

    /**
     * @Route("/create", name="dashboard_bank_create", methods={"GET", "POST"})
     */
    public function create(Request $request): Response
    {
        $this->addBreadcrumb()->addItem('breadcrumb.add');

        return $this->upsert(new Bank(), $request);
    }

    /**
     * @Route("/{id}/edit", name="dashboard_bank_edit", methods={"GET", "POST"}, requirements={"id": "\d+"})
     */
    public function edit(Bank $bank, Request $request): Response
    {
        $this->addBreadcrumb()->addItem('breadcrumb.edit');

        return $this->upsert($bank, $request);
    }

    private function upsert(Bank $bank, Request $request): Response
    {
        $form = $this
            ->createForm(BankType::class, $bank)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bank);
            $em->flush();

            return $this->redirectToRoute('dashboard_bank_index');
        }

        return $this->render(
            'dashboard/bank/bank/upsert.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/autocomplete", name="bank_autocomplete", methods={"GET"})
     */
    public function autocomplete(Request $request)
    {
        return new AutocompleteResponse(
            $this->bankHandler->findBankByUserQuery($request->query),
            $this->identifierService
        );
    }

    /**
     * Add breadcrumb.
     */
    private function addBreadcrumb(): Breadcrumbs
    {
        return $this->breadcrumbs
            ->addRouteItem('dashboard.breadcrumb', 'dashboard_dashboard')
            ->addRouteItem('bank.breadcrumb', 'dashboard_bank_index');
    }
}
