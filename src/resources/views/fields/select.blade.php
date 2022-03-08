@php
    $options = $options ?? [];
    
    $multiple = $multiple ?? false;
    $selected = old($name) !== null ? old($name) : ($item->{$name} ?? $default_value) ;
    
    if($multiple && !is_collection($selected)){
        $selected = collect($selected);
    }
@endphp

<select class="form-control" name="{{ $name }}{{ $multiple ? '[]' : '' }}" id="input-{{ $name }}" {{ $multiple ? 'multiple' : '' }}>
    @if(!$multiple)
        <option value="" selected>{{ $placeholder }}</option>
    @endif

    @foreach($options as $option)
        <option 
            value="{{ $option['value'] }}"
            @if($multiple)
                {{ $selected->contains($option['value']) ? 'selected' : '' }}
            @else
                {{ $selected == $option['value'] ? 'selected' : '' }}
            @endif
        >
            {{ $option['text'] }}
        </option>
    @endforeach
</select>