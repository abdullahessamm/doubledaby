@component('mail::message')
# Hi, {{ $user->full_name }}


Some one requests us to change your account password,<br>
<span> If that was you please enter this code on recover page </span>: <strong>{{ $code }}</strong>,<br>
for username: <strong>{{ $user->username }}</strong>. <br><br>

Or click here:

@component( 'mail::button', ['url' => url('/email/recover') . '/' . $token . '?user_id=' . $user->id ])
Reset password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
