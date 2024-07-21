<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $category->name }}
            </h2>
            @auth
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.categories.edit', $category->slug) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit Category
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">States in this category:</h3>
                    <ul>
                        @foreach($states as $state)
                            <li class="mb-2">
                                <a href="{{ route('state.show', ['category' => $category->slug, 'state' => $state->slug]) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $state->name }}
                                </a>
                                @auth
                                    @if(auth()->user()->is_admin)
                                        <a href="{{ route('admin.states.edit', ['category' => $category, 'state' => $state]) }}" class="text-sm text-gray-600 hover:text-gray-800 ml-2">
                                            (Edit)
                                        </a>
                                    @endif
                                @endauth
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>