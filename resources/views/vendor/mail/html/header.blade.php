<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
@isset($message)
<img src="{{ $message->embed(public_path('images/edubridge_icon.png')) }}" class="logo" alt="EduBridge" style="height: 45px; border-radius: 8px;">
@else
<img src="{{ asset('images/edubridge_icon.png') }}" class="logo" alt="EduBridge" style="height: 45px; border-radius: 8px;">
@endisset
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
