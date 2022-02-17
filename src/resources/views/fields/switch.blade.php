<input type="hidden" name="{{ $name }}" value="0">
<div class="custom-control custom-switch mr-2 mb-1">
    <input type="checkbox" id="input-{{ $name }}" class="custom-control-input" name="{{ $name }}" value="1" {{ (old($name) ?? $item->{$name} ?? $default_value) ? 'checked' : '' }}>
    <label class="custom-control-label" for="input-{{ $name }}"></label>
</div>