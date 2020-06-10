<?php

namespace App\Form\Extension;

use App\Form\Type\EntityRemoteType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EntityRemoteExtension.
 */
class EntityRemoteExtension extends AbstractTypeExtension
{
    /**
     * @return array|string
     */
    public static function getExtendedTypes(): iterable
    {
        return [EntityRemoteType::class];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(['route', 'pagination', 'custom_filters']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $attr = [];
        if (isset($options['route'])) {
            $attr['data-route'] = $options['route'];
            if (isset($options['custom_filters'])
                && is_array($options['custom_filters'])
                && !empty($options['custom_filters'])) {
                foreach ($options['custom_filters'] as $filter => $value) {
                    $attr['data-route'] = $this->appendRouteFilter(
                        $attr['data-route'],
                        $filter,
                        $value
                    );
                }
            }
        }
        if (isset($options['pagination'])) {
            $attr['data-pagination'] = (int) ($options['pagination']);
        }
        if (!empty($attr)) {
            if (isset($options['attr'])) {
                $view->vars['attr'] = array_merge($view->vars['attr'], $attr);
            } else {
                $view->vars['attr'] = $attr;
            }
        }
    }

    private function appendRouteFilter(string $route, string $filter, $value): string
    {
        if (false === mb_stripos($route, '?')) {
            $separator = '?';
        } else {
            $separator = '&';
        }

        return $route.$separator.http_build_query([$filter => $value]);
    }
}
