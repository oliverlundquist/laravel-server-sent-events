<?php declare(strict_types=1);

namespace App\LiveDashboard\Data;

use App\LiveDashboard\Validation\ValidIf;

class CustomerMessageData extends DataObject
{
    #[ValidIf(['string', 'in:discount_offer,support_message'])]
    public string $message_type {
        set => $this->validateProperty('message_type', $value);
    }

    // virtual property
    public string $message_text {
        get => $this->getMessage();
    }

    public function getMessage()
    {
        return match ($this->message_type) {
            'discount_offer'  => 'Get 10% off discount code for this product, only valid for 10 minutes!',
            'support_message' => 'If you have any questions about this product, don\'t hesitate to chat with us!',
            default           => ''
        };
    }

    /**
     * @return class-string
     */
    protected function getEloquentClassName(): string
    {
        return ''; // not an eloquent data object
    }
}
