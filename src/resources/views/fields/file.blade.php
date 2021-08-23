<livewire:media::temp-file-upload-component :name="$name" :config="['accept' => ($accept ?? ($type == 'image' ? 'image/*' : '*'))]" :maxFiles="$maxFiles ?? 10" :totalFiles="$editing_form ? $item->getMedia($name)->count() : null"/>
@if($editing_form)
    <livewire:media::file-preview-component :model="$item" :collection="$name" />
@endif