<tr>
    <td>
        <span style="padding-left: {{ $level * 25 }}px;">{{ $category->name }}</span>
    </td>

    @foreach ($units as $unit)
        <td class="text-center">
            @php
                $key = $category->id . '-' . $unit->id;
                $permission = $permissions[$key] ?? null;
            @endphp
            <div class="d-flex justify-content-center align-items-center">
                {{-- Checkbox untuk BACA --}}
                <div class="form-check form-check-inline mx-1">
                    <input class="form-check-input permission-check" type="checkbox" 
                        id="read-{{$key}}"
                        data-unit-id="{{ $unit->id }}"
                        data-category-id="{{ $category->id }}"
                        data-permission-type="read"
                        {{ ($permission && $permission->can_read) ? 'checked' : '' }}
                        title="Baca">
                </div>
                {{-- Checkbox untuk TULIS --}}
                <div class="form-check form-check-inline mx-1">
                    <input class="form-check-input permission-check" type="checkbox"
                        id="write-{{$key}}"
                        data-unit-id="{{ $unit->id }}"
                        data-category-id="{{ $category->id }}"
                        data-permission-type="write"
                        {{ ($permission && $permission->can_write) ? 'checked' : '' }}
                        title="Tulis">
                </div>

                {{-- Link untuk PENGECUALIAN --}}
                <a href="#" class="ms-2 exception-btn small" 
                    data-bs-toggle="modal" 
                    data-bs-target="#exceptionModal"
                    data-unit-id="{{ $unit->id }}"
                    data-category-id="{{ $category->id }}"
                    data-unit-name="{{ $unit->name }}"
                    data-category-name="{{ $category->name }}"
                    title="Atur Pengecualian">
                    Atur Pengecualian
                </a>
            </div>
        </td>
    @endforeach
</tr>

{{-- Panggil diri sendiri untuk sub-kategori --}}
@if ($category->children->isNotEmpty())
    @foreach ($category->children as $child)
        @include('permissions._category-row', [
            'category' => $child, 
            'units' => $units, 
            'level' => $level + 1,
            'permissions' => $permissions
        ])
    @endforeach
@endif