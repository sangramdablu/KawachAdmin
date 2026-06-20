<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Endpoint Not Found | Kawach Powered</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0b0f19; /* Kawach Deep Slate Night Base */
        }
        /* Tech Grid Pattern Background */
        .tech-grid {
            background-size: 40px 40px;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }
        /* Core ambient hub lighting */
        .cyber-glow {
            background: radial-gradient(circle at 50% 50%, rgba(14, 165, 233, 0.12) 0%, transparent 65%);
        }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center p-4 overflow-x-hidden relative tech-grid">

    <div class="absolute inset-0 cyber-glow pointer-events-none"></div>

    <div class="relative w-full max-w-xl text-center z-10">
        
        <div class="inline-flex items-center justify-center relative mb-8">
            <div class="absolute inset-0 bg-sky-500/10 rounded-full blur-xl animate-pulse scale-150"></div>
            <div class="w-24 h-24 bg-slate-900/80 border border-slate-800 text-sky-400 rounded-2xl flex flex-col items-center justify-center shadow-2xl backdrop-blur-md relative overflow-hidden group">
                <span class="text-3xl font-extrabold tracking-tighter text-white font-mono">404</span>
                <span class="text-[10px] uppercase tracking-widest text-sky-500 font-medium -mt-1">Missing</span>
            </div>
        </div>

        <div class="inline-block px-3 py-1 bg-slate-800/60 border border-slate-700/60 text-slate-400 text-xs font-mono tracking-wide rounded-full mb-4">
            HTTP_STATUS_CODE: NOT_FOUND
        </div>

        <h1 class="text-3xl md:text-4xl font-bold text-white tracking-tight mb-3">
            Endpoint Unreachable
        </h1>

        <p class="text-slate-400 text-base md:text-lg max-w-md mx-auto mb-8 font-light leading-relaxed">
            The resource path or data package directory you are trying to intercept does not exist or has been relocated within the network.
        </p>

        <div class="bg-slate-900/60 border border-slate-800 backdrop-blur-md rounded-xl p-4 mb-8 text-left max-w-md mx-auto shadow-xl">
            <div class="flex items-start space-x-3">
                <div class="text-sky-500 mt-0.5">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
                <div class="w-full overflow-hidden">
                    <h4 class="text-sm font-medium text-slate-200">Attempted Route Pointer:</h4>
                    <code class="block mt-1 text-xs font-mono text-slate-400 bg-slate-950/40 border border-slate-900/50 px-2 py-1 rounded select-all break-all truncate">
                        {{ request()->fullUrl() }}
                    </code>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 max-w-sm mx-auto">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}" 
               class="w-full sm:w-auto px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-medium rounded-lg border border-slate-700 transition duration-200 shadow-md inline-flex items-center justify-center gap-2">
                <i class="fa-solid fa-chevron-left text-xs"></i> Return Back
            </a>
            
            <a href="{{ route('dashboard') }}" 
               class="w-full sm:w-auto px-5 py-2.5 bg-sky-600 hover:bg-sky-500 text-white text-sm font-medium rounded-lg transition duration-200 shadow-lg shadow-sky-600/20 inline-flex items-center justify-center gap-2">
                <i class="fa-solid fa-house text-xs"></i> Core Dashboard
            </a>
        </div>

        <div class="mt-16 text-xs text-slate-600 tracking-wide uppercase">
            &copy; {{ date('Y') }} Kawach Smart Infrastructure. All rights reserved.
        </div>
    </div>

</body>
</html>