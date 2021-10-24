<?php

namespace core\models\payment;

interface InvoiceBoundModelInterface
{
    public function onInvoicePaid(): bool;
}
