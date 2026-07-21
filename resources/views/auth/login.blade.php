{{--

    SUNDAN NYO TO PARA DI KAYO MAHIRAPAN KUNG ANO GAGAWIN AFTER NETO START NA KAYO SA SYSTEM NYO OR PWEDE NYO NAMAN NA 
    HINDI GALAWIN TO TAS DIRETSO NA KAYO  SA SYSTEM NYO PARA DI KAYO MAHIRAPAN SA PAG GAWA SINCE MAG LOGIN/OUT PA KAYO NG PAULIT ULIT
|─────────────────────────────────────────────────────────────────────────────────
| TripWise — Universal Login Page Template
| Designed by: TripWise Development Team (Team 8 — Facilities & Administrative)
|─────────────────────────────────────────────────────────────────────────────────
|
| HOW TO CUSTOMIZE THIS FILE FOR YOUR TEAM:
|
|  1. SYSTEM TITLE   → Search for "Super Admin Login" and replace with your dept name
|                       e.g. "Payroll & Benefits" or "Fleet Management Portal"
|
|  2. HERO TEXT      → Search for "Total Control." and update the left-panel tagline
|                       to match your system's identity
|
|  3. FORM ACTION    → Update the <form action="..."> to point to your team's login POST route
|
|  4. BACK LINK      → The "Back to Public Website" link at the bottom points to url('/').
|                       Keep as-is if your landing page is the same host, otherwise update.
|
|  5. BACKGROUND     → Swap `login_bg.png` inside /public/ with your own image,
|                       or keep the default TripWise city background.
|
|  6. AUTH LOGIC     → Wire up authentication in your web.php / AuthController.
|                       See the placeholder comments in routes/web.php.
|
|  7. STAT NUMBERS   → Update the bottom-left Live Stats strip (Trips/Fleet/Crew)
|                       with meaningful numbers from your own system module.
|─────────────────────────────────────────────────────────────────────────────────
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="TripWise Super Admin — Secure Executive Portal Login">
    {{-- TODO: Change the title below to match your department --}}
    <title>TripWise — Super Admin Login</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: { DEFAULT: '#F44336', dark: '#D32F2F', light: '#EF5350' },
                        charcoal: { DEFAULT: '#1c1c1e', light: '#2c2c2e', dark: '#111112' },
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.6s ease-out both',
                        'fade-in': 'fadeIn 0.8s ease-out both',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                    },
                }
            }
        }
    </script>

    <style>
        * { box-sizing: border-box; }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #F44336; border-radius: 4px; }

        /* Input focus glow */
        .input-field:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(244, 67, 54, 0.15);
            border-color: #F44336;
        }

        /* Glassmorphism panel */
        .glass-panel {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
        }

        /* Password toggle */
        .toggle-pw { cursor: pointer; user-select: none; }
    </style>
</head>

<body class="font-sans antialiased h-screen w-screen overflow-hidden">

    <!-- Full Screen Container -->
    <div class="flex h-screen w-screen">

        <!-- ============================================
             LEFT PANEL — Cinematic Background
             ============================================ -->
        <div class="hidden lg:flex lg:w-[58%] xl:w-[62%] relative flex-col justify-between overflow-hidden">

            <!-- Background Image -->
            <div class="absolute inset-0">
                <img
                    src="{{ asset('login_bg.png') }}"
                    alt="TripWise Background"
                    class="w-full h-full object-cover object-center animate-fade-in"
                >
                <!-- Dark overlay gradient -->
                <div class="absolute inset-0 bg-gradient-to-br from-charcoal/90 via-charcoal/60 to-transparent"></div>
                <!-- Red accent bottom overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-brand/20 via-transparent to-transparent"></div>
            </div>

            <!-- Left Panel Content -->
            <div class="relative z-10 flex flex-col justify-between h-full p-10 xl:p-14">

                <!-- Top: Logo & Brand -->
                <div class="animate-fade-in-up" style="animation-delay: 0.1s">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur border border-white/20 flex items-center justify-center p-1.5 shadow-lg">
                            <img src="{{ asset('tripwise_icon.png') }}" alt="TripWise" class="w-full h-full object-contain">
                        </div>
                        <span class="text-2xl font-extrabold font-outfit text-white tracking-tight">
                            TripWise<span class="text-brand">.</span>
                        </span>
                    </div>
                </div>

                <!-- Center: Hero Text -->
                <div class="space-y-6 max-w-lg animate-fade-in-up" style="animation-delay: 0.25s">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-brand/20 border border-brand/30 backdrop-blur-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-brand animate-pulse"></span>
                        <span class="text-xs font-semibold text-brand tracking-wider uppercase">Executive Command Center</span>
                    </div>

                    {{-- TODO: Update the hero headline below to match your dept system --}}
                    <h1 class="text-4xl xl:text-5xl font-extrabold font-outfit text-white leading-tight">
                        Total Control.<br>
                        <span class="text-brand">One Dashboard.</span>
                    </h1>

                    <p class="text-sm text-white/60 leading-relaxed max-w-sm">
                        Monitor operations, workforce, logistics, and finances across all 10 integrated subsystems from a single secure portal.
                    </p>

                    <!-- Cluster Status Indicators -->
                    <div class="flex items-center gap-4 pt-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                            <span class="text-xs font-semibold text-white/70">10 Systems Online</span>
                        </div>
                        <div class="w-px h-4 bg-white/20"></div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-brand animate-pulse"></span>
                            <span class="text-xs font-semibold text-white/70">Secure Encrypted Portal</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom: Stats Strip -->
                <div class="flex items-center gap-6 animate-fade-in-up" style="animation-delay: 0.4s">
                    @foreach([
                        ['value' => '1,482', 'label' => 'Active Trips'],
                        ['value' => '247', 'label' => 'Fleet Ready'],
                        ['value' => '318', 'label' => 'Crew Online'],
                    ] as $stat)
                    <div class="glass-panel border border-white/10 rounded-2xl px-4 py-3">
                        <p class="text-lg font-extrabold font-outfit text-white leading-none">{{ $stat['value'] }}</p>
                        <p class="text-[10px] text-white/50 mt-0.5">{{ $stat['label'] }}</p>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>

        <!-- ============================================
             RIGHT PANEL — Login Form
             ============================================ -->
        <div class="flex-1 flex flex-col justify-center items-center bg-white px-6 py-10 sm:px-10 overflow-y-auto relative">

            <!-- Mobile: Logo (only visible on mobile when left panel is hidden) -->
            <div class="lg:hidden flex items-center gap-2.5 mb-8">
                <div class="w-9 h-9 rounded-xl bg-charcoal flex items-center justify-center p-1.5">
                    <img src="{{ asset('tripwise_icon.png') }}" alt="TripWise" class="w-full h-full object-contain">
                </div>
                <span class="text-xl font-extrabold font-outfit text-charcoal">TripWise<span class="text-brand">.</span></span>
            </div>

            <div class="w-full max-w-sm animate-fade-in-up" style="animation-delay: 0.15s">

                <!-- Form Header -->
                <div class="mb-8">
                    <h2 class="text-2xl font-extrabold font-outfit text-charcoal tracking-tight">Welcome back</h2>
                    <p class="text-sm text-gray-400 mt-1.5">Sign in to your executive dashboard</p>
                </div>

                @if($errors->any())
                <div class="mb-5 flex items-start gap-3 p-4 rounded-2xl bg-red-50 border border-red-100">
                    <svg class="w-4 h-4 text-brand mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-xs font-semibold text-brand leading-relaxed">{{ $errors->first() }}</p>
                </div>
                @endif

                {{-- TODO: Update the form action to your team's POST login route --}}
                <!-- Login Form -->
                <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="block text-xs font-bold text-charcoal">Email Address</label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                            </div>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email') }}"
                                placeholder="admin@tripwise.app"
                                required
                                class="input-field w-full pl-10 pr-4 py-3 text-sm text-charcoal placeholder-gray-300 bg-gray-50 border border-gray-200 rounded-xl transition-all duration-200"
                            >
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-xs font-bold text-charcoal">Password</label>
                            <a href="#" class="text-[11px] font-semibold text-brand hover:text-brand-dark transition-colors">Forgot password?</a>
                        </div>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="••••••••••"
                                required
                                class="input-field w-full pl-10 pr-11 py-3 text-sm text-charcoal placeholder-gray-300 bg-gray-50 border border-gray-200 rounded-xl transition-all duration-200"
                            >
                            <!-- Eye Toggle -->
                            <button type="button" id="toggle-pw" class="toggle-pw absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-charcoal transition-colors">
                                <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <div class="relative">
                                <input type="checkbox" name="remember" id="remember" class="sr-only peer">
                                <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:bg-brand transition-colors duration-200"></div>
                                <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-4"></div>
                            </div>
                            <span class="text-xs font-medium text-gray-500">Keep me logged in</span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        id="login-btn"
                        class="w-full py-3.5 rounded-xl bg-brand hover:bg-brand-dark active:scale-[0.98] text-white font-bold text-sm tracking-wide shadow-lg shadow-brand/25 transition-all duration-200 flex items-center justify-center gap-2"
                    >
                        <span id="btn-text">Enter Command Center</span>
                        <svg id="btn-arrow" class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </form>

                <!-- Divider -->
                <div class="flex items-center gap-3 my-6">
                    <div class="flex-1 h-px bg-gray-100"></div>
                    <span class="text-[10px] font-semibold text-gray-300 uppercase tracking-widest">Secured Portal</span>
                    <div class="flex-1 h-px bg-gray-100"></div>
                </div>

                <!-- Security Badge Row -->
                <div class="flex items-center justify-center gap-5">
                    <div class="flex items-center gap-1.5 text-[10px] text-gray-400 font-medium">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        SSL Encrypted
                    </div>
                    <div class="flex items-center gap-1.5 text-[10px] text-gray-400 font-medium">
                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                        </svg>
                        Admin Access Only
                    </div>
                </div>

                <!-- Back to Site -->
                <div class="text-center mt-8">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-gray-400 hover:text-charcoal transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Public Website
                    </a>
                </div>

            </div>

            <!-- Bottom copyright -->
            <p class="absolute bottom-5 text-[10px] text-gray-300 font-medium">
                &copy; {{ date('Y') }} TripWise. All rights reserved.
            </p>

        </div>

    </div>

    <script>
        // Password visibility toggle
        const toggleBtn = document.getElementById('toggle-pw');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        toggleBtn.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            eyeIcon.innerHTML = isPassword
                ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>` 
                : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
        });

        // Loading state on submit
        const form = document.querySelector('form');
        const btn = document.getElementById('login-btn');
        const btnText = document.getElementById('btn-text');

        form.addEventListener('submit', () => {
            btn.disabled = true;
            btn.classList.add('opacity-80');
            btnText.textContent = 'Authenticating...';
        });
    </script>

</body>
</html>