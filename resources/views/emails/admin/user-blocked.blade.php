@component('mail::message')
# New Event: User Blocked / Новое событие: пользователь заблокирован

User {{ $user->name }} has blocked theirs account.
Пользователь {{ $user->name }} заблокировал свой аккаунт.

@component('mail::button', ['url' => $url])
Open user's settings / К настройкам пользователя
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
