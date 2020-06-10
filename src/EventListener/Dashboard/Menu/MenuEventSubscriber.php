<?php

namespace App\EventListener\Dashboard\Menu;

use App\Event\Dashboard\Menu\MenuEvent;
use App\Modele\Menu\MenuItem;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class MenuEventSubscriber.
 */
class MenuEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    private $security;

    /**
     * MenuEventSubscriber constructor.
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [MenuEvent::class => ['addMenu']];
    }

    /**
     * Add menu.
     */
    public function addMenu(MenuEvent $event): void
    {
        $menu = [];

        // tickets
        $menu[] = (new MenuItem('ticket.breadcrumb', 'dashboard_ticket_index', [], ['TICKET_VIEW'], 'far fa-envelope'));

        // projects
        $menu[] = (new MenuItem('project.breadcrumb', false, [], [], 'fas fa-sitemap'))
            ->addChild(
                new MenuItem(
                    'project.breadcrumb',
                    'dashboard_project_view_index',
                    [],
                    ['PROJECT_VIEW']
                )
            )
            ->addChild(
                new MenuItem(
                    'project.prospector.breadcrumb',
                    'dashboard_project_prospector_index',
                    [],
                    ['PROSPECTOR_MANAGE']
                )
            );

        // properties
        $menu[] = (new MenuItem('property.breadcrumb', false, [], [], 'fas fa-home'))
            ->addChild(new MenuItem('property.breadcrumb', 'dashboard_property_view_index'))
            ->addChild(new MenuItem('property.lot.breadcrumb', 'dashboard_property_lot_view_index'))
            ->addChild(new MenuItem('property.date.breadcrumb', 'dashboard_property_date_index'))
            ->addChild(new MenuItem('property.task.breadcrumb', 'dashboard_property_task_index'))
            ->addChild(new MenuItem('property.task.invoice.breadcrumb', 'dashboard_property_task_invoice_index'))
            ->addChild(new MenuItem('property.urbanism.breadcrumb', 'dashboard_property_urbanism_restriction_index'))
            ->addChild(new MenuItem('property.urbanism.date.breadcrumb', 'dashboard_property_urbanism_date_index'))
            ->addChild(
                new MenuItem(
                    'property.date.type.breadcrumb',
                    'dashboard_property_date_type_index',
                    [],
                    ['PROPERTY_DATE_TYPE_MANAGE']
                )
            )
            ->addChild(
                new MenuItem(
                    'property.task.type.breadcrumb',
                    'dashboard_property_task_type_index',
                    [],
                    ['PROPERTY_TASK_TYPE_MANAGE']
                )
            )
            ->addChild(
                new MenuItem(
                    'property.urbanism.type.breadcrumb',
                    'dashboard_property_urbanism_type_index',
                    [],
                    ['PROPERTY_URBANISM_TYPE_MANAGE']
                )
            )
        ;

        // customers
        $menu[] = (new MenuItem('customer.breadcrumb', false, [], [], 'fas fa-users'))
            ->addChild(new MenuItem('customer.breadcrumb', 'dashboard_customer_index'))
            ->addChild(new MenuItem('customer.offer.breadcrumb', 'dashboard_customer_offer_index'));

        // investors
        $menu[] = (new MenuItem('investor.breadcrumb', false, [], [], 'fas fa-money-check-alt'))
            ->addChild(new MenuItem('investor.breadcrumb', 'dashboard_investor_index'));

        // banks
        $menu[] = (new MenuItem('bank.breadcrumb', false, [], [], 'fas fa-piggy-bank'))
            ->addChild(new MenuItem('bank.breadcrumb', 'dashboard_bank_index'))
            ->addChild(new MenuItem('bank.account.breadcrumb', 'dashboard_bank_account_index'))
            ->addChild(new MenuItem('bank.loan.breadcrumb', 'dashboard_bank_loan_index'))
            ->addChild(new MenuItem('bank.transfert.breadcrumb', 'dashboard_bank_transfert_index'))
            ->addChild(
                new MenuItem(
                    'bank.loan.type.breadcrumb',
                    'dashboard_bank_loan_type_index',
                    [],
                    ['BANK_LOAN_TYPE_MANAGE']
                )
            );

        // documents
        $menu[] = new MenuItem('document.breadcrumb', 'dashboard_document_index', [], [], 'fas fa-copy');

        // meetings
        $menu[] = new MenuItem('meeting.breadcrumb', 'meeting_index', [], ['MEETING_VIEW'], 'far fa-calendar-alt');

        // companies
        $menu[] = (new MenuItem('company.breadcrumb', false, [], [], 'far fa-building'))
            ->addChild(new MenuItem('company.breadcrumb', 'dashboard_company_index'))
            ->addChild(new MenuItem('company.sas.breadcrumb', 'dashboard_company_sas_view_index'))
            ->addChild(new MenuItem('company.associate.breadcrumb', 'dashboard_company_associate_view_index'))
            ->addChild(new MenuItem('company.associate.sas.breadcrumb', 'dashboard_company_associate_to_sas_index'))
            ->addChild(new MenuItem('company.associate.cca.breadcrumb', 'dashboard_company_associate_cca_index'))
            ->addChild(new MenuItem('company.ubo.breadcrumb', 'dashboard_company_ubo_index'))
            ->addChild(new MenuItem('company.position.breadcrumb', 'positions_index'))
            ->addChild(new MenuItem('company.code_ape.breadcrumb', 'dashboard_company_code_ape_index'))
            ->addChild(
                new MenuItem(
                    'company.type.breadcrumb',
                    'dashboard_company_type_index',
                    [],
                    ['COMPANY_TYPE_MANAGE']
                )
            );

        // enterprises
        $menu[] = (new MenuItem('enterprise.breadcrumb', false, [], [], 'fas fa-hard-hat'))
            ->addChild(new MenuItem('enterprise.breadcrumb', 'dashboard_enterprise_view_index'))
            ->addChild(new MenuItem('enterprise.insurance.breadcrumb', 'dashboard_enterprise_insurance_index'))
            ->addChild(
                new MenuItem(
                    'enterprise.insurance.type.breadcrumb',
                    'dashboard_enterprise_insurance_type_index',
                    [],
                    ['ENTERPRISE_INSURANCE_TYPE_MANAGE']
                )
            )
        ;

        // agencies
        $menu[] = (new MenuItem('agency.breadcrumb', false, [], [], 'fas fa-laptop-house'))
            ->addChild(new MenuItem('agency.breadcrumb', 'dashboard_agency_view_index'))
            ->addChild(new MenuItem('mandate.breadcrumb', 'dashboard_agency_mandate_index'));

        // notaries
        $menu[] = (new MenuItem('notary.breadcrumb', false, [], [], 'fas fa-user-tie'))
            ->addChild(new MenuItem('notary.breadcrumb', 'dashboard_notary_view_index'))
            ->addChild(new MenuItem('notary.act.breadcrumb', 'dashboard_notary_act_index'))
            ->addChild(
                new MenuItem(
                    'notary.act.type.breadcrumb',
                    'dashboard_notary_act_type_index',
                    [],
                    ['NOTARY_ACT_TYPE_MANAGE']
                )
            )
        ;

        // bonds
        $menu[] = (new MenuItem('bond.breadcrumb', false, [], [], 'fas fa-comment-dollar'))
            ->addChild(new MenuItem('bond.breadcrumb', 'dashboard_bond_view_index'))
            ->addChild(new MenuItem('bond.transfert.breadcrumb', 'dashboard_bond_transfert_index'));

        // reviews
        $menu[] = new MenuItem('review.breadcrumb', 'dashboard_review_index', [], [], 'far fa-comment-dots');

        // employees
        $menu[] = (new MenuItem('employee.breadcrumb', false, [], [], 'fas fa-users-cog'))
            ->addChild(new MenuItem('employee.breadcrumb', 'dashboard_employee_index'))
            ->addChild(
                new MenuItem(
                    'employee.type.breadcrumb',
                    'dashboard_employee_type_index',
                    [],
                    ['EMPLOYEE_TYPE_MANAGE']
                )
            )
            ->addChild(new MenuItem('employee.contract.breadcrumb', 'dashboard_employee_contract_index'));

        // persons
        $menu[] = (new MenuItem('person.breadcrumb', 'dashboard_person_view_index', [], [], 'fas fa-id-card-alt'));

        $menu[] = (new MenuItem('miscellaneous', false, [], [], 'fas fa-cogs'))
            ->addChild(
                new MenuItem(
                    'translation.breadcrumb',
                    'translation_index',
                    [],
                    ['TRANSLATION_MANAGE']
                )
            )
            ->addChild(
                new MenuItem(
                    'ticket.templates.breadcrumb',
                    'dashboard_ticket_template_index',
                    [],
                    ['TICKET_TEMPLATE_MANAGE']
                )
            )
            ->addChild(new MenuItem('faq.breadcrumb', 'dashboard_faq_index'))
            ->addChild(
                new MenuItem(
                    'document.type.breadcrumb',
                    'dashboard_document_type_index',
                    [],
                    ['DOCUMENT_TYPE_MANAGE']
                )
            )
            ->addChild(new MenuItem('country.breadcrumb', 'dashboard_country_index'))
            ->addChild(new MenuItem('role.breadcrumb', 'dashboard_role_index'));

        foreach ($menu as $menuItem) {
            $isGranted = true;
            foreach ($menuItem->getRoles() as $role) {
                if (!$this->security->isGranted($role)) {
                    $isGranted = false;
                    break;
                }
            }
            if ($isGranted) {
                if ($menuItem->hasChildren()) {
                    $children = [];
                    foreach ($menuItem->getChildren() as $child) {
                        $childIsGranted = true;
                        foreach ($child->getRoles() as $childRole) {
                            if (!$this->security->isGranted($childRole)) {
                                $childIsGranted = false;
                                break;
                            }
                        }
                        if ($childIsGranted) {
                            $children[] = $child;
                        }
                    }
                    $menuItem->setChildren($children);

                    if ($menuItem->hasChildren()) {
                        $event->addItem($menuItem);
                    }
                } else {
                    $event->addItem($menuItem);
                }
            }
        }
        $this->activateByRoute($event->getRequest()->get('_route'), $menu);
    }

    /**
     * Activate by route.
     *
     * @param MenuItem[] $items
     *
     * @return MenuItem[]
     */
    private function activateByRoute(string $route, $items)
    {
        foreach ($items as $item) {
            if ($item->hasChildren()) {
                $this->activateByRoute($route, $item->getChildren());
            } elseif ($item->getRoute() === $route) {
                $item->setIsActive(true);
            } elseif (mb_substr($item->getRoute(), 0, mb_strrpos($item->getRoute(), '_')) === mb_substr(
                    $route,
                    0,
                    mb_strrpos($route, '_')
                )) {
                $item->setIsActive(true);
            }
        }

        return $items;
    }
}
