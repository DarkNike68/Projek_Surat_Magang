@props(['category', 'parentAccordionId' => '#categoryAccordion'])

<div class="accordion-item">
    <h2 class="accordion-header d-flex align-items-center" id="heading-{{ $category->id }}">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $category->id }}" aria-expanded="false" aria-controls="collapse-{{ $category->id }}">
            <strong>{{ $category->name }}</strong>
        </button>
        
        {{-- Tombol Edit/Hapus Kategori hanya untuk BAU Admin --}}
        @can('view-admin-menu')
            <div class="p-2">
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-warning btn-sm">Edit</a>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kategori ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                </form>
            </div>
        @endcan
    </h2>

    <div id="collapse-{{ $category->id }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $category->id }}" data-bs-parent="{{ $parentAccordionId }}">
        <div class="accordion-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Daftar Dokumen di Kategori Ini</h5>
                {{-- Tombol Tambah Dokumen dibungkus dengan @can('update', $category) --}}
                @can('update', $category)
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal" data-category-id="{{ $category->id }}">
                        Tambah Dokumen
                    </button>
                @endcan
            </div>
            <table class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th>Nama Dokumen</th>
                        <th>Uploader</th>
                        <th>Tgl Upload</th>
                        {{-- Cek dinamis untuk menampilkan kolom Aksi --}}
                        @php $canUpdate = Gate::allows('update', $category); @endphp
                        @if($canUpdate)
                            <th style="width: 20%;">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($category->documents as $document)
                        <tr>
                            <td><a href="{{ route('admin.documents.download', $document) }}">{{ $document->name }}</a><small class="d-block text-muted">{{ $document->description }}</small></td>
                            <td>{{ $document->user->name }}</td>
                            <td>{{ $document->created_at->format('d M Y') }}</td>
                            
                            {{-- Tombol Edit/Hapus Dokumen dibungkus dengan kondisi --}}
                            @if($canUpdate)
                                <td class="d-flex">
                                    <button type="button" class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editModal" data-document-id="{{ $document->id }}" data-document-name="{{ $document->name }}" data-document-description="{{ $document->description }}">Edit</button>
                                    <form action="{{ route('admin.documents.destroy', $document) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus dokumen ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="{{ $canUpdate ? 4 : 3 }}" class="text-center text-muted">Belum ada dokumen di kategori ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
            
            @if ($category->children->isNotEmpty())
                <hr class="my-4">
                <h6 class="mb-3">Sub-Kategori:</h6>
                <div class="accordion" id="subAccordion-{{ $category->id }}">
                    @foreach ($category->children as $child)
                        <x-category-item :category="$child" :parentAccordionId="'#subAccordion-'.$category->id" />
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>