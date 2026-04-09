@extends('layouts.admin')

@section('title', 'Edit Product: ' . $product->name)

@section('content')

    {{-- Breadcrumb --}}
    <nav class="text-xs text-content-disabled mb-2">
        <a href="{{ route('dashboard') }}" class="hover:text-content-secondary transition-colors">Admin</a>
        <span class="mx-1.5">/</span>
        <a href="{{ route('admin.products.index') }}" class="hover:text-content-secondary transition-colors">Products</a>
        <span class="mx-1.5">/</span>
        <span class="text-content-secondary">Edit Product</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-medium text-content-primary">Edit: {{ $product->name }}</h1>
        <a href="{{ route('admin.products.show', $product) }}" class="text-[11px] font-medium text-accent hover:underline">
            View Details
        </a>
    </div>

    <div class="mb-6 flex flex-col gap-3">

        {{-- Notificación de Éxito al crear o actualizar --}}
        @if (session('status') || session('success'))
            <x-ui.alert
                type="success"
                title="Action Successful"
                :message="session('status') ?? session('success')"
            />
        @endif

        {{-- Notificación de Errores de Validación --}}
        @if ($errors->any())
            <x-ui.alert
                type="danger"
                title="Validation Error"
            >
                {{-- Usamos el slot para listar los errores de forma elegante --}}
                <ul class="mt-1.5 ml-4 list-disc list-outside text-[11px] opacity-85 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-ui.alert>
        @endif

    </div>

    <form
        action="{{ route('admin.products.update', $product) }}"
        method="POST"
        enctype="multipart/form-data"
        id="product-form"
    >
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_300px] gap-5 items-start">

            {{-- main column --}}
            <div class="flex flex-col gap-5">

                {{-- info --}}
                <div class="bg-bg-surface border border-border-subtle rounded-lg p-5">
                    <h2 class="text-sm font-medium text-content-primary pb-3 mb-4 border-b border-border-subtle">
                        Main Data
                    </h2>

                    <div class="mb-4">
                        <x-ui.input
                            name="name"
                            label="Product Name"
                            :value="old('name', $product->name)"
                            :error="$errors->first('name')"
                            required
                        />
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-xs font-medium text-content-secondary mb-1.5">
                            Description
                            <span class="text-[10px] text-content-disabled font-normal ml-1">optional</span>
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="Materials, state of cloth, important details..."
                            class="w-full px-3 py-2 text-sm bg-bg-elevated border border-border-subtle rounded-md text-content-primary placeholder:text-content-disabled outline-none resize-y transition-all duration-150 hover:border-border-mid focus:border-accent-border focus:ring-2 focus:ring-accent-muted {{ $errors->has('description') ? 'border-[rgba(239,68,68,0.5)]' : '' }}"
                        >{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <span class="text-xs text-state-danger mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div>
                            <label for="price" class="block text-xs font-medium text-content-secondary mb-1.5">
                                Price <span class="text-accent">*</span>
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
                                    value="{{ old('price', $product->price) }}"
                                    class="w-full pl-6 pr-3 py-2 text-sm bg-bg-elevated border border-border-subtle rounded-md text-content-primary placeholder:text-content-disabled outline-none transition-all duration-150 hover:border-border-mid focus:border-accent-border focus:ring-2 focus:ring-accent-muted {{ $errors->has('price') ? 'border-[rgba(239,68,68,0.5)]' : '' }}"
                                    {{-- required --}}
                                />
                            </div>
                            @error('price')
                                <span class="text-xs text-state-danger mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <x-ui.select
                            name="status"
                            label="Status"
                            :selected="old('status', $product->status)"
                            :options="[
                                'available' => 'Available',
                                'reserved'  => 'Reserved',
                                'raffle'    => 'In Raffle',
                                'sold'      => 'Sold',
                            ]"
                        />
                    </div>

                    <x-ui.select
                        name="category_id"
                        label="Category"
                        :selected="old('category_id', $product->category_id)"
                        :options="$categories->pluck('name', 'id')"
                    />
                </div>

                {{-- current Images--}}
                <div class="bg-bg-surface border border-border-subtle rounded-lg p-5">
                    <h2 class="text-sm font-medium text-content-primary pb-3 mb-4 border-b border-border-subtle">
                        Current Images
                    </h2>

                    <div class="grid grid-cols-3 gap-3 mb-6">
                        @foreach($product->images as $image)
                            <div class="relative aspect-square rounded-lg overflow-hidden border border-border-subtle group">
                                {{-- <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-full object-cover"> --}}
                                <img src="{{ asset('storage/' . ($image->path)) }}"
                                            onerror="this.onerror=null; this.src='{{ asset('storage/defaults/no-image.jpeg') }}';"
                                            class="w-full h-full object-cover">

                                <div class="absolute top-2 right-2">
                                    <input
                                        type="checkbox"
                                        name="delete_images[]"
                                        value="{{ $image->id }}"
                                        class="w-4 h-4 rounded border-gray-300 text-accent focus:ring-accent"
                                        title="Mark to delete"
                                    >
                                </div>

                                @if($image->is_primary)
                                    <span class="absolute bottom-2 left-2 bg-accent text-white text-[9px] px-1.5 py-0.5 rounded uppercase font-bold">Primary</span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- upload new images --}}
                    <p class="text-xs font-medium text-content-secondary mb-3">Add more images (Max. total: 3)</p>
                    <div
                        id="drop-zone"
                        class="border border-dashed border-border-mid rounded-lg p-6 text-center cursor-pointer hover:bg-accent-muted transition-colors"
                        onclick="document.getElementById('images-input').click()"
                    >
                        <p class="text-xs text-content-disabled">Click to upload new photos</p>
                    </div>
                    <input id="images-input" name="images[]" type="file" multiple class="hidden" accept="image/*" />

                    <div id="preview-grid" class="grid grid-cols-3 gap-2 mt-3"></div>
                </div>

            </div>

            {{-- Actions --}}
            <div class="bg-bg-surface border border-border-subtle rounded-lg p-5 sticky top-5">
                <h2 class="text-sm font-medium text-content-primary pb-3 mb-4 border-b border-border-subtle">
                    Publish Changes
                </h2>

                <div class="flex flex-col gap-2">
                    <x-ui.button variant="primary" type="submit" class="w-full justify-center">
                        Update Product
                    </x-ui.button>
                    <x-ui.button variant="ghost" :href="route('admin.products.show', $product)" class="w-full justify-center text-content-disabled">
                        Discard Changes
                    </x-ui.button>
                </div>

                <div class="mt-6 pt-4 border-t border-border-subtle">
                    <p class="text-[10px] text-content-disabled uppercase font-bold mb-2">History</p>
                    <p class="text-[10px] text-content-secondary">Created: {{ $product->created_at->format('d/m/Y H:i') }}</p>
                    <p class="text-[10px] text-content-secondary">Last Update: {{ $product->updated_at->diffForHumans() }}</p>
                </div>
            </div>

        </div>
    </form>

@endsection

{{-- Reutilizamos el script de preview de la vista Create --}}
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
        const allowed = ['image/jpeg', 'image/png', 'image/jpg'];
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
