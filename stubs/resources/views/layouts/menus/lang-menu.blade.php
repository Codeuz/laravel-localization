@foreach(config('localization.supported_locales') as $locale => $lang)
@if ($locale != config('app.locale'))
<x-dropdowns.dropdown-link hreflang="{{ $locale }}" href="{{ localization()->currentRoute($locale) }}">{{ $lang['native'] }}</x-dropdowns.dropdown-link>
@endif
@endforeach
