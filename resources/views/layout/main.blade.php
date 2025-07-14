@include('layout.header')
<body>
    <div class="mx-auto flex flex-col items-center justify-center min-h-screen bg-gray-100 p-8">
        <main class="container mx-auto p-4 flex-grow">
            @yield('content')
        </main>

        @include('layout.footer')
    </div>
</body>