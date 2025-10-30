@extends('layouts.dashboard')

@section('title', 'Daftar Paket - Prime Studio')
@section('page-title', 'Daftar Paket')

@section('dashboard-content')
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-800">Daftar Paket Foto</h3>
            @auth
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('packages.create') }}" class="px-4 py-2 gradient-bg text-white rounded-lg hover:opacity-90">
                        <i class="fas fa-plus mr-2"></i>Tambah Paket
                    </a>
                @endif
            @endauth
        </div>
        
        <!-- Packages Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($packages as $package)
                <div class="bg-white rounded-xl shadow-md overflow-hidden card-hover">
                    @if($package->image)
                        <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 gradient-bg flex items-center justify-center">
                            <i class="fas fa-camera text-white text-4xl"></i>
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $package->name }}</h4>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $package->description }}</p>
                        
                        <div class="space-y-2 mb-4 text-sm">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-clock w-5"></i>
                                <span>{{ $package->duration_hours }} jam sesi</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-camera w-5"></i>
                                <span>{{ $package->photo_count }} foto</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-edit w-5"></i>
                                <span>{{ $package->edited_photo_count }} foto edit</span>
                            </div>
                        </div>
                        
                        <div class="border-t pt-4 mb-4">
                            <div class="text-2xl font-bold text-indigo-600">
                                {{ $package->formatted_price }}
                            </div>
                            @if(!$package->is_active)
                                <div class="mt-3 px-3 py-1 bg-red-100 text-red-700 text-xs rounded text-center">
                                    Tidak Aktif
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex space-x-3">
                            <a href="{{ route('packages.show', $package) }}" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 text-center rounded-lg hover:bg-gray-50">
                                Detail
                            </a>
                            @auth
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('packages.edit', $package) }}" class="flex-1 px-4 py-2 border border-indigo-600 text-indigo-600 text-center rounded-lg hover:bg-indigo-50">
                                        Edit
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <div class="text-gray-500">Tidak ada paket tersedia.</div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
