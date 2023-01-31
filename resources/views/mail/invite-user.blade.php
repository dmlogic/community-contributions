<x-mail::message>
You have been invited to join the {{config('app.name')}} community.

Click below to accept the invite and login to the online portal.

<x-mail::panel>

**{{ $invite->name}}**

@if($invite->property)
Moved to {{$invite->property->address}}
@endif

<x-mail::button :url="route('invitation.confirm', $invite->code)">
Accept invitation
</x-mail::button>
</x-mail::panel>


</x-mail::message>
