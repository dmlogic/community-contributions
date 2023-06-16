<x-mail::message>

PAYMENT RECEIPT

Thank you for your contribution towards **{{$ledger->fund->name}}**

<x-mail::panel>

```
DATE: {{ $ledger->created_at }}
AMOUNT RECEIVED: {{ $ledger->value }}
PAYMENT REFERENCE: {{ $ledger->provider_reference }}
````

</x-mail::panel>

</x-mail::message>
