@extends('layouts.admin')

@section('title', 'Product Categories')

@section('content')
<div x-data="{
    showCreate: false,
    editingId: null,
    deleteCategory(id) {
        if(confirm('Are you sure? This might affect products in this category.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
}" class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-xl font-semibold text-content-primary">Categories</h1>
            <p class="text-xs text-content-disabled mt-1">Organize your boutique collection.</p>
        </div>

        <x-ui.button variant="primary" @click="showCreate = !showCreate">
            <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
            Add Category
        </x-ui.button>
    </div>

    {{-- Alert Component --}}
    @if(session('success'))
        <div class="mb-6">
            <x-ui.alert type="success" :message="session('success')" />
        </div>
    @endif

    @if ($errors->any())
    <div class="mb-6">
        <x-ui.alert type="danger" title="Action Failed">
            <ul class="mt-1.5 ml-4 list-disc list-outside text-[11px] opacity-85 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-ui.alert>
    </div>
    @endif

    <div class="bg-bg-surface border border-border-subtle rounded-lg overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-bg-elevated/50 border-b border-border-subtle">
                    <th class="px-6 py-3 text-[11px] font-bold uppercase tracking-wider text-content-disabled">Category Name</th>
                    <th class="px-6 py-3 text-[11px] font-bold uppercase tracking-wider text-content-disabled text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-subtle">
                {{-- Fila de Creación Inline --}}
                <tr x-show="showCreate" x-transition class="bg-accent-muted/20">
                    <td colspan="2" class="px-6 py-4">
                        <form
                        {{-- action="{{ route('admin.categories.store') }}"  --}}
                        method="POST" class="flex items-center gap-3">
                            @csrf
                            <input type="text" name="name" placeholder="Category name..."
                                class="flex-1 px-3 py-1.5 text-sm bg-bg-surface border border-border-subtle rounded-md focus:ring-1 focus:ring-accent outline-none">
                            <x-ui.button type="submit" variant="primary" class="py-1.5 px-3 text-xs">Save</x-ui.button>
                            <button type="button" @click="showCreate = false" class="text-xs text-content-disabled hover:text-content-primary">Cancel</button>
                        </form>
                    </td>
                </tr>

                @foreach($categories as $category)
                    <tr class="group hover:bg-bg-elevated/30 transition-colors">
                        <td class="px-6 py-4">
                            {{-- Vista Normal --}}
                            <div x-show="editingId !== {{ $category->id }}" class="flex items-center">
                                <span class="text-sm font-medium text-content-primary">{{ $category->name }}</span>
                            </div>

                            {{-- Vista Edición Inline --}}
                            <div x-show="editingId === {{ $category->id }}" x-cloak>
                                <form id="edit-form-{{ $category->id }}"
                                    action="{{ route('admin.categories.update', $category) }}"
                                     method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" value="{{ $category->name }}"
                                        class="px-2 py-1 text-sm bg-bg-surface border border-accent rounded outline-none w-full">
                                </form>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-right">
                            {{-- Botones Normales --}}
                            <div x-show="editingId !== {{ $category->id }}" class="flex justify-end gap-3">
                                <button @click="editingId = {{ $category->id }}" class="text-content-secondary hover:text-accent transition-colors hover:cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>

                                <button @click="deleteCategory({{ $category->id }})" class="text-content-secondary hover:text-danger transition-colors hover:cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>

                                <form id="delete-form-{{ $category->id }}"
                                    action="{{ route('admin.categories.destroy', $category) }}"
                                     method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>

                            {{-- Botones Guardar Edición --}}
                            <div x-show="editingId === {{ $category->id }}" x-cloak class="flex justify-end gap-2">
                                <button form="edit-form-{{ $category->id }}" type="submit" class="text-[11px] font-bold text-state-success uppercase hover:underline">
                                    Update
                                </button>
                                <button @click="editingId = null" class="text-[11px] font-bold text-content-disabled uppercase hover:underline">
                                    Cancel
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
