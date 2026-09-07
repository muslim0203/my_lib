<?php

namespace App\Core\Repository\Merchant;

use App\Models\Users\Merchant;

class MerchantRepository
{
    /**
     * @param Merchant $merchant
     * @param array $options
     * @return void
     */
    public function save(Merchant $merchant, array $options = []): void
    {
        if (!$merchant->save($options)) {
            throw new \RuntimeException(__('client.Merchant not saved.'));
        }
    }
}
