@extends('layout.main')
@section('title', 'Productos')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Productos de la categoría: <strong class="text-[#0074FF]">{{ $category->name }}</strong></h1>

    <a href="/" class="mb-4 inline-flex items-center bg-white border border-[#0074FF] text-[#0074FF] hover:bg-[#0074FF] hover:text-white font-medium py-1.5 px-4 rounded-md text-sm transition duration-150">
        &larr; Volver
    </a>

    @if ($products->isEmpty())
        <p class="text-gray-500">No hay productos disponibles.</p>
    @else
        <div class="max-w-6xl bg-white shadow-md rounded-lg overflow-hidden">
            <div class="grid grid-cols-5 gap-4 bg-[#0074FF] p-4 font-semibold text-white">
                <div>Nombre</div>
                <div>Descripción</div>
                <div>Precio</div>
                <div>Posición</div>
                <div>Estado</div>
            </div>
            @foreach ($products as $product)
                <div class="grid grid-cols-5 gap-4 p-4 border-b ">
                    <div class="text-gray-800">{{ $product->name }}</div>
                    <div class="text-gray-600">{{ $product->description }}</div>
                    <div class="text-gray-600">S/{{ $product->price }}</div>
                    <div class="text-gray-600">{{ $product->position }}</div>
                    @if($product->status === 'enabled')
                        <span class="h-5 w-5 rounded-full bg-green-500"></span>
                    @else
                        <span class="h-5 w-5 rounded-full bg-red-500"></span>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
@endsection