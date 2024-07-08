<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $post->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($post->thumbnail)
                        <img src="{{ asset('storage/' . $post->thumbnail) }}" alt="{{ $post->title }}" class="mb-4 max-w-full h-auto">
                    @endif

                    <h1 class="text-3xl font-bold mb-4">{{ $post->title }}</h1>
                    
                    <div class="text-gray-600 mb-4">
                        Published on {{ $post->published_at->format('F d, Y') }}
                    </div>

                    <div class="prose max-w-none">
                        {!! $post->content !!}
                    </div>

                    @if(auth()->user() && auth()->user()->is_admin)
                        <div class="mt-4">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="text-indigo-600 hover:text-indigo-900">Edit Post</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>