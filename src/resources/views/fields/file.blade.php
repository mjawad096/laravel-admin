<div class="form-group {{ $errors->has($name) ? 'error' : '' }}">
    <label for="input-{{ $name }}">{{ $title }}</label>
    <div class="controls">
        <livewire:media::temp-file-upload-component :name="$name" :config="['accept' => ($accept ?? ($type == 'image' ? 'image/*' : '*'))]" :maxFiles="$maxFiles ?? 10" :totalFiles="$editing_form ? $item->getMedia($name)->count() : null"/>

        @error($name)
            <div class="help-block">
                <ul role="alert">
                    <li>{{ $message }}</li>
                </ul>
            </div>
        @enderror

        @if($editing_form)
            <livewire:media::file-preview-component :model="$item" :collection="$name" />
        @endif
    </div>
</div>