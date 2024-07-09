<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row">
                <div class="md:w-3/4 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h1 class="text-3xl font-bold mb-2">{{ $post->title }}</h1>
                        
                        <nav class="text-gray-500 text-sm mb-4" aria-label="Breadcrumb">
                            <ol class="list-none p-0 inline-flex">
                                <li class="flex items-center">
                                    <a href="{{ route('home') }}">Home</a>
                                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
                                </li>
                                <li class="flex items-center">
                                    <a href="{{ route('posts.index') }}">Blog</a>
                                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
                                </li>
                                <li>
                                    <span class="text-gray-700" aria-current="page">{{ $post->title }}</span>
                                </li>
                            </ol>
                        </nav>

                        <div class="text-gray-600 mb-4">
                            Published on {{ $post->published_at->format('F d, Y') }} by {{ $post->user->name ?? 'Unknown' }}
                        </div>

                        <div class="prose max-w-none" id="post-content">
                            {!! $post->content !!}
                        </div>

                        @if(auth()->user() && auth()->user()->is_admin)
                            <div class="mt-4">
                                <a href="{{ route('admin.posts.edit', $post) }}" class="text-indigo-600 hover:text-indigo-900">Edit Post</a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="md:w-1/4 md:ml-4 mt-4 md:mt-0">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 sticky top-4">
                        <h2 class="text-lg font-semibold mb-2">Table of Contents</h2>
                        <nav id="toc" class="text-sm"></nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const content = document.getElementById('post-content');
            const toc = document.getElementById('toc');
            const headings = content.querySelectorAll('h1');
            const tocLinks = [];

            headings.forEach((heading, index) => {
                const id = `heading-${index}`;
                heading.id = id;
                const link = document.createElement('a');
                link.href = `#${id}`;
                link.textContent = heading.textContent;
                link.classList.add('block', 'mb-2', 'text-gray-700', 'hover:text-indigo-600');
                tocLinks.push(link);
                toc.appendChild(link);
            });

            function highlightTOC() {
                const scrollPosition = window.scrollY;

                headings.forEach((heading, index) => {
                    if (heading.offsetTop <= scrollPosition + 100) {
                        tocLinks.forEach(link => link.classList.remove('font-bold', 'text-indigo-600'));
                        tocLinks[index].classList.add('font-bold', 'text-indigo-600');
                    }
                });
            }

            window.addEventListener('scroll', highlightTOC);
            highlightTOC();
        });
    </script>
    @endpush
</x-app-layout>