<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Infrastructure Fault | Kawach Powered</title>
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
        /* Soft, critical red/amber ambient core lighting for system fault */
        .cyber-glow {
            background: radial-gradient(circle at 50% 50%, rgba(239, 68, 68, 0.1) 0%, transparent 65%);
        }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center p-4 overflow-x-hidden relative tech-grid">

    <div class="absolute inset-0 cyber-glow pointer-events-none"></div>

    <div class="relative w-full max-w-xl text-center z-10">
        
        <div class="inline-flex items-center justify-center relative mb-8">
            <div class="absolute inset-0 bg-red-500/10 rounded-full blur-xl animate-pulse scale-150"></div>
            <div class="w-24 h-24 bg-slate-900/80 border border-red-500/30 text-red-400 rounded-2xl flex flex-col items-center justify-center shadow-2xl backdrop-blur-md">
                <i class="fa-solid fa-server text-3xl mb-1 animate-pulse"></i>
                <div class="flex space-x-1">
                    <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                    <span class="w-2 h-2 rounded-full bg-slate-700"></span>
                    <span class="w-2 h-2 rounded-full bg-slate-700"></span>
                </div>
            </div>
        </div>

        <div class="inline-block px-3 py-1 bg-red-500/10 border border-red-400/20 text-red-400 text-xs font-mono tracking-wide rounded-full mb-4">
            CORE_EXCEPTION: INTERNAL_SERVER_ERROR
        </div>

        <h1 class="text-3xl md:text-4xl font-bold text-white tracking-tight mb-3">
            System Interruption
        </h1>

        <p class="text-slate-400 text-base md:text-lg max-w-md mx-auto mb-8 font-light leading-relaxed">
            The infrastructure layer encountered an unexpected exception while compiling this operational process. Our telemetry systems have been alerted.
        </p>

        <div class="bg-slate-900/60 border border-slate-800 backdrop-blur-md rounded-xl p-4 mb-8 text-left max-w-md mx-auto shadow-xl">
            <div class="flex items-start space-x-3">
                <div class="text-amber-500 mt-0.5">
                    <i class="fa-solid fa-microchip"></i>
                </div>
                <div class="w-full">
                    <h4 class="text-sm font-medium text-slate-200">Incident Diagnostics Report:</h4>
                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">
                        The application environment failed to safely process your request payload. 
                        @if(config('app.debug') && isset($exception))
                            <span class="block mt-2 font-mono text-amber-400 bg-red-950/40 border border-red-900/50 px-2 py-1 rounded break-all select-all">
                                {{ $exception->getMessage() }}
                            </span>
                        @else
                            If this continues to disrupt your workflow, please raise an infrastructure ticket.
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 max-w-sm mx-auto">
            <button onclick="window.location.reload();" 
               class="w-full sm:w-auto px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-medium rounded-lg border border-slate-700 transition duration-200 shadow-md inline-flex items-center justify-center gap-2 cursor-pointer">
                <i class="fa-solid fa-rotate text-xs"></i> Retry Handshake
            </button>
            
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