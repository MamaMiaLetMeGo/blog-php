<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(auth()->user() && auth()->user()->is_admin)
                        <a href="{{ route('admin.posts.create') }}" class="mb-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create New Post
                        </a>
                    @endif

                    @foreach ($posts as $post)
                        <div class="mb-4 p-4 border rounded">
                            <h3 class="text-lg font-semibold">
                                <a href="{{ route('posts.show', $post->slug) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            <p class="text-gray-600">{{ Str::limit($post->content, 100) }}</p>
                            <p class="text-sm text-gray-500 mt-2">Published: {{ $post->published_at->format('M d, Y') }}</p>
                            
                            @if(auth()->user() && auth()->user()->is_admin)
                                <div class="mt-2">
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>