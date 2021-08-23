@php
    $multiple = $multiple ?? false;
    $selected = old($name) !== null ? old($name) : ($item->{$name} ?? $default_value) ;
    
    if($multiple){
        if(empty($selected)){
            $selected = [];
        }

        if(is_array($selected)){
            $selected = collect($selected);
        }

        $selected->pluck('id');
    }
@endphp
<select class="form-control" name="{{ $name }}{{ $multiple ? '[]' : '' }}" id="input-{{ $name }}" {{ $multiple ? 'multiple' : '' }}>
    @if(!$multiple)
        <option value="" selected>Select {{ suggest_a_an($title) }} {{ strtolower($title) }}</option>
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