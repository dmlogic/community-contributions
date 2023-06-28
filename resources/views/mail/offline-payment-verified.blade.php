<x-mail::message>

PAYMENT VERIFIED

Thank you for your contribution towards **{{$ledger->fund->name}}**

The administator has confirmed receipt of the payment.

<x-mail::panel>

```
DATE: {{ $ledger->created_at }}
AMOUNT RECEIVED: {{ $ledger->value }}
````

</x-mail::panel>

</x-mail::message>
