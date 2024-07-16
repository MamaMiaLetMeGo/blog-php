<x-app-layout>
    <h1>{{ $subcategory->name }}</h1>
    <p>{{ $subcategory->description }}</p>
    <p>Category: <a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a></p>

    <h2>States</h2>
    <ul>
        @foreach($states as $state)
            <li>
                <a href="{{ route('state.show', [$category->slug, $subcategory->slug, $state->slug]) }}">
                    {{ $state->name }}
                </a>
            </li>
        @endforeach
    </ul>

    <a href="{{ route('subcategory.edit', [$category->slug, $subcategory->slug]) }}" class="btn btn-primary">Edit Subcategory</a>
</x-app-layout>