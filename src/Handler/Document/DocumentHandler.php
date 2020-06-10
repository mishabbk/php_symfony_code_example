<?php

namespace App\Handler\Document;

use App\Cache\Adapter;
use App\Service\Document\DocumentService;

class DocumentHandler
{
    private const KEY_TYPABLE_ENTITIES = 'document.typable_entities';

    /** @var DocumentService */
    private $service;

    /** @var Adapter */
    private $cache;

    public function __construct(DocumentService $service, Adapter $cache)
    {
        $this->service = $service;
        $this->cache   = $cache;
    }

    public function getTypableEntities(): array
    {
        if ($this->cache->hasData(self::KEY_TYPABLE_ENTITIES)) {
            $values = $this->cache->getData(self::KEY_TYPABLE_ENTITIES);
        } else {
            $values = $this->service->getTypableEntities();
            $this->cache->setData(self::KEY_TYPABLE_ENTITIES, $values);
        }

        return $values;
    }

    public function clear(): void
    {
        $this->cache->clear(self::KEY_TYPABLE_ENTITIES);
    }
}
