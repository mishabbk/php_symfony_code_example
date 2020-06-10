<?php

namespace App\Form\DataTransformer;

use App\Service\EntityIdentifierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EntityFieldTransformer implements DataTransformerInterface
{
    /** @var string|null */
    private $className;

    /** @var bool */
    private $multiple = false;

    /** @var EntityManagerInterface */
    private $em;

    /** @var EntityIdentifierService */
    private $identifierService;

    public function __construct(EntityManagerInterface $em, EntityIdentifierService $identifierService)
    {
        $this->em                = $em;
        $this->identifierService = $identifierService;
    }

    public function getClassName(): ?string
    {
        return $this->className;
    }

    public function setClassName(?string $className): self
    {
        $this->className = $className;

        return $this;
    }

    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    public function setMultiple(bool $multiple): self
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * @param mixed $object
     *
     * @return array|mixed
     */
    public function transform($object)
    {
        if (null === $object) {
            return [
                'class_entity'  => $this->isMultiple() ? [] : '',
                'class_choices' => '',
            ];
        }

        if ($this->isMultiple()) {
            $entities = [];
            foreach ($object as $obj) {
                $entities[] = $this->identifierService->getEntityIdentifier($obj);
            }

            return [
                'class_entity'  => json_encode($entities),
                'class_choices' => $object,
            ];
        }

        return [
            'class_entity'  => $this->identifierService->getEntityIdentifier($object),
            'class_choices' => $object,
        ];
    }

    /**
     * @param mixed $value
     *
     * @return mixed|object|null
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return $this->isMultiple() ? [] : null;
        }

        if (!is_array($value)) {
            throw new TransformationFailedException('Expected an array.');
        }

        if (empty($value['class_entity'])) {
            return $this->isMultiple() ? [] : null;
        }

        if ($this->isMultiple()) {
            $value['class_entity'] = json_decode($value['class_entity'], true);

            if (empty($value['class_entity'])) {
                return [];
            }

            return $this->findEntities($value['class_entity']);
        }

        return $this->findEntities($value['class_entity']);
    }

    public function findEntities($entities)
    {
        if ($this->isMultiple()) {
            return $this->em->getRepository($this->getClassName())->findBy(
                [
                    $this->identifierService->getSingleIdentifierFieldName(
                        $this->getClassName()
                    ) => $entities,
                ]
            );
        }

        return $this->em->getRepository($this->getClassName())->find($entities);
    }
}
