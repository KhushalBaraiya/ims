<!DOCTYPE html>
<html lang="en" class="h-full bg-zinc-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Kalathiya POS</title>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- FontAwesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body class="h-full font-sans antialiased text-zinc-100 flex items-center justify-center p-4 bg-zinc-950 relative overflow-hidden">

    <!-- Glowing Background blobs -->
    <div class="absolute top-0 left-0 w-[500px] h-[500px] rounded-full bg-blue-600/10 blur-[120px] -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] rounded-full bg-violet-600/10 blur-[120px] translate-x-1/2 translate-y-1/2 pointer-events-none"></div>

    <div class="w-full max-w-md bg-zinc-900/60 backdrop-blur-md border border-zinc-800 p-8 rounded-2xl shadow-2xl relative z-10">
        <!-- Logo -->
        <div class="text-center mb-2">
            <span class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-violet-400 bg-clip-text text-transparent">
                <i class="fa-solid fa-bolt mr-2"></i>Kalathiya POS
            </span>
        </div>
        <div class="text-center text-xs text-zinc-500 font-medium tracking-wide uppercase mb-8">
            Electronics Inventory & POS System
        </div>

        @yield('content')
    </div>

</body>
</html>
