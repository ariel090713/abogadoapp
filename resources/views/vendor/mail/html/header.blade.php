@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel' || trim($slot) === config('app.name'))
<div style="text-align: center;">
    <div style="font-size: 32px; font-weight: 700; color: #1E3A8A; margin-bottom: 4px; letter-spacing: -0.5px;">
        AbogadoMo
    </div>
    <div style="font-size: 11px; font-weight: 500; color: #B91C1C; letter-spacing: 2px; text-transform: uppercase;">
        Legal Consultations
    </div>
</div>
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
