<!doctype html>
<html lang="en">
    <head>
        <title>@if(isset($title)) {{ $title }}  @endif</title>

        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

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

            .page-link {
                --bs-pagination-font-size: var(--bs-body-font-size);
            }

            .form-select,
            .form-control {
                font-size: var(--bs-body-font-size);
            }
        </style>
        @yield('styles')
    </head>
    <body>
        <header class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
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
            <div class="text-center">
                {{ config('app.name') }}
            </div>
        </footer>

        <!-- Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        @yield('modals')
        @yield('scripts')
    </body>
</html>
