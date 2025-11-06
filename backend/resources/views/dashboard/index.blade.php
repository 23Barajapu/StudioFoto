@extends('layouts.dashboard')

@section('title', 'Dashboard - Prime Studio')
@section('page-title', 'Dashboard')

@section('dashboard-content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Pendapatan Card -->
        <div class="bg-white rounded-xl shadow-md p-6 card-hover">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-2">Pendapatan</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['formatted_revenue'] }}</h3>
                    <div class="mt-4">
                        <a href="#" class="text-indigo-600 text-sm font-semibold flex items-center">
                            Lihat pendapatan <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-invoice text-indigo-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <i class="fas fa-arrow-up text-green-500 text-sm mr-1"></i>
                <span class="text-green-500 text-sm font-semibold">12%</span>
                <span class="text-gray-500 text-sm ml-2">Naik</span>
            </div>
        </div>

        <!-- Riwayat Pemesanan Card -->
        <div class="bg-white rounded-xl shadow-md p-6 card-hover">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-2">Riwayat Pemesanan</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['total_bookings'] }}</h3>
                    <p class="text-gray-500 text-sm mt-1">Ada Riwayat baru</p>
                    <div class="mt-4">
                        <a href="{{ route('bookings.index') }}" class="text-indigo-600 text-sm font-semibold flex items-center">
                            Lihat <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Kelola Pembayaran Card -->
        <div class="bg-white rounded-xl shadow-md p-6 card-hover">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-500 text-sm mb-2">Kelola Pembayaran</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $stats['pending_payments'] }}</h3>
                    <p class="text-gray-500 text-sm mt-1">Pembayaran baru</p>
                    <div class="mt-4">
                        <a href="{{ route('payments.index') }}" class="text-indigo-600 text-sm font-semibold flex items-center">
                            Lihat <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center">
                <i class="fas fa-exclamation-circle text-orange-500 text-sm mr-1"></i>
                <span class="text-orange-500 text-sm font-semibold">Perlu perhatian</span>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Daftar Booking Terbaru</h3>
            <a href="{{ route('bookings.index') }}" class="text-indigo-600 text-sm font-semibold">Lihat Semua</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jml Orang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Pembayaran</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentBookings as $booking)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $booking->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $booking->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->booking_date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ date('H:i', strtotime($booking->booking_time)) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($booking->package)
                                    {{ $booking->package->name }}
                                @else
                                    <span class="text-red-500">Paket tidak ditemukan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $booking->formatted_price }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">1</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @php
                                    $statusClass = match($booking->status) {
                                        'paid', 'completed' => 'status-lunas',
                                        'confirmed' => 'status-dp',
                                        default => 'status-belum'
                                    };
                                    $statusText = match($booking->status) {
                                        'paid', 'completed' => 'Lunas',
                                        'confirmed' => 'DP',
                                        default => 'Belum Lunas'
                                    };
                                @endphp
                                <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Belum ada data booking</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
