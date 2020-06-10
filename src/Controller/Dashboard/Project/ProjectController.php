<?php

namespace App\Controller\Dashboard\Project;

use App\Component\HttpFoundation\AutocompleteResponse;
use App\Entity\Company\Sas;
use App\Entity\Project\Project;
use App\Form\Document\DocumentSearchType;
use App\Form\Project\ProjectDocumentUploadType;
use App\Form\Project\ProjectType;
use App\Handler\Project\ProjectHandler;
use App\Modele\Agency\FilterSearchMandate;
use App\Modele\Bank\FilterSearchLoan;
use App\Modele\Bond\FilterSearchBond;
use App\Modele\Customer\FilterSearchOffer;
use App\Modele\Document\FilterSearchDocument;
use App\Modele\Enterprise\FilterSearchEnterprise;
use App\Modele\Notary\FilterSearchAct;
use App\Modele\Project\FilterSearchProject;
use App\Modele\Project\Report\FilterSearchReport;
use App\Modele\Property\FilterSearchDate;
use App\Modele\Property\FilterSearchInvoice;
use App\Modele\Property\FilterSearchLot;
use App\Modele\Property\FilterSearchTask;
use App\Modele\Property\FilterSearchUrbanismRestriction;
use App\Repository\Agency\MandateRepository;
use App\Repository\Bank\Loan\LoanRepository;
use App\Repository\Bond\BondRepository;
use App\Repository\Company\SasRepository;
use App\Repository\Customer\OfferRepository;
use App\Repository\Document\DocumentRepository;
use App\Repository\Enterprise\EnterpriseRepository;
use App\Repository\Notary\Act\ActRepository;
use App\Repository\Project\ProjectRepository;
use App\Repository\Project\Report\ReportRepository;
use App\Repository\Property\Date\DateRepository;
use App\Repository\Property\LotRepository;
use App\Repository\Property\Task\InvoiceRepository;
use App\Repository\Property\Task\TaskRepository;
use App\Repository\Property\Urbanism\RestrictionRepository;
use App\Service\EntityIdentifierService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\JsonException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

/**
 * Class ProjectController.
 *
 * @Route("/dashboard/project")
 */
class ProjectController extends AbstractController
{
    /**
     * @var Breadcrumbs
     */
    private $breadcrumbs;

    /**
     * @var SasRepository
     */
    private $sasRepository;

    /**
     * @var ProjectHandler
     */
    private $projectHandler;

    /**
     * @var EntityIdentifierService
     */
    private $identifierService;

    /**
     * ProjectController constructor.
     */
    public function __construct(
        Breadcrumbs $breadcrumbs,
        SasRepository $sasRepository,
        ProjectHandler $projectHandler,
        EntityIdentifierService $identifierService
    ) {
        $this->breadcrumbs       = $breadcrumbs;
        $this->sasRepository     = $sasRepository;
        $this->projectHandler    = $projectHandler;
        $this->identifierService = $identifierService;
    }

    /**
     * @Route("/", name="dashboard_project_index", methods={"GET"})
     *
     * @IsGranted("PROJECT_VIEW")
     */
    public function index(Request $request, ProjectRepository $repository): Response
    {
        $this->addBreadcrumb();

        return $this->render(
            'dashboard/project/project/index.html.twig',
            [
                'projects' => $repository->search(new FilterSearchProject(), $request->query->getInt('page', 1)),
            ]
        );
    }

    /**
     * @Route("/create", name="dashboard_project_create", methods={"GET", "POST"})
     *
     * @IsGranted("PROJECT_VIEW")
     */
    public function create(Request $request): Response
    {
        $this->addBreadcrumb()->addRouteItem('breadcrumb.add', 'dashboard_project_create');

        return $this->upsert(new Project(), $request);
    }

    /**
     * @Route("/{id}/edit", name="dashboard_project_edit", methods={"GET", "POST"}, requirements={"id": "\d+"})
     *
     * @IsGranted("PROJECT_VIEW", subject="project")
     */
    public function edit(Project $project, Request $request): Response
    {
        $this->addBreadcrumb($project)->addItem('breadcrumb.edit');

        return $this->upsert($project, $request);
    }

    /**
     * @Route("/{id}/view/date", name="dashboard_project_view_date", methods={"GET"}, requirements={"id": "\d+"})
     *
     * @IsGranted("PROJECT_VIEW", subject="project")
     */
    public function viewDate(Project $project, Request $request, DateRepository $dateRepository): Response
    {
        $this->addBreadcrumb($project)->addItem('property.date.breadcrumb');

        $filter = (new FilterSearchDate())
            ->setProject($project);

        return $this->render(
            'dashboard/project/project/view/date.html.twig',
            [
                'project' => $project,
                'dates'   => $dateRepository->search($filter, $request->query->getInt('page', 1)),
            ]
        );
    }

    /**
     * @Route("/{id}/view/task", name="dashboard_project_view_task", methods={"GET"}, requirements={"id": "\d+"})
     *
     * @IsGranted("PROJECT_VIEW", subject="project")
     */
    public function viewTask(Project $project, Request $request, TaskRepository $taskRepository): Response
    {
        $this->addBreadcrumb($project)->addItem('property.task.breadcrumb');

        $filter = (new FilterSearchTask())
            ->setProject($project);

        return $this->render(
            'dashboard/project/project/view/task.html.twig',
            [
                'project' => $project,
                'tasks'   => $taskRepository->search($filter, $request->query->getInt('page', 1)),
            ]
        );
    }

    /**
     * @Route("/{id}/view/urbanism", name="dashboard_project_view_urbanism", methods={"GET"}, requirements={"id": "\d+"})
     *
     * @IsGranted("PROJECT_VIEW", subject="project")
     */
    public function viewUrbanism(
        Project $project,
        Request $request,
        RestrictionRepository $restrictionRepository
    ): Response {
        $this->addBreadcrumb($project)->addItem('property.urbanism.breadcrumb');

        $filter = (new FilterSearchUrbanismRestriction())
            ->setProject($project);

        return $this->render(
            'dashboard/project/project/view/urbanism.html.twig',
            [
                'project'   => $project,
                'urbanisms' => $restrictionRepository->search($filter, $request->query->getInt('page', 1)),
            ]
        );
    }

    /**
     * @Route("/{id}/view/document", name="dashboard_project_view_document", methods={"GET", "POST"}, requirements={"id": "\d+"})
     *
     * @IsGranted("PROJECT_VIEW", subject="project")
     */
    public function viewDocument(Project $project, Request $request, DocumentRepository $documentRepository): Response
    {
        $this->addBreadcrumb($project)->addItem('document.breadcrumb');

        $filter = (new FilterSearchDocument())
            ->setProject($project);

        $searchForm = $this->createForm(
            DocumentSearchType::class,
            $filter
        )->handleRequest($request);

        $documentUploadForm = $this
            ->createForm(
            ProjectDocumentUploadType::class,
            $project
            )
            ->handleRequest($request);

        if ($documentUploadForm->isSubmitted() && $documentUploadForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('dashboard_project_view_document', [
                'id' => $project->getId(),
            ]);
        }

        return $this->render(
            'dashboard/project/project/view/document/document.html.twig',
            [
                'project'            => $project,
                'documents'          => $documentRepository->search($filter, $request->query->getInt('page', 1)),
                'searchForm'         => $searchForm->createView(),
                'documentUploadForm' => $documentUploadForm->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/view/invoice", name="dashboard_project_view_invoice", methods={"GET"}, requirements={"id": "\d+"})
     *
     * @IsGranted("PROJECT_VIEW", subject="project")
     */
    public function viewInvoice(Project $project, Request $request, InvoiceRepository $invoiceRepository): Response
    {
        $this->addBreadcrumb($project)->addItem('property.task.invoice.breadcrumb');

        $filter = (new FilterSearchInvoice())
            ->setProject($project);

        return $this->render(
            'dashboard/project/project/view/invoice.html.twig',
            [
                'project'  => $project,
                'invoices' => $invoiceRepository->search($filter, $request->query->getInt('page', 1)),
            ]
        );
    }

    /**
     * @Route("/{id}/view/loan", name="dashboard_project_view_loan", methods={"GET"}, requirements={"id": "\d+"})
     *
     * @IsGranted("PROJECT_VIEW", subject="project")
     */
    public function viewLoan(Project $project, Request $request, LoanRepository $loanRepository): Response
    {
        $this->addBreadcrumb($project)->addItem('bank.breadcrumb');

        $filter = (new FilterSearchLoan())
            ->setProject($project);

        return $this->render(
            'dashboard/project/project/view/loan.html.twig',
            [
                'project' => $project,
                'loans'   => $loanRepository->search($filter, $request->query->getInt('page', 1)),
            ]
        );
    }

    /**
     * @Route("/{id}/view/agency", name="dashboard_project_view_agency", methods={"GET"}, requirements={"id": "\d+"})
     *
     * @IsGranted("PROJECT_VIEW", subject="project")
     */
    public function viewAgency(Project $project, Request $request, MandateRepository $mandateRepository): Response
    {
        $this->addBreadcrumb($project)->addItem('agency.breadcrumb');

        $filter = (new FilterSearchMandate())
            ->setProject($project);

        return $this->render(
            'dashboard/project/project/view/agency-mandate.html.twig',
            [
                'project'  => $project,
                'mandates' => $mandateRepository->search($filter, $request->query->getInt('page', 1)),
            ]
        );
    }

    /**
     * @Route("/{id}/view/enterprise", name="dashboard_project_view_enterprise", methods={"GET"}, requirements={"id": "\d+"})
     *
     * @IsGranted("PROJECT_VIEW", subject="project")
     */
    public function viewEnterprise(
        Project $project,
        Request $request,
        EnterpriseRepository $enterpriseRepository
    ): Response {
        $this->addBreadcrumb($project)->addItem('enterprise.breadcrumb');

        $filter = (new FilterSearchEnterprise())
            ->setProject($project);

        return $this->render(
            'dashboard/project/project/view/enterprise.html.twig',
            [
                'project'     => $project,
                'enterprises' => $enterpriseRepository->search($filter, $request->query->getInt('page', 1)),
            ]
        );
    }

    /**
     * @Route("/{id}/view/notary", name="dashboard_project_view_notary", methods={"GET"}, requirements={"id": "\d+"})
     *
     * @IsGranted("PROJECT_VIEW", subject="project")
     */
    public function viewNotary(Project $project, Request $request, ActRepository $actRepository): Response
    {
        $this->addBreadcrumb($project)->addItem('notary.breadcrumb');

        $filter = (new FilterSearchAct())
            ->setProject($project);

        return $this->render(
            'dashboard/project/project/view/notary-act.html.twig',
            [
                'project' => $project,
                'acts'    => $actRepository->search($filter, $request->query->getInt('page', 1)),
            ]
        );
    }

    /**
     * @Route("/{id}/view/bond", name="dashboard_project_view_bond", methods={"GET"}, requirements={"id": "\d+"})
     *
     * @IsGranted("PROJECT_VIEW", subject="project")
     */
    public function viewBond(Project $project, Request $request, BondRepository $bondRepository): Response
    {
        $this->addBreadcrumb($project)->addItem('bond.breadcrumb');

        $filter = (new FilterSearchBond())
            ->setProject($project);

        return $this->render(
            'dashboard/project/project/view/bond.html.twig',
            [
                'project' => $project,
                'bonds'   => $bondRepository->search($filter, $request->query->getInt('page', 1)),
            ]
        );
    }

    /**
     * @Route("/{id}/view/notification", name="dashboard_project_view_notification", methods={"GET"}, requirements={"id": "\d+"})
     *
     * @IsGranted("PROJECT_VIEW", subject="project")
     */
    public function viewNotification(Project $project): Response
    {
        $this->addBreadcrumb($project)->addItem('notification.breadcrumb');

        return $this->render(
            'dashboard/project/project/view/notification.html.twig',
            [
                'project'       => $project,
                'notifications' => [],
            ]
        );
    }

    /**
     * @Route("/{id}/view/lot", name="dashboard_project_view_lot", methods={"GET"}, requirements={"id": "\d+"})
     *
     * @IsGranted("PROJECT_VIEW", subject="project")
     */
    public function viewLot(Project $project, Request $request, LotRepository $lotRepository): Response
    {
        $this->addBreadcrumb($project)->addItem('property.lot.breadcrumb');

        $filter = (new FilterSearchLot())
            ->setProject($project);

        return $this->render(
            'dashboard/project/project/view/lot.html.twig',
            [
                'project' => $project,
                'lots'    => $lotRepository->search($filter, $request->query->getInt('page', 1)),
            ]
        );
    }

    /**
     * @Route("/{id}/view/offer", name="dashboard_project_view_offer", methods={"GET"}, requirements={"id": "\d+"})
     *
     * @IsGranted("PROJECT_VIEW", subject="project")
     */
    public function viewOffer(Project $project, Request $request, OfferRepository $offerRepository): Response
    {
        $this->addBreadcrumb($project)->addItem('customer.offer.breadcrumb');

        $filter = (new FilterSearchOffer())
            ->setProject($project);

        return $this->render(
            'dashboard/project/project/view/offer.html.twig',
            [
                'project' => $project,
                'offers'  => $offerRepository->search($filter, $request->query->getInt('page', 1)),
            ]
        );
    }

    /**
     * @Route("/{id}/view/gallery", name="dashboard_project_view_gallery", methods={"GET"}, requirements={"id": "\d+"})
     *
     * @IsGranted("PROJECT_VIEW", subject="project")
     */
    public function viewGallery(Project $project): Response
    {
        $this->addBreadcrumb($project)->addItem('gallery.breadcrumb', 'dashboard_project_view_gallery');

        return $this->render(
            'dashboard/project/project/view/gallery.html.twig',
            [
                'project'   => $project,
                'galleries' => [],
            ]
        );
    }

    /**
     * @Route("/{id}/view/report", name="dashboard_project_view_report", methods={"GET"}, requirements={"id": "\d+"})
     *
     * @IsGranted("PROJECT_VIEW", subject="project")
     */
    public function viewReport(Project $project, Request $request, ReportRepository $reportRepository): Response
    {
        $this->addBreadcrumb($project)->addItem('project.report.breadcrumb');

        $filter = (new FilterSearchReport())
            ->setProject($project);

        return $this->render(
            'dashboard/project/project/view/report.html.twig',
            [
                'project' => $project,
                'reports' => $reportRepository->search($filter, $request->query->getInt('page', 1)),
            ]
        );
    }

    /**
     * @Route("/autocomplete", name="dashboard_project_autocomplete", methods={"GET"})
     */
    public function autocomplete(Request $request)
    {
        return new AutocompleteResponse(
            $this->projectHandler->findProjectByUserQuery($request->query),
            $this->identifierService
        );
    }

    /**
     * @Route("/is_acquisition", name="dashboard_project_is_acquisition", methods={"GET"})
     */
    public function isAcquisition(Request $request, ProjectRepository $repository)
    {
        if (!$request->get('project_id')) {
            throw new JsonException('"project_id" required');
        }

        $project = $repository->find($request->get('project_id'));

        if (!$project) {
            throw new JsonException('Project not found');
        }

        return new JsonResponse([
           'isAcquisition' => $project->isAcquisition(),
        ]);
    }

    /**
     * Upsert.
     */
    private function upsert(Project $project, Request $request): Response
    {
        if ($sas = $request->query->getInt('sas', 0)) {
            if (!$project->getId()) {
                $sas = $this->sasRepository->find($sas);

                if ($sas instanceof Sas) {
                    $project->setCompanySas($sas);
                }
            }
        }

        $form = $this
            ->createForm(ProjectType::class, $project)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            if ($person = $request->query->getInt('person', 0)) {
                return $this->redirectToRoute(
                    'dashboard_person_view_project',
                    [
                        'id' => $person,
                    ]
                );
            }
            if ($sas) {
                return $this->redirectToRoute(
                    'dashboard_company_sas_view_project',
                    [
                        'id' => ($sas instanceof Sas) ? $sas->getCompany()->getId() : $sas,
                    ]
                );
            }

            return $this->redirectToRoute('dashboard_project_index');
        }

        return $this->render(
            'dashboard/project/project/upsert.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    private function addBreadcrumb(?Project $project = null): Breadcrumbs
    {
        $this->breadcrumbs
            ->addRouteItem('dashboard.breadcrumb', 'dashboard_dashboard')
            ->addRouteItem('project.breadcrumb', 'dashboard_project_index');

        if ($project) {
            $this->breadcrumbs->addRouteItem($project, 'dashboard_project_view_date', ['id' => $project->getId()]);
        }

        return $this->breadcrumbs;
    }
}
