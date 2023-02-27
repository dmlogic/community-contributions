<x-mail::message>
As per previous community agreements, you have a new funding request.

<x-mail::panel>
A contribution of **{{$request->value}}** towards

**{{$campaign->fund->name}} - _{{$campaign->description}}_**

<x-mail::button :url="$payment_link">
Pay online
</x-mail::button>
</x-mail::panel>

We are trying to raise a total of **{{$campaign->target_value}}** from the community during this campaign
so any additional contributions are always welcome!


Rather than pay online, you can save the fund some fees by paying via bank transfer:

```
ACCOUNT NAME: {{ config('app.payment_account.name') }}
SORT CODE: {{ config('app.payment_account.code') }}
ACCOUNT NUMBER: {{ config('app.payment_account.number') }}
```

If you choose this method, [let us know you've paid using this form]({{$offline_link}}).

Thank you for your contribution to our community<br>
**{{ config('app.name') }}**

<x-mail::subcopy>
Keep up to date with latest fund values and activity by [visiting the website](config('app.url'))
</x-mail::subcopy>

</x-mail::message>
