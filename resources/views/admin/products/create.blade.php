@extends('layouts.admin')

@section('title', 'Nuevo producto')

@section('content')

    {{-- Breadcrumb --}}
    <nav class="text-xs text-content-disabled mb-2">
        <a href="{{ route('dashboard') }}" class="hover:text-content-secondary transition-colors">Admin</a>
        <span class="mx-1.5">/</span>
        <a href="{{ route('admin.products.index') }}" class="hover:text-content-secondary transition-colors">Productos</a>
        <span class="mx-1.5">/</span>
        <span class="text-content-secondary">Nuevo producto</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-medium text-content-primary">Nuevo producto</h1>
        <span class="inline-flex items-center gap-1.5 text-[11px] font-medium px-2.5 py-1 rounded-full bg-accent-muted text-accent border border-accent-border">
            <span class="w-1.5 h-1.5 rounded-full bg-accent"></span>
            Sin guardar
        </span>
    </div>

    <form
        action="{{ route('admin.products.store') }}"
        method="POST"
        enctype="multipart/form-data"
        id="product-form"
    >
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_300px] gap-5 items-start">

            {{-- Columna principal --}}
            <div class="flex flex-col gap-5">

                {{-- Información general --}}
                <div class="bg-bg-surface border border-border-subtle rounded-lg p-5">
                    <h2 class="text-sm font-medium text-content-primary pb-3 mb-4 border-b border-border-subtle">
                        Información general
                    </h2>

                    {{-- Nombre --}}
                    <div class="mb-4">
                        <x-ui.input
                            name="name"
                            label="Nombre"
                            placeholder="Ej. Vestido floral talla M"
                            hint="Sé específico: incluye tipo de prenda y talla."
                            :value="old('name')"
                            :error="$errors->first('name')"
                            required
                        />
                    </div>

                    {{-- Descripción --}}
                    <div class="mb-4">
                        <label for="description" class="block text-xs font-medium text-content-secondary mb-1.5">
                            Descripción
                            <span class="text-[10px] text-content-disabled font-normal ml-1">opcional</span>
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="Materiales, estado de la prenda, detalles relevantes..."
                            class="w-full px-3 py-2 text-sm bg-bg-elevated border border-border-subtle rounded-md text-content-primary placeholder:text-content-disabled outline-none resize-y transition-all duration-150 hover:border-border-mid focus:border-accent-border focus:ring-2 focus:ring-accent-muted {{ $errors->has('description') ? 'border-[rgba(239,68,68,0.5)]' : '' }}"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <span class="text-xs text-state-danger mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Precio y Estado --}}
                    <div class="grid grid-cols-2 gap-3 mb-4">

                        {{-- Precio --}}
                        <div>
                            <label for="price" class="block text-xs font-medium text-content-secondary mb-1.5">
                                Precio <span class="text-accent">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-content-disabled pointer-events-none">$</span>
                                <input
                                    id="price"
                                    name="price"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    placeholder="0.00"
                                    value="{{ old('price') }}"
                                    class="w-full pl-6 pr-3 py-2 text-sm bg-bg-elevated border border-border-subtle rounded-md text-content-primary placeholder:text-content-disabled outline-none transition-all duration-150 hover:border-border-mid focus:border-accent-border focus:ring-2 focus:ring-accent-muted {{ $errors->has('price') ? 'border-[rgba(239,68,68,0.5)]' : '' }}"
                                    required
                                />
                            </div>
                            @error('price')
                                <span class="text-xs text-state-danger mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Estado --}}
                        <x-ui.select
                            name="status"
                            label="Estado"
                            :selected="old('status')"
                            :error="$errors->first('status')"
                            placeholder="Selecciona"
                            :options="[
                                'available' => 'Disponible',
                                'low_stock' => 'Últimas piezas',
                                'reserved'  => 'Reservado',
                                'raffle'    => 'En rifa',
                                'sold'      => 'Vendido',
                            ]"
                        />

                    </div>

                    {{-- Categoría --}}
                    <x-ui.select
                        name="category_id"
                        label="Categoría"
                        placeholder="Selecciona una categoría"
                        :selected="old('category_id')"
                        :error="$errors->first('category_id')"
                        :options="$categories->pluck('name', 'id')"
                    />

                </div>

                {{-- Imágenes --}}
                <div class="bg-bg-surface border border-border-subtle rounded-lg p-5">
                    <h2 class="text-sm font-medium text-content-primary pb-3 mb-4 border-b border-border-subtle">
                        Imágenes del producto
                    </h2>

                    {{-- Zona de drop --}}
                    <div
                        id="drop-zone"
                        class="border border-dashed border-border-mid rounded-lg p-6 text-center cursor-pointer transition-all duration-150 hover:border-accent-border hover:bg-accent-muted"
                        onclick="document.getElementById('images-input').click()"
                    >
                        <svg class="w-9 h-9 mx-auto mb-3 text-content-disabled" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                        <p class="text-sm text-content-secondary mb-1">
                            Arrastra imágenes o <span class="text-accent">selecciona archivos</span>
                        </p>
                        <p class="text-xs text-content-disabled">
                            JPG, PNG o WEBP · Máx. 2MB por imagen · Hasta 3 imágenes
                        </p>
                    </div>

                    <input
                        id="images-input"
                        name="images[]"
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        multiple
                        class="hidden"
                    />

                    @error('images')
                        <span class="text-xs text-state-danger mt-2 block">{{ $message }}</span>
                    @enderror
                    @error('images.*')
                        <span class="text-xs text-state-danger mt-2 block">{{ $message }}</span>
                    @enderror

                    {{-- Preview de imágenes --}}
                    <div id="preview-grid" class="grid grid-cols-3 gap-2 mt-3"></div>
                    <p id="preview-counter" class="text-[11px] text-content-disabled text-right mt-2 hidden">
                        <span id="preview-count">0</span> / 3 imágenes
                    </p>

                </div>

            </div>

            {{-- Columna lateral --}}
            <div class="bg-bg-surface border border-border-subtle rounded-lg p-5">
                <h2 class="text-sm font-medium text-content-primary pb-3 mb-4 border-b border-border-subtle">
                    Publicar
                </h2>

                <div class="flex flex-col gap-2">
                    <x-ui.button variant="primary" type="submit" class="w-full justify-center">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Guardar producto
                    </x-ui.button>
                    <x-ui.button variant="ghost" :href="route('admin.products.index')" class="w-full justify-center">
                        Cancelar
                    </x-ui.button>
                </div>

                <p class="text-[11px] text-content-disabled leading-relaxed mt-4 pt-4 border-t border-border-subtle">
                    El producto será visible en el catálogo público inmediatamente después de guardarse.
                </p>
            </div>

        </div>

    </form>

@endsection

@push('scripts')
<script>
    const input       = document.getElementById('images-input');
    const dropZone    = document.getElementById('drop-zone');
    const previewGrid = document.getElementById('preview-grid');
    const counter     = document.getElementById('preview-counter');
    const countLabel  = document.getElementById('preview-count');
    const MAX         = 3;

    let files = [];

    input.addEventListener('change', () => handleFiles(input.files));

    dropZone.addEventListener('dragover', e => {
        e.preventDefault();
        dropZone.classList.add('border-accent-border', 'bg-accent-muted');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-accent-border', 'bg-accent-muted');
    });

    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('border-accent-border', 'bg-accent-muted');
        handleFiles(e.dataTransfer.files);
    });

    function handleFiles(incoming) {
        const allowed = ['image/jpeg', 'image/png', 'image/webp'];
        const maxSize = 2 * 1024 * 1024;

        Array.from(incoming).forEach(file => {
            if (files.length >= MAX) return;
            if (!allowed.includes(file.type)) return;
            if (file.size > maxSize) return;
            files.push(file);
        });

        syncInput();
        renderPreviews();
    }

    function removeFile(index) {
        files.splice(index, 1);
        syncInput();
        renderPreviews();
    }

    function syncInput() {
        const dt = new DataTransfer();
        files.forEach(f => dt.items.add(f));
        input.files = dt.files;
    }

    function renderPreviews() {
        previewGrid.innerHTML = '';

        files.forEach((file, i) => {
            const url  = URL.createObjectURL(file);
            const item = document.createElement('div');
            item.className = 'relative aspect-square rounded-lg overflow-hidden border border-border-subtle bg-bg-elevated';
            item.innerHTML = `
                <img src="${url}" class="w-full h-full object-cover" />
                <button
                    type="button"
                    onclick="removeFile(${i})"
                    class="absolute top-1.5 right-1.5 w-5 h-5 rounded-full bg-black/70 flex items-center justify-center text-white border-none cursor-pointer"
                >
                    <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            `;
            previewGrid.appendChild(item);
        });

        const empty = MAX - files.length;
        for (let i = 0; i < empty; i++) {
            const slot = document.createElement('div');
            slot.className = 'aspect-square rounded-lg border border-dashed border-border-subtle flex items-center justify-center';
            slot.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3A3A3A" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>`;
            previewGrid.appendChild(slot);
        }

        countLabel.textContent = files.length;
        counter.classList.toggle('hidden', files.length === 0);
    }
</script>
@endpush
