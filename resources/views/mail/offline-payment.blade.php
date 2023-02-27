<x-mail::message>
Residents of the {{config('app.name')}} community have reported contributing to the fund via bank transfer.

In order to keep the online community up-to-date on fund and campaign balances, please login by clicking
below to reconcile and verify payments.

<x-mail::button :url="config('app.url')">
Login
</x-mail::button>

</x-mail::message>
