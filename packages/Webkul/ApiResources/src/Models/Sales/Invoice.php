<?php

namespace Webkul\ApiResources\Models\Sales;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Sales\Models\InvoiceItemProxy;

#[ApiResource]
class Invoice extends \Webkul\Sales\Models\Invoice
{
    /**
     * Get invoice items.
     */
    #[ApiProperty(readableLink: true)]
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItemProxy::modelClass());
    }
}
