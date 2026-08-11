<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TripWise — Two-Factor Verification</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

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
                        brand: { DEFAULT: '#F44336', dark: '#D32F2F' },
                        charcoal: { DEFAULT: '#1c1c1e', dark: '#111112' },
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4 font-sans">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
        
        <!-- Icon & Header -->
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-50 text-brand rounded-2xl flex items-center justify-center mx-auto mb-4 border border-red-100 shadow-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-extrabold font-outfit text-charcoal">Two-Factor Authentication</h2>
            <p class="text-xs text-gray-500 mt-1.5">Enter the 6-digit security code sent to <strong class="text-charcoal">{{ session('2fa_email', 'tripwiseadmin@gmail.com') }}</strong></p>
        </div>

        @if($errors->any())
            <div class="mb-5 p-3.5 bg-red-50 border border-red-100 text-brand text-xs font-semibold rounded-xl text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('2fa.verify') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 text-center mb-2 uppercase tracking-wider">6-Digit Code</label>
                <div class="flex justify-center gap-2" id="otpContainer">
                    <input type="text" maxlength="1" class="otp-input w-11 h-12 text-center text-xl font-extrabold border border-gray-200 rounded-xl focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none transition-all" autofocus>
                    <input type="text" maxlength="1" class="otp-input w-11 h-12 text-center text-xl font-extrabold border border-gray-200 rounded-xl focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none transition-all">
                    <input type="text" maxlength="1" class="otp-input w-11 h-12 text-center text-xl font-extrabold border border-gray-200 rounded-xl focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none transition-all">
                    <input type="text" maxlength="1" class="otp-input w-11 h-12 text-center text-xl font-extrabold border border-gray-200 rounded-xl focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none transition-all">
                    <input type="text" maxlength="1" class="otp-input w-11 h-12 text-center text-xl font-extrabold border border-gray-200 rounded-xl focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none transition-all">
                    <input type="text" maxlength="1" class="otp-input w-11 h-12 text-center text-xl font-extrabold border border-gray-200 rounded-xl focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none transition-all">
                </div>
                <input type="hidden" name="otp" id="fullOtpInput">
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl bg-brand hover:bg-brand-dark active:scale-[0.98] text-white font-bold text-sm tracking-wide shadow-lg shadow-brand/25 transition-all">
                Verify & Continue to Admin Portal
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-xs text-gray-400 hover:text-charcoal transition-colors">
                &larr; Back to Login
            </a>
        </div>
    </div>

    <script>
        const inputs = document.querySelectorAll('.otp-input');
        const hiddenInput = document.getElementById('fullOtpInput');

        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                updateHiddenValue();
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });

        function updateHiddenValue() {
            let code = '';
            inputs.forEach(i => code += i.value);
            hiddenInput.value = code;
        }
    </script>
</body>
</html>
