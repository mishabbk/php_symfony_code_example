<?php

namespace App\Controller\Dashboard\Menu;

use App\Event\Dashboard\Menu\MenuEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MenuController.
 */
class MenuController extends AbstractController
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->eventDispatcher = $dispatcher;
    }

    public function menuAction(Request $request): Response
    {
        if (!$this->eventDispatcher->hasListeners(MenuEvent::class)) {
            return new Response();
        }

        /** @var MenuEvent $event */
        $event = $this->eventDispatcher->dispatch(new MenuEvent($request));

        return $this->render(
            'dashboard/menu.html.twig',
            [
                'menu' => $event->getItems(),
            ]
        );
    }
}
