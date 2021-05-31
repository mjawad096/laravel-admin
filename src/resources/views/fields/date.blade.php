<div class="form-group {{ $errors->has($name) ? 'error' : '' }}">
    <label for="input-{{ $name }}">{{ $title }}</label>
    <div class="controls">
        
        @php
            $value = old($name) ?? $item->{$name} ?? $default_value;
            if($value){
                $value = (new Carbon\Carbon($value))->format('Y-m-d');
            }
        @endphp

        <input type="{{ $type ?? 'text' }}" id="input-{{ $name }}" class="form-control" name="{{ $name }}" placeholder="{{ $title }}" value="{{ $value }}">
        
        @error($name)
            <div class="help-block">
                <ul role="alert">
                    <li>{{ $message }}</li>
                </ul>
            </div>
        @enderror
    </div>
</div>