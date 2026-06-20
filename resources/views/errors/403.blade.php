<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Security Intercept | Kawach Powered</title>
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
        /* Custom Abstract Tech Grid Pattern Background */
        .tech-grid {
            background-size: 40px 40px;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }
        /* Radial glow to mimic high-end SaaS software dashboards */
        .cyber-glow {
            background: radial-gradient(circle at 50% 50%, rgba(14, 165, 233, 0.15) 0%, transparent 60%);
        }
        @keyframes float {
            0%, 100% {
                transform: translateY(0) scale(1);
                filter: drop-shadow(0 0 8px rgba(56, 189, 248, 0.6));
            }
            50% {
                transform: translateY(-6px) scale(1.04);
                filter: drop-shadow(0 0 15px rgba(56, 189, 248, 0.9));
            }
        }

        /* Connects the custom keyframe structure directly to the custom inline animation property */
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center p-4 overflow-x-hidden relative tech-grid">

    <div class="absolute inset-0 cyber-glow pointer-events-none"></div>

    <div class="relative w-full max-w-xl text-center z-10">
        
        <div class="inline-flex items-center justify-center relative mb-8 group">
            <div class="absolute inset-0 bg-sky-500/30 rounded-2xl animate-ping opacity-20 scale-105 duration-1000"></div>
            <div class="absolute inset-0 bg-sky-500/10 rounded-2xl blur-xl animate-pulse scale-125"></div>
            <div class="w-24 h-24 bg-slate-900/90 border border-sky-500/40 text-sky-400 rounded-2xl flex flex-col items-center justify-center shadow-[0_0_30px_rgba(14,165,233,0.15)] backdrop-blur-md relative z-10 transition-all duration-300 group-hover:border-sky-400/70 group-hover:shadow-[0_0_40px_rgba(14,165,233,0.3)]">
                <i class="fa-solid fa-shield-halved text-4xl mb-1 drop-shadow-[0_0_8px_rgba(56,189,248,0.6)] animate-[float_3s_ease-in-out_infinite]"></i>
                <span class="text-[9px] font-mono tracking-widest text-sky-500/70 uppercase select-none font-semibold">Secure</span>
            </div>
        </div>

        <div class="inline-block px-3 py-1 bg-sky-500/10 border border-sky-400/20 text-sky-400 text-xs font-semibold tracking-wider uppercase rounded-full mb-4">
            Security Protocol Active
        </div>

        <h1 class="text-3xl md:text-4xl font-bold text-white tracking-tight mb-3">
            Access Verification Failed
        </h1>

        <p class="text-slate-400 text-base md:text-lg max-w-md mx-auto mb-8 font-light leading-relaxed">
            Your current account role does not possess the clearance required to interact with this specific environment endpoint.
        </p>

        <div class="bg-slate-900/60 border border-slate-800 backdrop-blur-md rounded-xl p-4 mb-8 text-left max-w-md mx-auto shadow-xl">
            <div class="flex items-start space-x-3">
                <div class="text-amber-500 mt-0.5">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-slate-200">Required Clearance Parameter:</h4>
                    <code class="block mt-1 text-xs font-mono text-sky-300 bg-sky-950/40 border border-sky-900/50 px-2 py-1 rounded select-all break-all">
                        {{ $exception->getMessage() ?: 'pages.create' }}
                    </code>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 max-w-sm mx-auto">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}" 
               class="w-full sm:w-auto px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-medium rounded-lg border border-slate-700 transition duration-200 shadow-md inline-flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-left text-xs"></i> Go Back
            </a>
            
            <a href="{{ route('dashboard') }}" 
               class="w-full sm:w-auto px-5 py-2.5 bg-sky-600 hover:bg-sky-500 text-white text-sm font-medium rounded-lg transition duration-200 shadow-lg shadow-sky-600/20 inline-flex items-center justify-center gap-2">
                <i class="fa-solid fa-house text-xs"></i> Dashboard Base
            </a>
        </div>

        <div class="mt-16 text-xs text-slate-600 tracking-wide uppercase">
            &copy; {{ date('Y') }} Kawach Smart Infrastructure. All rights reserved.
        </div>
    </div>

</body>
</html>