<?php

namespace App\Service\Document;

use App\Entity\Document\DocumentTypeInterface;
use App\Entity\Document\TypeToEntity;
use App\Repository\Document\DocumentTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class DocumentTypeService.
 */
class DocumentTypeService
{
    /** @var DocumentTypeRepository */
    private $documentTypeRepository;

    public function __construct(DocumentTypeRepository $documentTypeRepository)
    {
        $this->documentTypeRepository = $documentTypeRepository;
    }

    public function getDocumentTypeForEntity($entity): ArrayCollection
    {
        if (!$entity instanceof DocumentTypeInterface) {
            return new ArrayCollection();
        }

        $reflectionClass = new \ReflectionClass($entity);
        $entityClassName = call_user_func(
            [
                $reflectionClass->getName(),
                'getEntityName',
            ]
        );

        $types = $this->documentTypeRepository->getAllForEntity($entityClassName);

        $collection = new ArrayCollection();
        foreach ($types as $type) {
            foreach ($type->getTypeToEntities() as $typeToEntity) {
                if ($entityClassName !== $typeToEntity->getEntity()) {
                    continue;
                }
                if ($this->isRequirementReached($entity, $typeToEntity)) {
                    $collection->add($type);
                }
            }
        }

        return $collection;
    }

    public function isRequirementReached($entity, TypeToEntity $documentType)
    {
        if (empty($documentType->getRequirement())) {
            return true;
        }
        if (!preg_match(
            '/\A([^<=>!]+)([<=>]{1,2}|!=|IN|NOT IN)([^<=>!]+)\z/',
            $documentType->getRequirement(),
            $matches
        )) {
            return true;
        }
        $methods = explode('.', trim($matches[1]));
        $value   = $entity;
        foreach ($methods as $method) {
            $method = str_replace(['(', ')'], '', $method);
            if (in_array($method, ['this', '$this'])
                || !preg_match('/\A(is|get)/', $method)
                || !method_exists($value, $method)) {
                continue;
            }
            $value = $value->{$method}();
        }
        if ($value === $entity) {
            return true;
        }

        $methods = explode('.', trim($matches[3]));
        $target  = $entity;
        foreach ($methods as $method) {
            $method = str_replace(['(', ')'], '', $method);
            if (in_array($method, ['this', '$this'])
                || !preg_match('/\A(is|get)/', $method)
                || !method_exists($target, $method)) {
                continue;
            }
            $target = $value->{$method}();
        }
        if ($target === $entity) {
            $target = preg_replace(['/\A"/', '/"\z/'], '', trim($matches[3]));
        }

        if (in_array($matches[2], ['IN', 'NOT IN'])) {
            $newTarget = [];
            foreach (explode(',', preg_replace(['/\A\(/', '/\)\z/'], '', $target)) as $tar) {
                $newTarget[] = preg_replace(['/\A"/', '/"\z/'], '', trim($tar));
            }

            if ('IN' === $matches[2]) {
                return in_array($value, $newTarget);
            }

            return !in_array($value, $newTarget);
        }

        switch ($matches[2]) {
            case '=':
                return $value == $target;
                break;
            case '!=':
                return $value != $target;
                break;
            case '<':
                return $value < $target;
                break;
            case '>':
                return $value > $target;
                break;
            case '<=':
                return $value <= $target;
                break;
            case '>=':
                return $value >= $target;
                break;
        }

        return true;
    }
}
