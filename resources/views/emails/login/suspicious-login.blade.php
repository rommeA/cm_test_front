@component('mail::message')
# Unusual login into your account (en)


<p>We detected an unusual login at {{ $data['login_at']->setTimezone('Europe/Moscow')->format('d.m.Y H:i') }} Timezone: Moscow.</p>
<p>If it was not you, please click the button below to block your account and change your password.</p>

# Подозрительный вход в ваш аккаунт (ru)
<p>Мы зафиксировали необычный вход в ваш аккаунт {{ $data['login_at']->setTimezone('Europe/Moscow')->format('d.m.Y') }} в {{ $data['login_at']->setTimezone('Europe/Moscow')->format('H:i') }} (по Мск).</p>
<p>Если это были не вы, пожалуйста, нажмите кнопку ниже, чтобы заблокировать ваш аккаунт и сменить пароль.</p>

@if(isset($data['city_name']) or isset($data['country_name']))
<p>Location (Местоположение): {{ $data['city_name'] ?? '' }}, {{ $data['country_name'] ?? '' }}</p>
@endif
<p>Device (Устройство): {{ $data['device'] }}.</p>
<p>IP-address (IP-адрес): {{ $data['ip_address'] }}</p>

@component('mail::button', ['url' => $route])
Block account / Заблокировать аккаунт
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
