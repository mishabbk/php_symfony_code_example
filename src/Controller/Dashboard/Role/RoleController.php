<?php

namespace App\Controller\Dashboard\Role;

use App\Entity\Employee\Type;
use App\Entity\Role\Role;
use App\Form\Role\RoleType;
use App\Handler\Role\RoleHandler;
use App\Repository\Employee\TypeRepository;
use App\Repository\Role\RoleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

/**
 * @Route("/dashboard/role")
 */
class RoleController extends AbstractController
{
    /**
     * @var RoleHandler
     */
    private $roleHandler;

    /**
     * RoleController constructor.
     */
    public function __construct(RoleHandler $roleHandler)
    {
        $this->roleHandler = $roleHandler;
    }

    /**
     * @Route("/", name="dashboard_role_index", methods={"GET", "POST"})
     */
    public function index(
        RoleRepository $roleRepository,
        TypeRepository $typeRepository,
        Request $request,
        Breadcrumbs $breadcrumbs
    ): Response
    {
        $breadcrumbs->addRouteItem('dashboard.breadcrumb', 'dashboard_dashboard');
        $breadcrumbs->addRouteItem('role.breadcrumb', 'dashboard_role_index');

        $role = new Role();
        $form = $this
            ->createForm(RoleType::class, $role)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($role);
            $this->getDoctrine()->getManager()->flush();

            $this->roleHandler->clear();

            return $this->redirectToRoute('dashboard_role_index');
        }

        return $this->render(
            'dashboard/role/role/index.html.twig',
            [
                'roles' => $roleRepository->findBy([],['name' => 'ASC']),
                'types' => $typeRepository->findBy([],['name' => 'ASC']),
                'form'  => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="dashboard_role_edit", methods={"POST"}, requirements={"id": "\d+"})
     */
    public function edit(
        Role $role,
        TypeRepository $typeRepository,
        Request $request
    ): JsonResponse
    {
        if ($request->request->get('action') == 'associate') {
            $role->setAssociate($request->request->get('value') === 'add');
        } elseif ($request->request->get('action') == 'employeeType') {
            $type = $typeRepository->find($request->request->get('typeID'));
            if ($type instanceof Type) {
                if ($request->request->get('value') == 'add') {
                    $role->addEmployeeType($type);
                } else {
                    $role->removeEmployeeType($type);
                }
            }
        }
        $this->getDoctrine()->getManager()->persist($role);
        $this->getDoctrine()->getManager()->flush();

        $this->roleHandler->clear();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
