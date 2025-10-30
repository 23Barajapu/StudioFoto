@extends('layouts.dashboard')

@section('title', 'Detail Paket - Prime Studio')
@section('page-title', 'Detail Paket')

@section('dashboard-content')
<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Package Image -->
        <div class="md:w-1/3">
            @if($package->image)
                <img src="{{ asset('storage/' . $package->image) }}" alt="{{ $package->name }}" class="w-full h-auto rounded-lg">
            @else
                <div class="w-full h-64 gradient-bg rounded-lg flex items-center justify-center">
                    <i class="fas fa-camera text-white text-5xl"></i>
                </div>
            @endif
        </div>
        
        <!-- Package Details -->
        <div class="md:w-2/3">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $package->name }}</h2>
            <p class="text-gray-600 mb-6">{{ $package->description }}</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-700 mb-2">Informasi Paket</h4>
                    <ul class="space-y-2">
                        <li class="flex justify-between">
                            <span class="text-gray-600">Harga:</span>
                            <span class="font-medium">{{ $package->formatted_price }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-600">Durasi:</span>
                            <span class="font-medium">{{ $package->duration_hours }} jam</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-600">Jumlah Foto:</span>
                            <span class="font-medium">{{ $package->photo_count }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-600">Jumlah Foto Edit:</span>
                            <span class="font-medium">{{ $package->edited_photo_count }}</span>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-700 mb-2">Fasilitas</h4>
                    <ul class="space-y-2">
                        <li class="flex items-center">
                            <i class="fas {{ $package->include_makeup ? 'fa-check text-green-500' : 'fa-times text-red-500' }} mr-2"></i>
                            <span>Makeup</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas {{ $package->include_outfit ? 'fa-check text-green-500' : 'fa-times text-red-500' }} mr-2"></i>
                            <span>Outfit</span>
                        </li>
                        @if($package->features)
                            @foreach(json_decode($package->features) as $feature)
                                <li class="flex items-center">
                                    <i class="fas fa-check text-green-500 mr-2"></i>
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
            
            <div class="flex flex-wrap gap-4">
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('packages.edit', $package) }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-edit mr-2"></i>Edit Paket
                        </a>
                    @endif
                @endauth
                
                <a href="{{ route('packages.index') }}" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>
    
    <!-- Recent Bookings -->
    <div class="mt-12">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Pemesanan Terbaru</h3>
        @if($package->bookings_count > 0)
            <div class="bg-gray-50 rounded-lg p-4">
                <ul class="divide-y">
                    @foreach($package->bookings as $booking)
                        <li class="py-3">
                            <div class="flex justify-between">
                                <div>
                                    <p class="font-medium">{{ $booking->customer_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $booking->booking_date->format('d M Y') }} - {{ $booking->booking_time }}</p>
                                </div>
                                <div>
                                    <span class="px-3 py-1 rounded-full text-xs {{ $booking->status == 'confirmed' ? 'bg-green-100 text-green-800' : ($booking->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $booking->status_label }}
                                    </span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">Belum ada pemesanan untuk paket ini</p>
            </div>
        @endif
    </div>
</div>
@endsection
