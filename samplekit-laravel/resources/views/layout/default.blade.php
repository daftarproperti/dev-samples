<!doctype html>
<html lang="en">
    <head>
        <title>@if(isset($title)) {{ $title }}  @endif</title>

        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="icon" type="image/x-icon" href="">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            :root {
                --bs-body-font-size: 14px; /* CSS variable for font size */
            }

            .btn{
                --bs-btn-font-size: var(--bs-body-font-size);
            }

            #filter-form label i {
                min-width: 20px;
            }

            .page-link {
                --bs-pagination-font-size: var(--bs-body-font-size);
            }

            .form-select,
            .form-control {
                font-size: var(--bs-body-font-size);
            }

            /* Custom CSS for glowing effect */
            .btn-glow {
              position: relative;
              animation: glow-animation 0.5s infinite alternate;
            }

            @keyframes glow-animation {
              0% {
                box-shadow: 0 0 3px rgba(0, 123, 255, 0.5);
              }
              100% {
                box-shadow: 0 0 8px rgba(0, 123, 255, 0.9), 0 0 12px rgba(0, 123, 255, 0.7);
              }
            }
        </style>
        <link rel="stylesheet" href="{{ asset('css/default.css') }}"/>
        @if(config('app.theme_color') == 'red')
            <link rel="stylesheet" href="{{ asset('css/red-theme.css') }}"/>
        @endif

        @yield('styles')
    </head>
    <body>
        <header class="navbar navbar-expand-md navbar-dark bg-dark sticky-top" style="z-index: 2000;">
            <div class="container-xxl">
                @include('includes.header')
            </div>
        </header>

        <main class="py-4">
            <div class="container-xxl">
                @yield('content')
            </div>
        </main>

        <footer class="footer mt-auto py-5 bg-light">
            <div class="container-xxl">
                <div class="d-flex justify-content-between">
                    @include('includes.footer')
                </div>
            </div>
        </footer>

        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        @yield('modals')
        @yield('scripts')
    </body>
</html>
