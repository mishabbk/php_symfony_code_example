<?php

/** @noinspection PhpUnusedParameterInspection */

namespace App\Twig\Pagination;

use App\Service\Pagination\Paginator;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class RenderExtension.
 */
class RenderExtension extends AbstractExtension
{
    private const TEMPLATE = 'Pagination/pagination.html.twig';

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var string
     */
    private $pageParameterName = 'page';

    /**
     * RenderExtension constructor.
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'pagination_render',
                [$this, 'renderPagination'],
                ['is_safe' => ['html'], 'needs_environment' => true]
            ),
            new TwigFunction(
                'pagination_counter',
                [$this, 'counterPagination'],
                ['is_safe' => ['html'], 'needs_environment' => true]
            ),
        ];
    }

    /**
     * @return string
     */
    public function getPageParameterName()
    {
        return $this->pageParameterName;
    }

    /**
     * @param string $pageParameterName
     *
     * @return $this
     */
    public function setPageParameterName($pageParameterName)
    {
        $this->pageParameterName = $pageParameterName;

        return $this;
    }

    /**
     * @return array
     */
    public function getQuery()
    {
        $request = $this->requestStack->getCurrentRequest();

        $query = array_merge($request->query->all(), $request->attributes->get('_route_params', []));
        foreach ($query as $key => $param) {
            if ('_' == mb_substr($key, 0, 1)) {
                unset($query[$key]);
            }
        }

        return $query;
    }

    /**
     * Render the pagination.
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderPagination(Environment $environment, Paginator $paginator): string
    {
        $request = $this->requestStack->getCurrentRequest();

        $page      = $paginator->getPage();
        $pageRange = $paginator->getPageRange();
        $pageCount = $paginator->getPageCount();

        if ($pageCount < $page) {
            $page = $pageCount;
        }

        if ($pageRange > $pageCount) {
            $pageRange = $pageCount;
        }

        $delta = ceil($pageRange / 2);

        if ($page - $delta > $pageCount - $pageRange) {
            $pages = range($pageCount - $pageRange + 1, $pageCount);
        } else {
            if ($page - $delta < 0) {
                $delta = $page;
            }

            $offset = $page - $delta;
            $pages  = range($offset + 1, $offset + $pageRange);
        }

        $viewData = [
            'last'              => $pageCount,
            'current'           => $page,
            'first'             => 1,
            'pageCount'         => $pageCount,
            'pagesInRange'      => $pages,
            'route'             => $request->attributes->get('_route'),
            'route_params'      => $request->attributes->get('_route_params'),
            'query'             => $this->getQuery(),
            'pageParameterName' => $this->getPageParameterName(),
        ];

        if ($page > 1) {
            $viewData['previous'] = $page - 1;
        }

        if ($page < $pageCount) {
            $viewData['next'] = $page + 1;
        }

        return $environment->load(self::TEMPLATE)->render($viewData);
    }

    public function counterPagination(Environment $environment, Paginator $paginator): array
    {
        return [
            'min'   => 1 + ($paginator->getPage() - 1) * $paginator->getNbPerPage(),
            'max'   => min($paginator->getPage() * $paginator->getNbPerPage(), $paginator->count()),
            'total' => $paginator->count(),
        ];
    }
}
