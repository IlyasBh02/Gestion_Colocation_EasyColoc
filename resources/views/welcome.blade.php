<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>EasyColoc - Modern Colocation Management</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --glass-bg: rgba(255, 255, 255, 0.7);
                --glass-border: rgba(255, 255, 255, 0.3);
            }
            
            body { 
                font-family: 'Outfit', sans-serif; 
                overflow-x: hidden;
            }

            .mesh-bg {
                background-color: #f8fafc;
                background-image: 
                    radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 50%),
                    radial-gradient(at 100% 0%, rgba(168, 85, 247, 0.15) 0px, transparent 50%),
                    radial-gradient(at 100% 100%, rgba(236, 72, 153, 0.15) 0px, transparent 50%),
                    radial-gradient(at 0% 100%, rgba(34, 197, 94, 0.1) 0px, transparent 50%);
                background-attachment: fixed;
            }

            .glass {
                background: var(--glass-bg);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid var(--glass-border);
            }

            .text-gradient {
                background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .hover-lift {
                transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            }
            .hover-lift:hover {
                transform: translateY(-8px) scale(1.02);
            }

            @keyframes blob {
                0% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
                100% { transform: translate(0px, 0px) scale(1); }
            }

            .animate-blob {
                animation: blob 7s infinite;
            }

            .animation-delay-2000 { animation-delay: 2s; }
            .animation-delay-4000 { animation-delay: 4s; }
        </style>
    </head>
    <body class="antialiased mesh-bg text-slate-900">
        <!-- Background Decorations -->
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-indigo-200/30 rounded-full blur-3xl animate-blob"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-purple-200/30 rounded-full blur-3xl animate-blob animation-delay-2000"></div>
            <div class="absolute top-[30%] right-[10%] w-[30%] h-[30%] bg-pink-200/20 rounded-full blur-3xl animate-blob animation-delay-4000"></div>
        </div>

        <nav class="fixed w-full z-50 px-6 py-4">
            <div class="max-w-7xl mx-auto flex justify-between items-center glass rounded-2xl px-6 py-3 shadow-sm border-white/50">
                <div class="flex items-center space-x-2">
                    <x-application-logo class="w-10 h-10" />
                    <span class="text-xl font-bold tracking-tight text-slate-800">EasyColoc</span>
                </div>
                
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition-colors">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-700 hover:text-indigo-600 transition-colors">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/30 hover:bg-indigo-500 transition-all active:scale-95">Sign Up</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        <main class="relative pt-32 pb-20 px-6">
            <div class="max-w-7xl mx-auto text-center">
                <div class="inline-flex items-center px-4 py-2 rounded-full glass mb-8 animate-fade-in-up">
                    <span class="flex h-2 w-2 rounded-full bg-indigo-500 mr-3"></span>
                    <span class="text-xs font-bold tracking-widest uppercase text-indigo-600">The New Way to Co-live</span>
                </div>
                
                <h1 class="text-6xl md:text-8xl font-black mb-8 tracking-tighter leading-none animate-fade-in-up">
                    <span class="block">Living Better</span>
                    <span class="text-gradient">Together.</span>
                </h1>
                
                <p class="max-w-2xl mx-auto text-lg md:text-xl text-slate-600 leading-relaxed mb-12 animate-fade-in-up">
                    Manage your shared living space with ease. Create colocations, invite roommates, and organize everything in one premium workspace.
                </p>

                <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6 animate-fade-in-up">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-10 py-4 bg-indigo-600 text-white font-bold rounded-2xl shadow-xl shadow-indigo-500/40 hover:bg-indigo-500 transition-all active:scale-95 text-lg">
                            Back to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="px-10 py-4 bg-indigo-600 text-white font-bold rounded-2xl shadow-xl shadow-indigo-500/40 hover:bg-indigo-500 transition-all active:scale-95 text-lg">
                            Start for Free
                        </a>
                        <a href="#features" class="px-10 py-4 glass text-slate-700 font-bold rounded-2xl hover:bg-white/80 transition-all text-lg">
                            Learn More
                        </a>
                    @endauth
                </div>

                <!-- Features Preview -->
                <div id="features" class="mt-32 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="p-8 glass rounded-[2.5rem] text-left hover-lift border-white/40">
                        <div class="w-14 h-14 bg-gradient-to-tr from-indigo-500 to-blue-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-indigo-200">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-slate-800">Smart Groups</h3>
                        <p class="text-slate-600 leading-relaxed">Create private colocations and invite your roommates with one-click secure invitation links.</p>
                    </div>

                    <div class="p-8 glass rounded-[2.5rem] text-left hover-lift border-white/40">
                        <div class="w-14 h-14 bg-gradient-to-tr from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-purple-200">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-slate-800">Permission Control</h3>
                        <p class="text-slate-600 leading-relaxed">Dedicated owner roles and member permissions to keep your rooming house organized and safe.</p>
                    </div>

                    <div class="p-8 glass rounded-[2.5rem] text-left hover-lift border-white/40">
                        <div class="w-14 h-14 bg-gradient-to-tr from-cyan-500 to-teal-600 rounded-2xl flex items-center justify-center text-white mb-6 shadow-lg shadow-cyan-200">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-slate-800">Rapid Invitations</h3>
                        <p class="text-slate-600 leading-relaxed">Send emails directly to your future roommates and get them onboarded in seconds.</p>
                    </div>
                </div>
            </div>
        </main>

        <footer class="py-12 px-6 border-t border-slate-200 text-center text-slate-500 text-sm font-medium">
            <p>&copy; {{ date('Y') }} EasyColoc. Created with &hearts; for better living.</p>
        </footer>
    </body>
</html>
