<x-app-layout>
    <div class="py-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Content Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row">
                        <!-- Left side: Breadcrumbs, Title, Description -->
                        <div class="w-full md:w-2/3 pr-4 mb-4 md:mb-0">
                            <!-- Breadcrumbs -->
                            <nav class="mb-4">
                                <ol class="list-reset flex text-sm">
                                    <li><a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-700">Home</a></li>
                                    <li><span class="text-gray-500 mx-2">/</span></li>
                                    <li><a href="{{ route('category.show', $category->slug) }}" class="text-blue-600 hover:text-blue-700">{{ $category->name }}</a></li>
                                    <li><span class="text-gray-500 mx-2">/</span></li>
                                    <li class="text-gray-500">{{ $state->name }}</li>
                                </ol>
                            </nav>
                            
                            <h1 class="text-3xl font-bold mb-6">{{ $state->name }}</h1>
                            
                            @if($state->description)
                                <div class="mb-6">
                                    {{ $state->description }}
                                </div>
                            @endif
                        </div>
                        <!-- Right side: State Image or Placeholder -->
                        <div class="w-full md:w-1/3">
                            <!-- Add state image or a placeholder here -->
                            <div class="bg-gray-200 h-48 flex items-center justify-center">
                                <span class="text-gray-500">State Image Placeholder</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Forms List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold mb-4">Available Forms</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($forms as $form)
                            <div class="border rounded p-4">
                                <h3 class="text-lg font-semibold mb-2">{{ $form->name }}</h3>
                                <p class="text-sm text-gray-600 mb-2">Downloads: {{ $form->downloads }}</p>
                                <div class="flex justify-between items-center">
                                    <a href="{{ route('forms.show', [$category->slug, $state->slug, $form->slug]) }}" class="text-blue-600 hover:text-blue-800">View</a>
                                    <a href="{{ route('forms.download', ['category' => $category->slug, 'state' => $state->slug, 'form' => $form->slug]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-sm">Download</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @auth
                @if(auth()->user()->is_admin)
                    <div class="mt-6 text-right">
                        <a href="{{ route('admin.states.edit', [$category->slug, $state->slug]) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Edit State
                        </a>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</x-app-layout>