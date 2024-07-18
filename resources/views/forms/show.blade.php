<x-app-layout>
    <div class="py-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Content Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row">
                        <!-- Left side: Breadcrumbs, Title, Content Header, Download Button -->
                        <div class="w-full md:w-2/3 pr-4 mb-4 md:mb-0">
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
                            
                            <h1 class="text-3xl font-bold mb-6">{{ $form->name }}</h1>
                            
                            @if($form->content_header)
                                <div class="mb-6">
                                    {!! $form->content_header !!}
                                </div>
                            @else
                                <p class="mb-6">No description available for this form.</p>
                            @endif
                            
                            @if($form->file_path)
                                <div class="mb-4">
                                    <a href="{{ route('forms.download', ['category' => $category->slug, 'state' => $state->slug, 'form' => $form->slug]) }}" 
                                       class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Download Form
                                    </a>
                                </div>
                                <div class="text-md text-gray-600 flex items-center space-x-4">
                                    <span>Downloads: <span class="download-count font-semibold">{{ $form->downloads }}</span></span>
                                    <!-- Add other information here as needed -->
                                </div>
                            @endif
                        </div>
                        <!-- Right side: PDF Thumbnail -->
                        <div class="w-full md:w-1/3">
                            @if($form->file_path)
                                <div id="pdf-container" class="w-full relative overflow-hidden cursor-pointer" style="height: 300px;" onclick="openPdfModal()">
                                    <div id="pdf-loading" class="absolute inset-0 flex items-center justify-center bg-gray-100">
                                        <svg class="animate-spin h-10 w-10 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                    <canvas id="pdf-preview" class="w-full h-full hidden object-contain"></canvas>
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
                            <div class="prose max-w-none" id="form-content">
                                {!! $form->content !!}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="w-full md:w-1/4 md:pl-4">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky top-4">
                        <div class="p-4">
                            <h4 class="text-lg font-semibold mb-2">Table of Contents</h4>
                            <nav id="toc" class="space-y-1 max-h-[50vh] overflow-y-auto pr-2"></nav>
                            
                            <h4 class="text-lg font-semibold mb-2 mt-4">Additional Information</h4>
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
            let pdfDoc = null;

            function renderPage(pageNum, canvas, scale = 1.5) {
                pdfDoc.getPage(pageNum).then(function(page) {
                    const viewport = page.getViewport({ scale: scale });
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
                    const renderContext = {
                        canvasContext: canvas.getContext('2d'),
                        viewport: viewport
                    };
                    page.render(renderContext);
                });
            }

            function loadPdfPreview() {
                pdfjsLib.getDocument(url).promise.then(function(pdf) {
                    pdfDoc = pdf;
                    const previewCanvas = document.getElementById('pdf-preview');
                    const container = document.getElementById('pdf-container');
                    const scale = container.clientWidth / 595; // Assuming A4 width
                    renderPage(1, previewCanvas, scale);
                    document.getElementById('pdf-loading').classList.add('hidden');
                    previewCanvas.classList.remove('hidden');
                }).catch(function(error) {
                    console.error('Error loading PDF:', error);
                });
            }

            function openPdfModal() {
                const modal = document.getElementById('pdf-modal');
                modal.classList.remove('hidden');
                const canvas = document.getElementById('pdf-full-preview');
                renderPage(1, canvas, 2); // Larger scale for the modal view

                // Set the download link
                const downloadLink = document.getElementById('pdf-download-link');
                downloadLink.href = "{{ route('forms.download', ['category' => $category->slug, 'state' => $state->slug, 'form' => $form->slug]) }}";
                downloadLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    fetch(this.href)
                        .then(response => response.blob())
                        .then(blob => {
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.style.display = 'none';
                            a.href = url;
                            a.download = '{{ $form->name }}.pdf';
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                            // Update the download count on the page
                            const downloadCount = document.querySelector('.download-count');
                            if (downloadCount) {
                                const newCount = parseInt(downloadCount.textContent) + 1;
                                document.querySelectorAll('.download-count').forEach(el => {
                                    el.textContent = newCount;
                                });
                            }
                        });
                });
            }

            function closePdfModal() {
                const modal = document.getElementById('pdf-modal');
                modal.classList.add('hidden');
            }

            // Close modal when clicking outside of it
            window.onclick = function(event) {
                const modal = document.getElementById('pdf-modal');
                if (event.target == modal) {
                    closePdfModal();
                }
            }

            // Load the preview when the page is ready
            document.addEventListener('DOMContentLoaded', loadPdfPreview);
        </script>
        @endpush
    @endif
    <!-- PDF Modal -->
    <div id="pdf-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="flex justify-between items-center mb-4">
                    <div class="w-1/3"></div> <!-- Empty div for spacing -->
                    <div class="w-1/3">
                        <a id="pdf-download-link" href="#" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" download>
                            Download PDF
                        </a>
                    </div>
                    <div class="w-1/3 flex justify-end">
                        <button onclick="closePdfModal()" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div id="pdf-modal-content" class="mt-2 overflow-y-auto" style="max-height: 80vh;">
                    <canvas id="pdf-full-preview"></canvas>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>