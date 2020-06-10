<?php

namespace App\Handler\Role;

use App\Cache\Adapter;
use App\Repository\Role\RoleRepository;

class RoleHandler
{
    private const KEY_ROLES = 'role.names';

    /** @var RoleRepository */
    private $repository;

    /** @var Adapter */
    private $cache;

    public function __construct(RoleRepository $repository, Adapter $cache)
    {
        $this->repository = $repository;
        $this->cache   = $cache;
    }

    public function getNames(): array
    {
        if ($this->cache->hasData(self::KEY_ROLES)) {
            $values = $this->cache->getData(self::KEY_ROLES);
        } else {
            $result = $this->repository->getNames();
            $values = array_column($result, 'name');
            $this->cache->setData(self::KEY_ROLES, $values);
        }

        return $values;
    }

    public function clear(): void
    {
        $this->cache->clear(self::KEY_ROLES);
    }
}
