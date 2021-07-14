<div class="form-group {{ $errors->has('status') ? 'error' : '' }}">
    <label for="input-status">Status</label>

    @php
        $status = old('status') !== null ? old('status') : ($item->status ?? 1);
    @endphp
    <select class="form-control" name="status" id="input-status" required>
        <option value="1" {{ $status == 1 ? 'selected' : '' }}>Active</option>
        <option value="0" {{ $status == 0 ? 'selected' : '' }}>Inactive</option>
    </select>
</div>