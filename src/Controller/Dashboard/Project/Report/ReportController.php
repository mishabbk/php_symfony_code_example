<?php

namespace App\Controller\Dashboard\Project\Report;

use App\Entity\Person;
use App\Entity\Project\Project;
use App\Entity\Project\Report\Report;
use App\Form\Project\Report\ReportType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

/**
 * @Route("/dashboard/project/{id}/report", requirements={"id": "\d+"})
 */
class ReportController extends AbstractController
{
    /**
     * @var Breadcrumbs
     */
    private $breadcrumbs;

    /**
     * ReportController constructor.
     */
    public function __construct(Breadcrumbs $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * @Route("/create", name="dashboard_project_report_create", methods={"GET", "POST"})
     */
    public function create(Project $project, Request $request)
    {
        $this->addBreadcrumb($project)->addItem('breadcrumb.add');

        return $this->upsert($project, new Report(), $request);
    }

    /**
     * @Route("/{report}/edit", name="dashboard_project_report_edit", methods={"GET", "POST"}, requirements={"report": "\d+"})
     */
    public function edit(Project $project, Report $report, Request $request)
    {
        $this->addBreadcrumb($project)->addItem('breadcrumb.edit');

        return $this->upsert($project, $report, $request);
    }

    /**
     * Upsert.
     */
    private function upsert(Project $project, Report $report, Request $request): Response
    {
        if ($report->getId() === null) {
            $report->setProject($project);
            /** @var Person $person */
            $person = $this->getUser();
            $report->setPerson($person);
        }

        $form = $this
            ->createForm(ReportType::class, $report)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($report);
            $em->flush();

            return $this->redirectToRoute(
                'dashboard_project_view_report',
                [
                    'id' => $project->getId()
                ]
            );
        }

        return $this->render(
            'dashboard/project/report/report/upsert.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Add breadcrumb.
     */
    private function addBreadcrumb(Project $project): Breadcrumbs
    {
        return $this->breadcrumbs
            ->addRouteItem('dashboard.breadcrumb', 'dashboard_dashboard')
            ->addRouteItem('project.breadcrumb', 'dashboard_project_index')
            ->addRouteItem('project.report.breadcrumb', 'dashboard_project_view_report', ['id' => $project->getId()]);
    }
}
