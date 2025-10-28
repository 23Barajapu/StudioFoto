<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Prime Studio')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .sidebar-active {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
        }
        
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-lunas {
            background: #d4edda;
            color: #155724;
        }
        
        .status-dp {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-belum {
            background: #f8d7da;
            color: #721c24;
        }
        
        @yield('styles')
    </style>
</head>
<body class="bg-gray-50">
    @yield('content')
    
    <script>
        const API_BASE_URL = '{{ url("/api") }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';
    </script>
    
    @yield('scripts')
</body>
</html>
