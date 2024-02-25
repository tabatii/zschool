<!DOCTYPE html>
<html class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'Home' }}</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />
        @vite('resources/css/app.css')
        @laravelPWA
    </head>
    <body class="overflow-x-hidden">
        <header class="flex items-center h-32">
            <div class="container px-3">
                <div class="flex justify-between items-center">
                    <div>
                        <img src="{{ asset('assets/logo.png') }}" class="h-16" draggable="false" />
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="#login" @class([
                            'inline-flex bg-orange-500 hover:bg-orange-300 text-white text-sm font-medium',
                            'rounded transition-colors duration-500 px-4 py-2',
                        ])>
                            <span>Sign in</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>
        <main>{{ $slot }}</main>
        <footer class="bg-orange-500 py-4">
            <div class="container px-3">
                <div class="flex flex-wrap items-center gap-y-2 -mx-3">
                    <div class="w-full sm:w-6/12 px-3">
                        <p class="text-center sm:text-start text-white">Copyright &copy;2023 | All rights reserved</p>
                    </div>
                    <div class="w-full sm:w-6/12 px-3">
                        <ul class="flex flex-wrap justify-center sm:justify-end gap-2">
                            <li>
                                <a href="#" target="_blank" class="inline-flex justify-center items-center bg-white text-orange-500 rounded-full h-8 w-8">
                                    <i class="fa-brands fa-facebook-f"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" target="_blank" class="inline-flex justify-center items-center bg-white text-orange-500 rounded-full h-8 w-8">
                                    <i class="fa-brands fa-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" target="_blank" class="inline-flex justify-center items-center bg-white text-orange-500 rounded-full h-8 w-8">
                                    <i class="fa-brands fa-linkedin"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>