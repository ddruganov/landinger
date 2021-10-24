<?php

namespace core\models\payment;

use core\components\ExecutionResult;

interface InvoiceBoundModelInterface
{
    public function onInvoicePaid(): ExecutionResult;
    public function isPaid(): bool;
}
