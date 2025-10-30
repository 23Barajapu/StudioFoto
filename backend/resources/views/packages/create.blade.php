@extends('layouts.dashboard')

@section('title', 'Tambah Paket - Prime Studio')
@section('page-title', 'Tambah Paket Baru')

@section('dashboard-content')
<div class="bg-white rounded-xl shadow-md p-6 max-w-3xl mx-auto">
    <form action="{{ route('packages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Nama Paket -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Paket</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Harga -->
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                <input type="number" id="price" name="price" value="{{ old('price') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    required>
                @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Durasi -->
            <div>
                <label for="duration_hours" class="block text-sm font-medium text-gray-700 mb-1">Durasi (jam)</label>
                <input type="number" id="duration_hours" name="duration_hours" value="{{ old('duration_hours') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    required>
                @error('duration_hours')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Jumlah Foto -->
            <div>
                <label for="photo_count" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Foto</label>
                <input type="number" id="photo_count" name="photo_count" value="{{ old('photo_count') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    required>
                @error('photo_count')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Jumlah Foto Edit -->
            <div>
                <label for="edited_photo_count" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Foto Edit</label>
                <input type="number" id="edited_photo_count" name="edited_photo_count" value="{{ old('edited_photo_count') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    required>
                @error('edited_photo_count')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Deskripsi -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea id="description" name="description" rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Gambar -->
        <div class="mb-6">
            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Gambar Paket</label>
            <input type="file" id="image" name="image" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, JPEG. Maks: 2MB</p>
            @error('image')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('packages.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" class="px-6 py-2 gradient-bg text-white rounded-lg hover:opacity-90">
                Simpan Paket
            </button>
        </div>
    </form>
</div>
@endsection
