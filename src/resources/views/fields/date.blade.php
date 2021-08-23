@php
    $value = old($name) ?? $item->{$name} ?? $default_value;
    if($value){
        $value = (new Carbon\Carbon($value))->format('Y-m-d');
    }
@endphp

<input type="{{ $type ?? 'text' }}" id="input-{{ $name }}" class="form-control" name="{{ $name }}" placeholder="{{ $title }}" value="{{ $value }}">
