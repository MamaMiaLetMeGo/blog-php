<x-app-layout>
    <h1>{{ $category->name }}</h1>
    <p>{{ $category->description }}</p>


    @if(auth()->user() && auth()->user()->isAdmin())
        <a href="{{ route('admin.category.edit', $category->slug) }}" class="btn btn-primary">Edit Category</a>
    @endif
</x-app-layout>