<x-mail::message>
Please don't forget your community contribution.

<x-mail::panel>
A contribution of **{{$request->value}}** towards

**{{$campaign->fund->name}} - _{{$campaign->description}}_**

was requested on {{$request->created_at->format('d/m/Y')}}

<x-mail::button :url="$payment_link">
Pay online
</x-mail::button>
</x-mail::panel>

So far we have raised a total of **{{$campaign->raised_value}}** against our target of **{{$campaign->target_value}}**.

Rather than pay online, you can save the fund some fees by paying via bank transfer:

```
ACCOUNT NAME: {{ config('app.payment_account.name') }}
SORT CODE: {{ config('app.payment_account.code') }}
ACCOUNT NUMBER: {{ config('app.payment_account.number') }}
```

If you choose this method, _please reply to this email to let us know you've paid_.

Thank you for your contribution to our community<br>
**{{ config('app.name') }}**

<x-mail::subcopy>
Keep up to date with latest fund values and activity by [visiting the website](config('app.url'))
</x-mail::subcopy>

</x-mail::message>
