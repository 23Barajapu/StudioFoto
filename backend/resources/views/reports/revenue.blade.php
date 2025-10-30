@extends('layouts.dashboard')

@section('title', 'Laporan Pendapatan - Prime Studio')
@section('page-title', 'Laporan Pendapatan')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('dashboard-content')
<div class="bg-white rounded-xl shadow-md p-6">
    <!-- Filter Form -->
    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
        <form action="{{ route('reports.revenue') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="text" id="start_date" name="start_date" value="{{ $startDate }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 datepicker">
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="text" id="end_date" name="end_date" value="{{ $endDate }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 datepicker">
                </div>
                <div>
                    <label for="group_by" class="block text-sm font-medium text-gray-700 mb-1">Kelompokkan Berdasarkan</label>
                    <select id="group_by" name="group_by" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="day" {{ $groupBy === 'day' ? 'selected' : '' }}>Harian</option>
                        <option value="month" {{ $groupBy === 'month' ? 'selected' : '' }}>Bulanan</option>
                        <option value="year" {{ $groupBy === 'year' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Tampilkan Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
            <div class="text-gray-500 text-sm font-medium mb-1">Total Pendapatan</div>
            <div class="text-2xl font-bold text-gray-800">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
            <div class="text-sm text-gray-500 mt-1">dari {{ $summary['total_bookings'] }} transaksi</div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
            <div class="text-gray-500 text-sm font-medium mb-1">Rata-rata per Transaksi</div>
            <div class="text-2xl font-bold text-gray-800">Rp {{ number_format($summary['average_revenue'], 0, ',', '.') }}</div>
            <div class="text-sm text-gray-500 mt-1">rata-rata per transaksi</div>
        </div>
        <div class="bg-white p-6 rounded-xl shadow border border-gray-100">
            <div class="text-gray-500 text-sm font-medium mb-1">Periode</div>
            <div class="text-2xl font-bold text-gray-800">{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }}</div>
            <div class="text-sm text-gray-500">s/d {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="bg-white p-6 rounded-xl shadow border border-gray-100 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Grafik Pendapatan</h3>
        <div class="h-80">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Revenue Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Detail Pendapatan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Transaksi</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($revenues as $revenue)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $revenue->period }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                            {{ number_format($revenue->total_bookings, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">
                            Rp {{ number_format($revenue->total_revenue, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada data pendapatan untuk periode yang dipilih.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
    // Initialize date picker
    flatpickr(".datepicker", {
        dateFormat: "Y-m-d",
        locale: "id",
        allowInput: true
    });

    // Revenue Chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = @json($revenues);
    
    const labels = revenueData.map(item => item.period);
    const data = revenueData.map(item => item.total_revenue);
    
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan',
                data: data,
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderColor: 'rgba(79, 70, 229, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: 'rgba(79, 70, 229, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(79, 70, 229, 1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.raw.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
