<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $form->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">{{ $form->name }}</h3>
                    <p>Category: {{ $category->name }}</p>
                    <p>Subcategory: {{ $subcategory->name }}</p>
                    <p>State: {{ $state->name }}</p>
                    
                    @if($form->file_path)
                        <a href="{{ asset('storage/' . $form->file_path) }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Download Form
                        </a>
                    @endif

                    <div class="mt-6">
                        <h4 class="text-lg font-semibold mb-2">Content</h4>
                        <div class="prose max-w-none">
                            {!! $form->content !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>