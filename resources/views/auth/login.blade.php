<x-guest-layout>
    <div style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/images/login-bg.png'); background-size: cover; background-position: center; background-attachment: fixed; min-height: 100vh; width: 100vw; position: fixed; top: 0; left: 0; display: flex; align-items: center; justify-content: center;">

        <!-- Login Form Container -->
        <div style="position: relative; z-index: 10; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); width: 100%; max-width: 400px; margin: 20px;">
            
            <!-- Logo -->
            <div style="text-align: center; margin-bottom: 32px;">
                <img src="/images/logo.png" alt="Teratak Sofea" style="max-width: 150px; height: auto; display: block; margin: 0 auto 16px; transform: translateX(12px); margin-bottom: 16px;">
                <p style="color: #6b7280; font-size: 14px; margin: 0;">Housekeeping Management System</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div style="margin-bottom: 16px; padding: 12px; background-color: #dcfce7; color: #166534; border-radius: 4px; font-size: 14px;">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div style="margin-bottom: 16px; padding: 12px; background-color: #fee2e2; color: #991b1b; border-radius: 4px; font-size: 14px;">
                    @foreach ($errors->all() as $error)
                        <p style="margin: 4px 0;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Code Input -->
                <div style="margin-bottom: 24px;">
                    <label for="code" style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Staff Code</label>
                    <input 
                        id="code"
                        style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 16px; box-sizing: border-box;"
                        type="text" 
                        name="code" 
                        value="{{ old('code') }}"
                        placeholder="e.g., s01, admin_001, h01"
                        required 
                        autofocus
                    >
                    @error('code')
                        <p style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input with Toggle -->
                <div style="margin-bottom: 24px;">
                    <label for="password" style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Password</label>
                    <div style="position: relative;">
                        <input 
                            id="password" 
                            style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 16px; box-sizing: border-box;"
                            type="password" 
                            name="password" 
                            placeholder="Enter your password"
                            required 
                            autocomplete="current-password"
                        >
                        <button 
                            type="button"
                            onclick="togglePasswordVisibility()"
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #6b7280; font-size: 18px; padding: 0;"
                        >
                            <span id="eyeIcon">👁️</span>
                        </button>
                    </div>
                    @error('password')
                        <p style="color: #dc2626; font-size: 12px; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div style="margin-bottom: 24px; display: flex; align-items: center;">
                    <input 
                        id="remember_me" 
                        type="checkbox" 
                        name="remember"
                        style="width: 16px; height: 16px; cursor: pointer;"
                    >
                    <label for="remember_me" style="margin-left: 8px; color: #6b7280; cursor: pointer; font-size: 14px;">
                        Remember me
                    </label>
                </div>

                <!-- Login Button -->
                <button 
                    type="submit" 
                    style="width: 100%; padding: 12px; background-color: #6f6e66; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 16px; cursor: pointer; transition: background-color 0.3s;"
                    onmouseover="this.style.backgroundColor='#929186'"
                    onmouseout="this.style.backgroundColor='#6f6e66'"
                >
                    Sign In
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                eyeIcon.textContent = '👁️';
            }
        }
    </script>
</x-guest-layout>