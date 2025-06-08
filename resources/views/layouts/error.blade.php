<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lỗi hệ thống')</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: "Segoe UI", sans-serif;
        }

        .error-box {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
            max-width: 500px;
            width: 100%;
        }

        .error-code {
            font-size: 5rem;
            font-weight: bold;
            color: #dc3545;
        }

        .error-message {
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }
    </style>

    @yield('styles')
</head>

<body>
    <div class="error-box text-center">
        @yield('content')
    </div>

    @yield('scripts')
</body>

</html>
