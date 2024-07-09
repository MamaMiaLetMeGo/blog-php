<x-app-layout>
    <!-- Full width banner -->
    <div class="relative bg-gray-900 overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                            <span class="block">Welcome to</span>
                            <span class="block text-indigo-600 xl:inline">Your Amazing Blog</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-300 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Discover insightful articles, engaging stories, and expert opinions on various topics.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="{{ route('posts.index') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                                    Read Blog
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <!-- Recent posts section -->
    <div class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                Recent Posts
            </h2>
            <div class="mt-12 grid gap-5 max-w-lg mx-auto lg:grid-cols-3 lg:max-w-none">
                @foreach ($recentPosts as $post)
                    <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                    <div class="flex-shrink-0">
                        <a href="{{ route('posts.show', $post->slug) }}">
                            @if($post->thumbnail && Storage::disk('public')->exists($post->thumbnail))
                                <img class="h-48 w-full object-cover" src="{{ Storage::url($post->thumbnail) }}" alt="{{ $post->title }}">
                            @else
                                <div class="h-48 w-full bg-gray-200 flex items-center justify-center text-gray-500">No Image</div>
                            @endif
                        </a>
                    </div>
                        <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                            <div class="flex-1">
                                <a href="{{ route('posts.show', $post->slug) }}" class="block mt-2">
                                    <p class="text-xl font-semibold text-gray-900">{{ $post->title }}</p>
                                    <p class="mt-3 text-base text-gray-500">{{ Str::limit($post->content, 100) }}</p>
                                </a>
                            </div>
                            <div class="mt-6 flex items-center">
                              <div class="flex-shrink-0">
                                  @if($post->user)
                                      <span class="sr-only">{{ $post->user->name }}</span>
                                      <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name) }}" alt="">
                                  @else
                                      <span class="sr-only">Anonymous</span>
                                      <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=Anonymous" alt="">
                                  @endif
                              </div>
                              <div class="ml-3">
                              <p class="text-sm font-medium text-gray-900">
                                  {{ $post->user ? $post->user->name : 'Anonymous' }}
                              </p>
                                  <div class="flex space-x-1 text-sm text-gray-500">
                                      <time datetime="{{ $post->published_at->toDateString() }}">
                                          {{ $post->published_at->format('M d, Y') }}
                                      </time>
                                      <span aria-hidden="true">&middot;</span>
                                      <span>{{ $post->readTime() }} min read</span>
                                  </div>
                              </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>