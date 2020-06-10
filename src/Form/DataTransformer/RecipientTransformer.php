<?php

namespace App\Form\DataTransformer;

use App\Modele\Ticket\Recipient;
use App\Service\Ticket\RecipientService;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class RecipientTransformer.
 */
class RecipientTransformer implements DataTransformerInterface
{
    /** @var RecipientService */
    private $recipientService;

    /**
     * RecipientTransformer constructor.
     */
    public function __construct(RecipientService $recipientService)
    {
        $this->recipientService = $recipientService;
    }

    /**
     * @param array|null $recipients
     *
     * @return array|mixed
     */
    public function transform($recipients)
    {
        if (null === $recipients) {
            return [
                'recipient_entities' => [],
                'recipient_choices'  => '',
            ];
        }
        if (false === is_array($recipients)) {
            throw new TransformationFailedException('Expected an array');
        }

        $entities = [];
        /** @var Recipient[] $recipients */
        foreach ($recipients as $recipient) {
            $entities[] = $recipient->getId();
        }

        return [
            'recipient_entities' => json_encode($entities),
            'recipient_choices'  => $recipients,
        ];
    }

    /**
     * @param array $value
     *
     * @return Recipient[]|null
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }
        if (!is_array($value)) {
            throw new TransformationFailedException('Expected an array.');
        }
        if (empty($value['recipient_entities'])) {
            return null;
        }

        $value['recipient_entities'] = json_decode($value['recipient_entities'], true);

        $return = [];
        foreach ($value['recipient_entities'] as $recipientEntity) {
            if ($entity = $this->recipientService->getRecipientEntity($recipientEntity)) {
                $return[] = $entity;
            }
        }

        return $return;
    }
}
