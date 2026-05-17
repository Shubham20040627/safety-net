<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SafetyNet - Community Driven Security</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&family=merriweather:300,400,700&display=swap" rel="stylesheet" />
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Glassmorphism Navbar */
            .glass-nav {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(20px);
                border-bottom: 1px border rgba(0, 0, 0, 0.05);
            }

            /* Scroll Progress Bar */
            #scroll-progress {
                position: fixed;
                top: 0;
                left: 0;
                width: 0%;
                height: 4px;
                background: linear-gradient(to right, #4f46e5, #ec4899);
                z-index: 1000;
                transition: width 0.1s ease-out;
            }

            /* Magnetic Button Effect */
            .btn-magnetic {
                transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            }
            .btn-magnetic:hover {
                transform: scale(1.05) translateY(-2px);
            }

            /* Parallax Effect */
            .parallax-img {
                transform: translateY(var(--parallax-offset, 0px));
                transition: transform 0.1s ease-out;
            }

            /* Animated Text Gradient */
            .text-glow {
                background: linear-gradient(to right, #1e293b, #4f46e5, #ec4899, #1e293b);
                background-size: 300% auto;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                animation: textSweep 8s linear infinite;
            }

            @keyframes textSweep {
                0% { background-position: 0% center; }
                100% { background-position: 300% center; }
            }

            /* 3D Tilt Container */
            .tilt-card {
                transform-style: preserve-3d;
                perspective: 1000px;
            }
            .tilt-card > * {
                transition: transform 0.2s ease-out;
            }

            .font-serif-custom { font-family: 'Merriweather', serif; }
            
            /* Reveal Animation Styles */
            .reveal {
                opacity: 0;
                transform: translateY(40px);
                transition: all 1s cubic-bezier(0.16, 1, 0.3, 1);
            }
            
            .reveal-left {
                opacity: 0;
                transform: translateX(-40px);
                transition: all 1s cubic-bezier(0.16, 1, 0.3, 1);
            }
            
            .reveal-right {
                opacity: 0;
                transform: translateX(40px);
                transition: all 1s cubic-bezier(0.16, 1, 0.3, 1);
            }
            
            .revealed {
                opacity: 1;
                transform: translateY(0) translateX(0);
            }

            /* Floating Blobs */
            .blob {
                position: absolute;
                width: 500px;
                height: 500px;
                background: linear-gradient(180deg, rgba(79, 70, 229, 0.1) 0%, rgba(147, 197, 253, 0.05) 100%);
                filter: blur(80px);
                border-radius: 50%;
                z-index: -1;
                pointer-events: none;
                animation: float 20s infinite alternate ease-in-out;
            }

            @keyframes float {
                0% { transform: translate(0, 0) scale(1); }
                100% { transform: translate(100px, 50px) scale(1.1); }
            }

            /* Shimmer Effect */
            .btn-shimmer {
                position: relative;
                overflow: hidden;
            }
            .btn-shimmer::after {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
                transform: rotate(45deg);
                animation: shimmer 4s infinite;
            }

            @keyframes shimmer {
                0% { left: -150%; }
                100% { left: 150%; }
            }

            /* Progress Bar Animation */
            .progress-bar-fill {
                width: 0% !important;
                transition: width 1.5s cubic-bezier(0.16, 1, 0.3, 1);
            }
            .revealed .progress-bar-fill {
                width: var(--target-width) !important;
            }
            /* Cinematic Noise Texture */
            .noise-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: url('https://grainy-gradients.vercel.app/noise.svg');
                opacity: 0.05;
                pointer-events: none;
                z-index: 9999;
                filter: contrast(150%) brightness(100%);
            }

            /* Mouse Spotlight */
            #spotlight {
                position: fixed;
                top: 0;
                left: 0;
                width: 600px;
                height: 600px;
                background: radial-gradient(circle, rgba(79, 70, 229, 0.15) 0%, rgba(79, 70, 229, 0) 70%);
                border-radius: 50%;
                pointer-events: none;
                z-index: -1;
                transform: translate(-50%, -50%);
                transition: width 0.5s, height 0.5s;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-[#fafafa] text-slate-900 selection:bg-slate-900 selection:text-white overflow-x-hidden w-full">
        <!-- Effects -->
        <div class="noise-overlay"></div>
        <div id="spotlight"></div>

        <!-- Background Mesh Gradients -->
        <div class="blob top-[-100px] left-[-100px] opacity-60"></div>
        <div class="blob top-[300px] right-[-100px] bg-indigo-200/20" style="animation-delay: -5s; width: 600px; height: 600px;"></div>
        <div class="blob bottom-[-200px] left-[-200px] bg-amber-100/30" style="width: 800px; height: 800px; animation-delay: -10s;"></div>
        <div class="blob middle-0 right-[10%] bg-purple-100/20" style="width: 400px; height: 400px; animation-delay: -15s;"></div>

        <!-- Scroll Progress -->
        <div id="scroll-progress"></div>

        <!-- Custom Cursor -->
        <div id="cursor-dot" class="fixed w-2 h-2 bg-indigo-600 rounded-full pointer-events-none z-[9999] transition-transform duration-100 ease-out"></div>
        <div id="cursor-outline" class="fixed w-8 h-8 border border-indigo-400 rounded-full pointer-events-none z-[9998] transition-all duration-300 ease-out flex items-center justify-center">
            <div class="w-1 h-1 bg-indigo-400 rounded-full opacity-0 cursor-plus transition-opacity"></div>
        </div>
        
        <!-- Navbar -->
        <header id="main-nav" class="fixed w-full top-0 z-50 transition-all duration-300" x-data="{ open: false }">
            <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8 h-24 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-slate-900 text-white rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold tracking-tight text-slate-900">SafetyNet.</span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden sm:flex items-center gap-6">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-slate-900 hover:text-indigo-600 transition-colors">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 hover:translate-x-1 transition-all">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 px-6 py-3 rounded-full transition-all shadow-md hover:shadow-xl btn-shimmer hover:scale-105 active:scale-95">Get Started</a>
                            @endif
                        @endauth
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <div class="sm:hidden flex items-center">
                    <button @click="open = !open" class="text-slate-900 focus:outline-none">
                        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu Overlay -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 class="md:hidden bg-white border-b border-slate-100 absolute w-full z-40 shadow-xl" 
                 style="display: none;">
                <div class="px-6 py-8 space-y-6">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block text-lg font-bold text-slate-900">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="block text-lg font-bold text-slate-900 hover:text-indigo-600 transition-colors">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="block w-full bg-slate-900 text-white text-center py-4 rounded-2xl font-bold btn-shimmer">Get Started</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </header>

        <main>
            <!-- Hero Section (Split Screen) -->
            <div class="relative min-h-screen flex flex-col lg:flex-row">
                
                <!-- Left Content -->
                <div class="w-full lg:w-[55%] flex flex-col justify-center px-6 lg:px-20 xl:px-32 pt-40 lg:pt-48 pb-20 lg:py-24 z-10 bg-white">
                    <div class="max-w-2xl">

                        
                        <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold text-slate-900 tracking-tight mb-8 leading-[1.1] font-serif-custom reveal text-glow" style="transition-delay: 100ms;">
                            Protect your <br> neighborhood, <br> <span class="italic">together.</span>
                        </h1>
                        
                        <p class="text-lg sm:text-xl text-slate-600 mb-10 leading-relaxed max-w-lg border-l-4 border-slate-900 pl-6 reveal" style="transition-delay: 200ms;">
                            A highly vetted, localized network for reporting incidents, viewing real-time safety heatmaps, and building a secure environment for your family.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4 reveal" style="transition-delay: 300ms;">
                            <a href="{{ route('register') }}" class="w-full sm:w-auto bg-slate-900 text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-slate-800 transition-all text-center btn-shimmer shadow-lg hover:shadow-indigo-500/20">
                                Join the Network
                            </a>
                            <a href="#features" class="w-full sm:w-auto bg-white text-slate-900 px-8 py-4 rounded-full font-bold text-lg border-2 border-slate-200 hover:border-slate-900 transition-all text-center">
                                How it works
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Right Image -->
                <div class="w-full lg:w-[45%] lg:absolute lg:top-0 lg:right-0 lg:bottom-0 h-[60vh] lg:h-screen reveal-right" style="transition-delay: 400ms;">
                    <img src="{{ asset('images/branding/hero_pro.png') }}" 
                         alt="Professional Safety Monitoring" 
                         class="w-full h-full object-cover rounded-tl-[100px] lg:rounded-l-[100px] lg:rounded-tr-none shadow-2xl">
                </div>
                
                <!-- Parallax Hero Background Decor -->
                <div class="absolute bottom-0 right-0 w-64 h-64 bg-indigo-500/5 blur-3xl parallax-img"></div>
            </div>

            <!-- Features Section (Professional Feature Showcase) -->
            <div id="features" class="py-32 bg-white border-t border-slate-100">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    
                    <div class="text-center max-w-3xl mx-auto mb-24">
                        <h2 class="text-4xl sm:text-5xl font-black text-slate-900 font-serif-custom mb-8 tracking-tight">The Future of Neighborhood Safety.</h2>
                        <p class="text-xl text-slate-600 leading-relaxed">SafetyNet is not just a reporting tool—it is a complete security ecosystem designed to protect, inform, and guide your community.</p>
                    </div>

                    <!-- Grid of Features -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-16 lg:gap-24">

                        <!-- Feature: AI Safety Guardian -->
                        <div class="reveal-left tilt-card">
                            <!-- Premium Mockup Container -->
                            <div class="relative rounded-3xl overflow-hidden shadow-2xl mb-8 aspect-[4/3] bg-slate-950 border border-slate-800 p-8 flex flex-col justify-between group">
                                <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500"></div>
                                
                                <div class="flex items-center gap-3 border-b border-white/10 pb-4">
                                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-xs font-black shadow-lg shadow-indigo-500/30 text-white">AI</div>
                                    <div>
                                        <h4 class="font-bold text-sm text-white">SafetyNet AI Guardian</h4>
                                        <p class="text-[9px] text-indigo-400 font-extrabold uppercase tracking-widest">Active & Vetted</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-4 text-xs">
                                    <div class="bg-white/5 border border-white/5 rounded-2xl p-4 max-w-[85%]">
                                        <p class="text-slate-300">"What is the current safety status of Greenwood Corridor?"</p>
                                    </div>
                                    <div class="bg-indigo-600/10 border border-indigo-500/20 rounded-2xl p-4 text-slate-200">
                                        <span class="inline-block text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-1.5">⚡ AI Threat Analysis</span>
                                        <p class="leading-relaxed text-[11px]">Greenwood Corridor status is <span class="text-indigo-400 font-bold">Medium Risk</span>. 1 package theft detected nearby. <b class="text-indigo-300">Protocol:</b> Avoid late solo walks; security patrols dispatched.</p>
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-2xl font-black text-slate-950 font-serif-custom mb-4">Interactive AI Safety Guardian</h3>
                            <p class="text-slate-600 leading-relaxed mb-6">
                                Ask anything, get instant data-driven answers. Our Gemini-powered assistant aggregates your neighborhood's reports to analyze weekly risk curves, outline emergency protocols, and offer intelligent safety advice.
                            </p>
                        </div>

                        <!-- Feature: GIS Map Styles -->
                        <div class="reveal-right tilt-card">
                            <!-- Premium Mockup Container -->
                            <div class="relative rounded-3xl overflow-hidden shadow-2xl mb-8 aspect-[4/3] bg-slate-50 border border-slate-200 p-8 flex flex-col justify-between group">
                                <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-indigo-500/5 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500"></div>
                                
                                <div class="flex items-center justify-between border-b border-slate-200/60 pb-4">
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-sm">Vector Map Engine</h4>
                                        <p class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">Dynamic Styles</p>
                                    </div>
                                    <span class="bg-indigo-50 text-indigo-600 text-[9px] font-black uppercase px-2.5 py-1 rounded-full border border-indigo-200/30">Geofence Active</span>
                                </div>
                                
                                <div class="flex gap-2 p-1.5 bg-slate-100 rounded-2xl border border-slate-200/50">
                                    <div class="flex-1 text-center py-2 bg-white rounded-xl shadow-sm text-[10px] font-black text-slate-800 border border-slate-200/40">🗺️ Streets</div>
                                    <div class="flex-1 text-center py-2 text-[10px] font-black text-slate-400">🏔️ 3D Terrain</div>
                                    <div class="flex-1 text-center py-2 text-[10px] font-black text-slate-400">🛰️ Satellite</div>
                                </div>
                                
                                <div class="p-4 bg-indigo-50/40 border border-indigo-100/40 rounded-2xl text-[10px] text-slate-600 leading-relaxed">
                                    <span class="font-bold text-indigo-600">Pro Feature:</span> Toggle high-definition vector streets, 3D terrain elevation curves, and high-res satellite imagery instantly.
                                </div>
                            </div>
                            <h3 class="text-2xl font-black text-slate-950 font-serif-custom mb-4">Dynamic GIS Map & 3D Topography</h3>
                            <p class="text-slate-600 leading-relaxed mb-6">
                                Experience map layers like never before. Seamlessly toggle between High-Definition Vector Streets, 3D Terrain hillshading, and high-res Satellite Imagery with fully persistent geofence boundaries.
                            </p>
                        </div>
                        
                        <!-- Feature 1: Real-Time SOS -->
                        <div class="reveal-left tilt-card">
                            <div class="relative rounded-3xl overflow-hidden shadow-2xl mb-8 aspect-[4/3] group">
                                <img src="{{ asset('images/branding/sos.png') }}" alt="Real-Time SOS" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[3s]">
                                <div class="absolute inset-0 bg-gradient-to-t from-red-900/60 to-transparent"></div>
                                <div class="absolute bottom-6 left-6">
                                    <span class="px-3 py-1 bg-red-500 text-white text-[10px] font-black uppercase tracking-widest rounded-full">Immediate Action</span>
                                </div>
                            </div>
                            <h3 class="text-2xl font-black text-slate-950 font-serif-custom mb-4">Real-Time SOS Intelligence</h3>
                            <p class="text-slate-600 leading-relaxed mb-6">
                                One-tap emergency alerts that broadcast instantly to the entire community. Powered by **Pusher**, our SOS system bypasses delays to warn everyone in the network within milliseconds of a threat.
                            </p>
                        </div>

                        <!-- Feature 2: Interactive Heatmaps -->
                        <div class="reveal-right tilt-card">
                            <div class="relative rounded-3xl overflow-hidden shadow-2xl mb-8 aspect-[4/3] group">
                                <img src="{{ asset('images/branding/hero_pro.png') }}" alt="Security Heatmaps" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[3s]">
                                <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/60 to-transparent"></div>
                                <div class="absolute bottom-6 left-6">
                                    <span class="px-3 py-1 bg-indigo-500 text-white text-[10px] font-black uppercase tracking-widest rounded-full">Visual Data</span>
                                </div>
                            </div>
                            <h3 class="text-2xl font-black text-slate-950 font-serif-custom mb-4">Advanced Security Heatmaps</h3>
                            <p class="text-slate-600 leading-relaxed mb-6">
                                Visualize crime density using high-resolution **Leaflet Heatmaps**. We overlay your neighborhood data onto MapTiler professional imagery to show you exactly where safety attention is needed.
                            </p>
                        </div>

                        <!-- Feature 3: GPS Navigation -->
                        <div class="reveal-left tilt-card">
                            <div class="relative rounded-3xl overflow-hidden shadow-2xl mb-8 aspect-[4/3] group">
                                <img src="{{ asset('images/branding/responder.png') }}" alt="GPS Navigation" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[3s]">
                                <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/60 to-transparent"></div>
                                <div class="absolute bottom-6 left-6">
                                    <span class="px-3 py-1 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-full">Responder Tools</span>
                                </div>
                            </div>
                            <h3 class="text-2xl font-black text-slate-950 font-serif-custom mb-4">GPS-Guided Response</h3>
                            <p class="text-slate-600 leading-relaxed mb-6">
                                Every incident includes precise GPS coordinates. Our **Navigate** feature connects responders to Google Maps instantly, providing turn-by-turn directions to the scene for rapid intervention.
                            </p>
                        </div>

                        <!-- Feature 4: Predictive Analytics -->
                        <div class="reveal-right tilt-card">
                            <div class="relative rounded-3xl overflow-hidden shadow-2xl mb-8 aspect-[4/3] group">
                                <img src="{{ asset('images/branding/community.png') }}" alt="Safety Analytics" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[3s]">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
                                <div class="absolute bottom-6 left-6">
                                    <span class="px-3 py-1 bg-slate-700 text-white text-[10px] font-black uppercase tracking-widest rounded-full">Intelligence</span>
                                </div>
                            </div>
                            <h3 class="text-2xl font-black text-slate-950 font-serif-custom mb-4">Predictive Trend Analytics</h3>
                            <p class="text-slate-600 leading-relaxed mb-6">
                                Our dashboard analyzes historical data to predict **Peak Danger Hours** and weekly safety trends. We turn raw reports into actionable insights through sophisticated Chart.js visualizations.
                            </p>
                        </div>

                        <!-- Feature 5: Professional Reporting -->
                        <div class="reveal-left tilt-card">
                            <div class="relative rounded-3xl overflow-hidden shadow-2xl mb-8 aspect-[4/3] group">
                                <img src="{{ asset('images/branding/pdf_doc.png') }}" alt="PDF Reports" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[3s]">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-800/60 to-transparent"></div>
                                <div class="absolute bottom-6 left-6">
                                    <span class="px-3 py-1 bg-slate-600 text-white text-[10px] font-black uppercase tracking-widest rounded-full">Legal Proof</span>
                                </div>
                            </div>
                            <h3 class="text-2xl font-black text-slate-950 font-serif-custom mb-4">Official PDF Case Reports</h3>
                            <p class="text-slate-600 leading-relaxed mb-6">
                                Generate professional, court-ready PDF documents for any incident. Each report includes high-res imagery, exact timestamps, and location data in a standardized official format.
                            </p>
                        </div>

                        <!-- Feature 6: Verified Community -->
                        <div class="reveal-right">
                            <div class="bg-slate-900 rounded-3xl p-12 aspect-[4/3] flex flex-col justify-center border border-slate-800 shadow-2xl">
                                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mb-8">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <h3 class="text-3xl font-black text-white font-serif-custom mb-6">Vetted User Verification</h3>
                                <p class="text-slate-400 leading-relaxed mb-8">
                                    Safety is built on trust. Our admin-approval system ensures that only verified neighborhood residents can report incidents, keeping the network free from spam and false alarms.
                                </p>
                                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-white font-bold hover:gap-4 transition-all">
                                    Join the safe network
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            
            <!-- Footer (Corporate Enterprise Style) -->
            <footer class="bg-slate-950 text-white pt-24 pb-12 border-t border-white/5">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 mb-20">
                        
                        <!-- Column 1: Brand -->
                        <div class="space-y-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white text-slate-950 rounded-xl flex items-center justify-center shadow-lg shadow-white/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <span class="text-2xl font-black tracking-tighter uppercase">SafetyNet</span>
                            </div>
                            <p class="text-slate-400 leading-relaxed text-sm">
                                The world's first open-source neighborhood intelligence platform. Empowering citizens through real-time data and community vigilance.
                            </p>
                            <div class="flex items-center gap-4">
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-500">All Systems Operational</span>
                            </div>
                        </div>

                        <!-- Column 2: Product -->
                        <div>
                            <h4 class="text-xs font-black uppercase tracking-[0.2em] text-white/40 mb-8">Ecosystem</h4>
                            <ul class="space-y-4">
                                <li><a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">Global Dashboard</a></li>
                                <li><a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">Live Heatmaps</a></li>
                                <li><a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">Emergency SOS</a></li>
                                <li><a href="{{ route('dashboard') }}" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">Incident Reporting</a></li>
                            </ul>
                        </div>

                        <!-- Column 3: Resources -->
                        <div>
                            <h4 class="text-xs font-black uppercase tracking-[0.2em] text-white/40 mb-8">Resources</h4>
                            <ul class="space-y-4">
                                <li><a href="#" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">Safety Guidelines</a></li>
                                <li><a href="#" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">Community Handbook</a></li>
                                <li><a href="#" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">Official API</a></li>
                                <li><a href="#" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">Privacy Policy</a></li>
                            </ul>
                        </div>

                        <!-- Column 4: Technology -->
                        <div>
                            <h4 class="text-xs font-black uppercase tracking-[0.2em] text-white/40 mb-8">Built With</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-[10px] font-bold text-slate-300 text-center">Laravel 11</div>
                                <div class="px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-[10px] font-bold text-slate-300 text-center">Pusher JS</div>
                                <div class="px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-[10px] font-bold text-slate-300 text-center">MapTiler</div>
                                <div class="px-3 py-2 bg-white/5 border border-white/10 rounded-lg text-[10px] font-bold text-slate-300 text-center">Tailwind</div>
                            </div>
                            <div class="mt-8 flex gap-4">
                                <a href="#" class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center hover:bg-white/20 transition-all">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                </a>
                                <a href="#" class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center hover:bg-white/20 transition-all">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Bar -->
                    <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                        <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.2em]">
                            © {{ date('Y') }} SafetyNet. A community safety initiative.
                        </p>
                        <div class="flex gap-8">
                            <a href="#" class="text-slate-500 hover:text-white transition-colors text-[10px] font-black uppercase tracking-[0.2em]">Security Whitepaper</a>
                            <a href="#" class="text-slate-500 hover:text-white transition-colors text-[10px] font-black uppercase tracking-[0.2em]">Privacy Policy</a>
                        </div>
                    </div>
                </div>
            </footer>
        </main>

        <!-- Reveal on Scroll Script -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Custom Cursor Logic
                const dot = document.getElementById('cursor-dot');
                const outline = document.getElementById('cursor-outline');
                const spotlight = document.getElementById('spotlight');
                
                window.addEventListener('mousemove', (e) => {
                    const posX = e.clientX;
                    const posY = e.clientY;

                    // Move dot instantly
                    dot.style.transform = `translate(${posX - 4}px, ${posY - 4}px)`;

                    // Move spotlight
                    spotlight.style.left = posX + 'px';
                    spotlight.style.top = posY + 'px';
                    
                    // Move outline with slight delay (magnetic feel)
                    outline.animate({
                        left: `${posX - 16}px`,
                        top: `${posY - 16}px`
                    }, { duration: 500, fill: "forwards" });
                });

                // Hover Effects
                const interactiveElements = document.querySelectorAll('a, button, .group');
                interactiveElements.forEach(el => {
                    el.addEventListener('mouseenter', () => {
                        outline.style.transform = 'scale(2)';
                        outline.style.backgroundColor = 'rgba(79, 70, 229, 0.1)';
                        outline.style.borderColor = 'transparent';
                    });
                    el.addEventListener('mouseleave', () => {
                        outline.style.transform = 'scale(1)';
                        outline.style.backgroundColor = 'transparent';
                        outline.style.borderColor = '#818cf8';
                    });
                });

                // 3D Tilt Logic
                const tiltCards = document.querySelectorAll('.tilt-card');
                tiltCards.forEach(card => {
                    const content = card.querySelector('div');
                    card.addEventListener('mousemove', (e) => {
                        const rect = card.getBoundingClientRect();
                        const x = e.clientX - rect.left;
                        const y = e.clientY - rect.top;
                        
                        const centerX = rect.width / 2;
                        const centerY = rect.height / 2;
                        
                        const rotateX = (y - centerY) / 10;
                        const rotateY = (centerX - x) / 10;
                        
                        content.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.05)`;
                    });
                    
                    card.addEventListener('mouseleave', () => {
                        content.style.transform = 'rotateX(0deg) rotateY(0deg) scale(1)';
                    });
                });

                // Scroll Progress and Sticky Nav
                const nav = document.getElementById('main-nav');
                const progress = document.getElementById('scroll-progress');
                
                window.addEventListener('scroll', () => {
                    // Progress Bar
                    const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
                    const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                    const scrolled = (winScroll / height) * 100;
                    progress.style.width = scrolled + "%";

                    // Sticky Nav Glass Effect
                    if (window.scrollY > 50) {
                        nav.classList.add('glass-nav', 'py-2');
                        nav.classList.remove('py-4');
                    } else {
                        nav.classList.remove('glass-nav', 'py-2');
                        nav.classList.add('py-4');
                    }

                    // Parallax
                    const parallaxImgs = document.querySelectorAll('.parallax-img');
                    parallaxImgs.forEach(img => {
                        let speed = 0.2;
                        img.style.setProperty('--parallax-offset', (window.scrollY * speed) + 'px');
                    });
                });

                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('revealed');
                        }
                    });
                }, observerOptions);

                document.querySelectorAll('.reveal, .reveal-left, .reveal-right').forEach(el => {
                    observer.observe(el);
                });
            });
        </script>
    </body>
</html>
