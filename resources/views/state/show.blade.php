<x-app-layout>
<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $state->name }}
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">{{ $state->description }}</h3>
    
                    <p>Category: <a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a></p>

                    <h2>Forms</h2>
                    <ul>
                        @foreach($forms as $form)
                            <li>
                                <a href="{{ route('forms.show', [$category->slug, $state->slug, $form->slug]) }}">
                                    {{ $form->name }}
                                </a>
                            </li>
                        @endforeach
                        <a href="{{ route('admin.states.edit', [$category->slug, $state->slug]) }}" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit State</a>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>