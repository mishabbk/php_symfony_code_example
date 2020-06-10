<?php

namespace App\Controller\Dashboard\Bank;

use App\Component\HttpFoundation\AutocompleteResponse;
use App\Entity\Bank\BankAccount;
use App\Form\Bank\BankAccountSearchType;
use App\Form\Bank\BankAccountType;
use App\Handler\Bank\BankAccountHandler;
use App\Modele\Bank\FilterSearchBankAccount;
use App\Repository\Bank\BankAccountRepository;
use App\Service\EntityIdentifierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

/**
 * Class BankAccountController.
 *
 * @Route("/dashboard/bank-account")
 */
class BankAccountController extends AbstractController
{
    /**
     * @var BankAccountHandler
     */
    private $bankAccountHandler;

    /**
     * @var EntityIdentifierService
     */
    private $identifierService;

    /**
     * @var Breadcrumbs
     */
    private $breadcrumbs;

    /**
     * BankAccountController constructor.
     */
    public function __construct(
        BankAccountHandler $bankAccountHandler,
        EntityIdentifierService $identifierService,
        Breadcrumbs $breadcrumbs
    ) {
        $this->bankAccountHandler = $bankAccountHandler;
        $this->identifierService  = $identifierService;
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * @Route("/", name="dashboard_bank_account_index", methods={"GET"})
     */
    public function index(Request $request, BankAccountRepository $repository): Response
    {
        $this->addBreadcrumb();

        $search     = new FilterSearchBankAccount();
        $searchForm = $this->createForm(
            BankAccountSearchType::class,
            $search
        )->handleRequest($request);

        return $this->render(
            'dashboard/bank/bank_account/index.html.twig',
            [
                'bankAccounts' => $repository->search(
                    $search,
                    $request->query->getInt('page', 1)
                ),
                'searchForm' => $searchForm->createView(),
            ]
        );
    }

    /**
     * @Route("/create", name="dashboard_bank_account_create", methods={"GET", "POST"})
     */
    public function create(Request $request): Response
    {
        $this->addBreadcrumb()->addItem('breadcrumb.add');

        return $this->upsert(new BankAccount(), $request);
    }

    /**
     * @Route("/{id}/edit", name="dashboard_bank_account_edit", methods={"GET", "POST"}, requirements={"id": "\d+"})
     */
    public function edit(BankAccount $bankAccount, Request $request): Response
    {
        $this->addBreadcrumb()->addItem('breadcrumb.edit');

        return $this->upsert($bankAccount, $request);
    }

    private function upsert(BankAccount $bankAccount, Request $request): Response
    {
        $form = $this
            ->createForm(BankAccountType::class, $bankAccount)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bankAccount);
            $em->flush();

            return $this->redirectToRoute('dashboard_bank_account_index');
        }

        return $this->render(
            'dashboard/bank/bank_account/upsert.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/autocomplete", name="dashboard_bank_account_autocomplete", methods={"GET"})
     */
    public function autocomplete(Request $request)
    {
        return new AutocompleteResponse(
            $this->bankAccountHandler->findBankByUserQuery($request->query),
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
            ->addRouteItem('bank.account.breadcrumb', 'dashboard_bank_account_index');
    }
}
