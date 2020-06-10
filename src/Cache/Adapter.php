<?php

namespace App\Cache;

use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * Class Adapter.
 */
class Adapter extends FilesystemAdapter
{
    public function getData(string $key)
    {
        return $this->getItem($key)->get();
    }

    public function hasData(string $key): bool
    {
        return $this->hasItem($key);
    }

    public function setData(string $key, $datas): bool
    {
        return $this->save($this->getItem($key)->set($datas));
    }
}
