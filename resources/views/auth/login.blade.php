<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SCR Platform') }} - Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background:#060e1f; font-family:'Inter',sans-serif; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:20px 0;">

<div style="width:100%; max-width:440px; padding:24px;">

    {{-- Logo --}}
    <div style="text-align:center; margin-bottom:28px;">
        <div style="width:52px;height:52px;background:linear-gradient(135deg,#1d4ed8,#3b82f6);border-radius:14px;display:inline-flex;align-items:center;justify-content:center;font-size:26px;box-shadow:0 8px 24px rgba(59,130,246,0.3);margin-bottom:14px;">🌍</div>
        <div style="font-size:22px;font-weight:900;color:#e2e8f0;letter-spacing:-0.03em;">GSC Risk</div>
        <div style="font-size:11px;color:#4a6080;text-transform:uppercase;letter-spacing:0.1em;margin-top:4px;">Global Supply Chain Intelligence</div>
    </div>

    {{-- Card --}}
    <div style="background:#0f1e35;border:1px solid rgba(255,255,255,0.08);border-radius:20px;padding:32px;box-shadow:0 20px 40px rgba(0,0,0,0.4);">

        <div style="font-size:18px;font-weight:800;color:#e2e8f0;margin-bottom:4px;">Sign in to your account</div>
        <div style="font-size:12px;color:#8b9fc7;margin-bottom:22px;">Masuk sebagai Administrator atau Risk Analyst</div>



        {{-- Session Status --}}
        @if(session('status'))
        <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.2);border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:12px;color:#4ade80;">
            {{ session('status') }}
        </div>
        @endif

        {{-- Error --}}
        @if($errors->any())
        <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.2);border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:12px;color:#f87171;">
            @foreach($errors->all() as $err) {{ $err }}<br> @endforeach
        </div>
        @endif

        <form id="login-form" method="POST" action="{{ route('login') }}">
            @csrf

            <div style="margin-bottom:16px;">
                <label style="display:block;font-size:11.5px;font-weight:600;color:#8b9fc7;margin-bottom:6px;">Email Address</label>
                <input type="email" id="email-input" name="email" value="{{ old('email') }}" required autofocus
                    style="width:100%;background:#060e1f;border:1px solid rgba(255,255,255,0.08);border-radius:8px;padding:11px 14px;color:#e2e8f0;font-size:13px;outline:none;font-family:inherit;transition:border-color 0.2s;"
                    placeholder="admin@example.com"
                    onfocus="this.style.borderColor='rgba(59,130,246,0.5)'"
                    onblur="this.style.borderColor='rgba(255,255,255,0.08)'">
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:11.5px;font-weight:600;color:#8b9fc7;margin-bottom:6px;">Password</label>
                <input type="password" id="password-input" name="password" required
                    style="width:100%;background:#060e1f;border:1px solid rgba(255,255,255,0.08);border-radius:8px;padding:11px 14px;color:#e2e8f0;font-size:13px;outline:none;font-family:inherit;transition:border-color 0.2s;"
                    placeholder="••••••••"
                    onfocus="this.style.borderColor='rgba(59,130,246,0.5)'"
                    onblur="this.style.borderColor='rgba(255,255,255,0.08)'">
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                <label style="display:flex;align-items:center;gap:6px;cursor:pointer;">
                    <input type="checkbox" name="remember" style="accent-color:#3b82f6;">
                    <span style="font-size:12px;color:#8b9fc7;">Remember me</span>
                </label>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size:12px;color:#60a5fa;text-decoration:none;">Forgot password?</a>
                @endif
            </div>

            <button type="submit"
                style="width:100%;background:linear-gradient(135deg,#1d4ed8,#3b82f6);border:none;border-radius:12px;padding:13px;color:white;font-size:13.5px;font-weight:700;cursor:pointer;font-family:inherit;transition:all 0.2s;box-shadow:0 4px 16px rgba(59,130,246,0.3);"
                onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 20px rgba(59,130,246,0.4)';"
                onmouseout="this.style.transform='';this.style.boxShadow='0 4px 16px rgba(59,130,246,0.3)';">
                Sign In to Platform →
            </button>

            @if (Route::has('register'))
            <div style="text-align:center;margin-top:18px;font-size:12px;color:#4a6080;">
                Don't have an account?
                <a href="{{ route('register') }}" style="color:#60a5fa;text-decoration:none;font-weight:600;"> Create one</a>
            </div>
            @endif
        </form>

    </div>

    {{-- Footer note --}}
    <div style="text-align:center;margin-top:16px;font-size:10px;color:#4a6080;">
        © 2026 Global Supply Chain Risk Intelligence Platform
    </div>

</div>



</body>
</html>
