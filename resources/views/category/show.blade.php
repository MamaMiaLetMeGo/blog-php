<x-app-layout>
    <h1>{{ $category->name }}</h1>
    <p>{{ $category->description }}</p>

    @auth
        @if(auth()->user()->is_admin)
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary">Edit Category</a>
        @endif
    @endauth

    <!-- Add the list of states or other category-related content here -->
</x-app-layout>