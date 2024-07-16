<x-app-layout>
    <h1>{{ $category->name }}</h1>
    <p>{{ $category->description }}</p>

    <h2>Subcategories</h2>
    <ul>
        @foreach($category->subcategories as $subcategory)
            <li>
                <a href="{{ route('subcategory.show', [$category->slug, $subcategory->slug]) }}">
                    {{ $subcategory->name }}
                </a>
            </li>
        @endforeach
    </ul>

    @if(auth()->user() && auth()->user()->isAdmin())
        <a href="{{ route('admin.category.edit', $category->slug) }}" class="btn btn-primary">Edit Category</a>
    @endif
</x-app-layout>