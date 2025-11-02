<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FLDM Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #003366;
            --primary-dark: #002244;
            --accent-color: #4A90E2;
            --accent-light: #6BA4E8;
            --light-gray: #f8f9fa;
            --text-color: #333;
            --error-color: #e74c3c;
            --success-color: #2ecc71;
            --transition-speed: 0.3s;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: 
                linear-gradient(135deg, #e6f0fa 0%, #b3cde0 100%),
                url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 0h100v100H0z' fill='%23003366' fill-opacity='0.03'/%3E%3Cpath d='M20 20h60v60H20z' stroke='%23003366' stroke-width='0.5' stroke-opacity='0.05' fill='none'/%3E%3Cpath d='M30 30h40v40H30z' stroke='%23003366' stroke-width='0.5' stroke-opacity='0.05' fill='none'/%3E%3C/svg%3E"),
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23003366' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: var(--text-color);
            position: relative;
            overflow-x: hidden;
        }

        body::before,
        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        body::before {
            background: 
                radial-gradient(circle at 15% 25%, rgba(74, 144, 226, 0.4) 0%, transparent 15%),
                radial-gradient(circle at 85% 65%, rgba(74, 144, 226, 0.4) 0%, transparent 15%),
                radial-gradient(circle at 50% 10%, rgba(255, 255, 255, 0.3) 0%, transparent 15%);
            opacity: 0.6;
            animation: drift 25s linear infinite;
        }

        body::after {
            background: 
                radial-gradient(circle at 30% 80%, rgba(0, 51, 102, 0.35) 0%, transparent 12%),
                radial-gradient(circle at 70% 20%, rgba(255, 255, 255, 0.25) 0%, transparent 12%),
                radial-gradient(circle at 10% 50%, rgba(74, 144, 226, 0.35) 0%, transparent 12%),
                url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23003366' fill-opacity='0.03' fill-rule='evenodd'%3E%3Ccircle cx='3' cy='3' r='3'/%3E%3Ccircle cx='13' cy='13' r='3'/%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
            animation: driftReverse 30s linear infinite;
        }

        @keyframes drift {
            0% { transform: translate(0, 0); }
            100% { transform: translate(100px, 100px); }
        }

        @keyframes driftReverse {
            0% { transform: translate(0, 0); }
            100% { transform: translate(-100px, -100px); }
        }

        .container {
            display: flex;
            width: 85%;
            max-width: 1100px;
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            animation: fadeIn 0.8s ease-out;
            position: relative;
            z-index: 1;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .left {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            background: 
                linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%),
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            position: relative;
            overflow: hidden;
        }

        .left::before {
            content: '';
            position: absolute;
            width: 250%;
            height: 250%;
            background: 
                radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 70% 70%, rgba(255, 255, 255, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 50% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 20%);
            transform: rotate(30deg);
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: rotate(30deg) translate(0, 0); }
            50% { transform: rotate(30deg) translate(-10px, -10px); }
        }

        .left img {
            max-width: 85%;
            height: auto;
            z-index: 1;
            filter: drop-shadow(0 8px 20px rgba(0, 0, 0, 0.3));
            transition: transform var(--transition-speed);
        }

        .left:hover img {
            transform: translateY(-5px);
        }

        .right {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: white;
            position: relative;
            overflow: hidden;
        }

        .right::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: 
                url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 0h100v100H0z' fill='none'/%3E%3Cpath d='M0 0l100 100' stroke='%234A90E2' stroke-width='0.3' stroke-opacity='0.03'/%3E%3Cpath d='M100 0L0 100' stroke='%234A90E2' stroke-width='0.3' stroke-opacity='0.03'/%3E%3Cpath d='M50 0v100' stroke='%234A90E2' stroke-width='0.3' stroke-opacity='0.03'/%3E%3Cpath d='M0 50h100' stroke='%234A90E2' stroke-width='0.3' stroke-opacity='0.03'/%3E%3C/svg%3E"),
                url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23003366' fill-opacity='0.02' fill-rule='evenodd'%3E%3Cpath d='M20 0c11.046 0 20 8.954 20 20s-8.954 20-20 20S0 31.046 0 20 8.954 0 20 0zm0 4C11.163 4 4 11.163 4 20s7.163 16 16 16 16-7.163 16-16S28.837 4 20 4z'/%3E%3C/g%3E%3C/svg%3E");
            z-index: 0;
            opacity: 0.6;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .login-box h2 {
            color: var(--primary-color);
            margin-bottom: 40px;
            text-align: center;
            font-size: 30px;
            font-weight: 700;
            position: relative;
            letter-spacing: 0.5px;
        }

        .login-box h2::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: linear-gradient(to right, var(--accent-color), var(--primary-dark));
            margin: 15px auto 0;
            border-radius: 4px;
            transition: width var(--transition-speed);
        }

        .login-box:hover h2::after {
            width: 80px;
        }

        .input-group {
            position: relative;
            margin-bottom: 32px;
        }

        .input-group input {
            width: 100%;
            padding: 18px 20px 18px 50px;
            border: 2px solid #e1e5eb;
            border-radius: 10px;
            font-size: 16px;
            outline: none;
            transition: all var(--transition-speed);
            background-color: var(--light-gray);
            letter-spacing: 0.5px;
        }

        .input-group input:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(74, 144, 226, 0.15);
            background-color: white;
            transform: translateY(-2px);
        }

        .input-group i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #7b8793;
            transition: color var(--transition-speed);
            font-size: 18px;
        }

        .input-group input:focus + i {
            color: var(--accent-color);
        }

        .invalid-feedback {
            display: block;
            color: var(--error-color);
            font-size: 14px;
            margin-top: 8px;
            font-weight: 500;
            padding-left: 5px;
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
            font-size: 15px;
        }

        .options label {
            display: flex;
            align-items: center;
            color: #555;
            cursor: pointer;
            font-weight: 500;
        }

        .options input {
            margin-right: 10px;
            cursor: pointer;
            width: 16px;
            height: 16px;
        }

        .options a {
            color: var(--primary-color);
            text-decoration: none;
            transition: all var(--transition-speed);
            font-weight: 600;
            position: relative;
        }

        .options a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-color);
            transition: width var(--transition-speed);
        }

        .options a:hover {
            color: var(--accent-color);
        }

        .options a:hover::after {
            width: 100%;
        }

        button[type="submit"] {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-speed);
            box-shadow: 0 5px 15px rgba(0, 51, 102, 0.25);
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        button[type="submit"]::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.7s ease;
        }

        button[type="submit"]:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 51, 102, 0.35);
        }

        button[type="submit"]:hover::before {
            left: 100%;
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }

        /* Responsive design */
        @media (max-width: 992px) {
            .container {
                width: 90%;
            }
            
            .right {
                padding: 50px 40px;
            }
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                width: 85%;
                max-width: 450px;
                border-radius: 15px;
            }

            .left {
                display: none;
            }
            
            .right {
                padding: 40px 30px;
            }
        }

        @media (max-width: 480px) {
            .container {
                width: 95%;
                border-radius: 12px;
            }
            
            .right {
                padding: 30px 25px;
            }
            
            .options {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .options a {
                margin-top: 5px;
            }
            
            .login-box h2 {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left">
            <img src="{{ asset('images/login-image.png') }}" alt="Login Image">
        </div>

        <div class="right">
            <form method="POST" action="{{ route('login') }}" class="login-box">
                @csrf
                <h2>Welcome to FLDM Platform</h2>

                <div class="input-group">
                    <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email Address">
                    <i class="fas fa-envelope"></i>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="input-group">
                    <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                    <i class="fas fa-lock"></i>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="options">
                    <label>
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        Remember Me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Forgot Password?</a>
                    @endif
                </div>

                <button type="submit">Sign In</button>
            </form>
        </div>
    </div>
</body>
</html>