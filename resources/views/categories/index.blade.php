@extends('layout.main')
@section('title', 'Categorías')

@section('content')
    <h1 class="text-2xl font-bold mb-4 text-[#0074FF]">Categorías</h1>

    @if ($categories->isEmpty())
        <p class="text-gray-500">No hay categorías disponibles.</p>
    @else
        <div class="max-w-6xl bg-white shadow-md rounded-lg overflow-hidden">
            <div class="grid grid-cols-5 gap-4 bg-[#0074FF] p-4 font-semibold text-white">
                <div>Nombre</div>
                <div>Descripción</div>
                <div>Posición</div>
                <div>Estado</div>
                <div>Acción</div>
            </div>
            @foreach ($categories as $category)
                <div class="grid grid-cols-5 gap-4 p-4 border-b">
                    <div class="text-gray-800">{{ $category->name }}</div>
                    <div class="text-gray-600">{{ $category->description }}</div>
                    <div class="text-gray-600">{{ $category->position }}</div>
                    @if($category->status === 'enabled')
                        <span class="h-5 w-5 rounded-full bg-green-500"></span>
                    @else
                        <span class="h-5 w-5 rounded-full bg-red-500"></span>
                    @endif
                    <div>
                        @if($category->products->isNotEmpty())
                            <a href="{{ route('products', $category->id) }}" class="bg-[#0074FF] hover:bg-[#0066e6] text-white font-medium py-1.5 px-4 rounded-md text-sm">Productos</a>

                        @endif
                        @if($category->services->isNotEmpty())
                            <a href="{{ route('services', $category->id) }}" class="border border-[#0074FF] text-[#0074FF] hover:bg-[#0074FF] hover:text-white font-medium py-1.5 px-4 rounded-md text-sm">Servicios</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection