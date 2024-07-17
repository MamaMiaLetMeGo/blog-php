<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $form->name }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="mb-4">
                <ol class="list-reset flex text-sm">
                    <li><a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-700">Home</a></li>
                    <li><span class="text-gray-500 mx-2">/</span></li>
                    <li><a href="{{ route('category.show', $category->slug) }}" class="text-blue-600 hover:text-blue-700">{{ $category->name }}</a></li>
                    <li><span class="text-gray-500 mx-2">/</span></li>
                    <li><a href="{{ route('state.show', ['category' => $category->slug, 'state' => $state->slug]) }}" class="text-blue-600 hover:text-blue-700">{{ $state->name }}</a></li>
                    <li><span class="text-gray-500 mx-2">/</span></li>
                    <li class="text-gray-500">{{ $form->name }}</li>
                </ol>
            </nav>

            <!-- Content Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row">
                        <!-- Left side: Title, Content Header, Download Button -->
                        <div class="w-full md:w-2/3 pr-4 mb-4 md:mb-0">
                            <h1 class="text-3xl font-bold mb-4">{{ $form->name }}</h1>
                            @if($form->content_header)
                                <div class="mb-4">
                                    {!! $form->content_header !!}
                                </div>
                            @else
                                <p class="mb-4">No description available for this form.</p>
                            @endif
                        </div>
                        <!-- Right side: PDF Thumbnail -->
                        <div class="w-full md:w-1/3">
                            @if($form->file_path)
                                <div id="pdf-container" class="w-full relative" style="height: 200px;">
                                    <div id="pdf-loading" class="absolute inset-0 flex items-center justify-center bg-gray-100">
                                        <svg class="animate-spin h-10 w-10 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                    <canvas id="pdf-preview" class="w-full h-auto hidden" style="max-height: 200px; object-fit: contain;"></canvas>
                                </div>
                            @else
                                <p>No preview available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content and Sidebar -->
            <div class="flex flex-col md:flex-row">
                <!-- Main content area -->
                <div class="w-full md:w-3/4 md:pr-4 mb-6 md:mb-0">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="prose max-w-none">
                                {!! $form->content !!}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="w-full md:w-1/4 md:pl-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <h4 class="text-lg font-semibold mb-2">Additional Information</h4>
                            <!-- Add any additional sidebar content here -->
                            @auth
                                @if(auth()->user()->is_admin)
                                    <div class="mt-2 text-center">
                                        <a href="{{ route('admin.forms.edit', $form) }}" class="inline-block bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                            Edit Page
                                        </a>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($form->file_path)
        @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
        <script>
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.worker.min.js';

            const url = '{{ asset('storage/' . $form->file_path) }}';

            pdfjsLib.getDocument(url).promise.then(function(pdf) {
                pdf.getPage(1).then(function(page) {
                    const scale = 2 * window.devicePixelRatio; // Increase scale for better resolution
                    const viewport = page.getViewport({ scale: scale });

                    const canvas = document.getElementById('pdf-preview');
                    const context = canvas.getContext('2d');

                    // Set canvas size to match the viewport
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Set canvas CSS size to fit the sidebar
                    canvas.style.width = '100%';
                    canvas.style.height = 'auto';

                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    page.render(renderContext).promise.then(() => {
                        document.getElementById('pdf-loading').classList.add('hidden');
                        document.getElementById('pdf-preview').classList.remove('hidden');
                    });
                });
            });
        </script>
        @endpush
    @endif
</x-app-layout>