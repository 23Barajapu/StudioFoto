<aside class="w-64 gradient-bg text-white flex-shrink-0 overflow-y-auto">
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-8">Prime Studio</h1>
        
        <nav class="space-y-2">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-3 {{ request()->routeIs('dashboard') ? 'sidebar-active' : 'hover:bg-white/10 rounded-lg transition' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('bookings.index') }}" class="flex items-center space-x-3 p-3 {{ request()->routeIs('bookings.*') ? 'sidebar-active' : 'hover:bg-white/10 rounded-lg transition' }}">
                <i class="fas fa-calendar-check"></i>
                <span>Booking</span>
            </a>
            <a href="{{ route('payments.index') }}" class="flex items-center space-x-3 p-3 {{ request()->routeIs('payments.*') ? 'sidebar-active' : 'hover:bg-white/10 rounded-lg transition' }}">
                <i class="fas fa-dollar-sign"></i>
                <span>Kelola Pembayaran</span>
            </a>
            <a href="{{ route('reports.revenue') }}" class="flex items-center space-x-3 p-3 {{ request()->routeIs('reports.revenue') ? 'sidebar-active' : 'hover:bg-white/10 rounded-lg transition' }}">
                <i class="fas fa-chart-line"></i>
                <span>Laporan Pendapatan</span>
            </a>
            <a href="#" class="flex items-center space-x-3 p-3 hover:bg-white/10 rounded-lg transition">
                <i class="fas fa-bell"></i>
                <span>Notifikasi</span>
            </a>
            <a href="#" class="flex items-center space-x-3 p-3 hover:bg-white/10 rounded-lg transition">
                <i class="fas fa-history"></i>
                <span>Riwayat pemesanan</span>
            </a>
            <a href="{{ route('categories.index') }}" class="flex items-center space-x-3 p-3 {{ request()->routeIs('categories.*') ? 'sidebar-active' : 'hover:bg-white/10 rounded-lg transition' }}">
                <i class="fas fa-tags"></i>
                <span>Daftar Kategori</span>
            </a>
        </nav>
    </div>
    
    <!-- User Profile -->
    <div class="absolute bottom-0 w-64 p-6 border-t border-white/20">
        <div class="flex items-center space-x-3">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=667eea&color=fff" 
                 class="w-10 h-10 rounded-full" alt="User">
            <div class="flex-1">
                <p class="font-semibold">{{ Auth::user()->name ?? 'User' }}</p>
                <p class="text-xs opacity-75">{{ Auth::user()->role ?? 'Admin' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-white/70 hover:text-white">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
