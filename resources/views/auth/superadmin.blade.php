<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SafetyNet — System Nexus Command Console</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&family=merriweather:300,400,700&display=swap" rel="stylesheet" />

        <!-- Scripts & Styling -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .font-serif-custom { font-family: 'Merriweather', serif; }
            
            /* High-end Cyber Animations */
            @keyframes pulseGlow {
                0%, 100% { opacity: 0.15; transform: scale(1); }
                50% { opacity: 0.25; transform: scale(1.05); }
            }

            @keyframes rotateConsole {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            .animate-pulse-glow {
                animation: pulseGlow 8s ease-in-out infinite;
            }

            .animate-rotate-console {
                animation: rotateConsole 40s linear infinite;
            }

            /* Custom cyber mesh grid background */
            .bg-cyber-grid {
                background-size: 40px 40px;
                background-image: 
                    linear-gradient(to right, rgba(99, 102, 241, 0.03) 1px, transparent 1px),
                    linear-gradient(to bottom, rgba(99, 102, 241, 0.03) 1px, transparent 1px);
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-slate-950 text-slate-100 min-h-screen flex items-center justify-center p-6 relative overflow-hidden bg-cyber-grid">
        
        <!-- Radical Blur Background Elements -->
        <div class="absolute top-1/4 left-1/4 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-indigo-500/10 rounded-full blur-[120px] animate-pulse-glow"></div>
        <div class="absolute bottom-1/4 right-1/4 translate-x-1/2 translate-y-1/2 w-[500px] h-[500px] bg-violet-500/10 rounded-full blur-[120px] animate-pulse-glow" style="animation-delay: -4s;"></div>

        <!-- System Console Ring Overlay (Cyberpunk HUD decoration) -->
        <div class="absolute -top-32 -right-32 w-96 h-96 border border-indigo-500/10 rounded-full pointer-events-none flex items-center justify-center animate-rotate-console">
            <div class="w-80 h-80 border border-dashed border-indigo-500/5 rounded-full"></div>
            <div class="w-64 h-64 border border-indigo-500/20 rounded-full"></div>
        </div>
        <div class="absolute -bottom-32 -left-32 w-96 h-96 border border-violet-500/10 rounded-full pointer-events-none flex items-center justify-center animate-rotate-console" style="animation-direction: reverse;">
            <div class="w-80 h-80 border border-dashed border-violet-500/5 rounded-full"></div>
            <div class="w-64 h-64 border border-violet-500/20 rounded-full"></div>
        </div>

        <!-- Glassmorphism Login Container -->
        <div class="w-full max-w-md relative z-10">
            
            <!-- Branding Header -->
            <div class="flex flex-col items-center mb-8">
                <div class="w-16 h-16 bg-slate-900/80 border border-indigo-500/30 rounded-2xl flex items-center justify-center mb-4 shadow-[0_0_30px_rgba(99,102,241,0.25)] relative group overflow-hidden">
                    <!-- Laser scanning effect -->
                    <div class="absolute inset-x-0 h-0.5 bg-indigo-500/60 top-0 group-hover:translate-y-16 transition-all duration-1000 ease-in-out shadow-[0_0_10px_#6366f1]"></div>
                    
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-400 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-[9px] font-black tracking-[0.25em] text-indigo-400 bg-indigo-500/10 border border-indigo-500/20 px-3 py-1 rounded-full uppercase">Superadmin Portal</span>
                </div>
                <h1 class="text-3xl font-black tracking-tight text-white mb-2">System Nexus</h1>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider text-center">Authorized Layer-1 safety credentials required</p>
            </div>

            <!-- Login Card -->
            <div class="bg-slate-900/50 backdrop-blur-2xl border border-indigo-500/20 rounded-3xl p-8 sm:p-10 shadow-[0_20px_50px_rgba(0,0,0,0.5),_0_0_60px_-15px_rgba(99,102,241,0.15)] relative">
                
                <!-- Inner Neon Corner Overlays -->
                <div class="absolute top-0 left-0 w-8 h-8 border-t-2 border-l-2 border-indigo-500/40 rounded-tl-3xl"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b-2 border-r-2 border-indigo-500/40 rounded-br-3xl"></div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-6 text-sm text-green-400" :status="session('status')" />

                <form method="POST" action="{{ route('superadmin.login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Secure Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M14.243 5.757a6 6 0 10-.986 9.284 1 1 0 111.087 1.678A8 8 0 1118 10a3 3 0 01-4.8 2.401 9.049 9.049 0 01-1.378-.29 1 1 0 11.51-1.935 7.049 7.049 0 001.227.124 1 1 0 001-1V5.757z" clip-rule="evenodd"/>
                                </svg>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                                class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 outline-none transition bg-slate-950/80 text-white placeholder-slate-600 text-sm shadow-inner" 
                                placeholder="superadmin@safety.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2.5 text-xs text-red-400 font-medium" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Security Access Key</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <input id="password" type="password" name="password" required autocomplete="current-password" 
                                class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500/50 outline-none transition bg-slate-950/80 text-white placeholder-slate-600 text-sm shadow-inner" 
                                placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2.5 text-xs text-red-400 font-medium" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-800 text-indigo-600 focus:ring-indigo-500 bg-slate-950 cursor-pointer">
                        <label for="remember_me" class="ml-2.5 text-xs font-bold uppercase tracking-wider text-slate-400 cursor-pointer select-none">Remember Device</label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" class="w-full py-4 px-4 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-black text-xs uppercase tracking-widest rounded-xl transition-all shadow-[0_4px_20px_rgba(99,102,241,0.3)] hover:shadow-[0_4px_25px_rgba(99,102,241,0.5)] active:scale-[0.98] cursor-pointer">
                            Initialize Login Sequence
                        </button>
                    </div>
                </form>
            </div>

            <!-- Portal Footer Info -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-[10px] font-black uppercase tracking-widest text-slate-500 hover:text-slate-300 transition-colors flex items-center justify-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Return to Citizen Node
                </a>
            </div>

        </div>

    </body>
</html>
