<?php

namespace App\Core\Helpers\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasCompositePrimaryKey
{
    /**
     * @param $query
     * @return Builder
     */
    protected function setKeysForSaveQuery($query): Builder
    {
        // Eloquent `getKeyName()` ni `string` deb e'lon qiladi, lekin bu
        // traitdan foydalanadigan modellarda `$primaryKey` massiv bo'ladi.
        // Annotatsiya haqiqiy qiymat turini bildiradi, aks holda quyidagi
        // shart "har doim yolg'on" deb hisoblanardi.
        /** @var array<int, string>|string $keys */
        $keys = $this->getKeyName();

        if (!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach ($keys as $key) {
            $query->where($key, '=', $this->getKeyForSaveQuery($key));
        }

        return $query;
    }

    /**
     * @param string|null $key
     * @return mixed
     */
    protected function getKeyForSaveQuery(?string $key = null): mixed
    {
        if (is_null($key)) {
            return parent::getKeyForSaveQuery();
        }

        if (isset($this->original[$key])) {
            return $this->original[$key];
        }

        return $this->getAttribute($key);
    }


    /**
     * Kompozit kalitda `$key` massiv bo'lishi mumkin.
     *
     * @param array<int, string>|string $key
     * @return mixed
     */
    public function getAttribute($key): mixed
    {
        if (is_array($key)) {
            foreach ($key as $item) {
                return parent::getAttribute($item);
            }
        }

        return parent::getAttribute($key);
    }
}
