<?php

namespace core\collectors\payment;

use core\collectors\AbstractDataCollector;
use core\models\payment\Invoice;

class InvoiceAllCollector extends AbstractDataCollector
{
    public function get(): array
    {
        /** @var Invoice[] */
        $invoices = Invoice::find()
            ->where([
                'user_id' => $this->getParam('userId')
            ])
            ->orderBy([
                'payment_date' => SORT_DESC,
                'id' => SORT_DESC
            ])
            ->all();

        return array_map(fn (Invoice $invoice) => [
            'id' => $invoice->getId(),
            'creationDate' => $invoice->getCreationDate('d-m-Y'),
            'paymentDate' => $invoice->getPaymentDate('d-m-Y'),
            'paymentAmount' => $invoice->getPaymentAmount(),
            'serviceName' => $invoice->getBoundModel()->getName()
        ], $invoices);
    }
}
