@props(['post'])

<article class="group relative bg-white rounded-2xl border border-gray-100 hover:border-emerald-200 shadow-sm hover:shadow-emerald-100 hover:shadow-lg transition-all duration-300 overflow-hidden">

    {{-- Top accent line --}}
    <div class="h-1 w-full bg-gradient-to-r from-emerald-400 to-teal-500"></div>

    <div class="p-6">
        {{-- Status Badge --}}
        <div class="flex items-center justify-between mb-3">
            @if($post->status && $post->status->value === 'draft')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">
                    ● Draft
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                    ● Published
                </span>
            @endif
            <span class="text-xs text-gray-400">{{ $post->created_at->format('M d, Y') }}</span>
        </div>

        {{-- Title --}}
        <h2 class="text-lg font-bold text-gray-900 mb-3 leading-snug group-hover:text-emerald-700 transition-colors duration-200">
            <a href="{{ route('posts.show', $post->slug) }}">
                {{ $post->title }}
            </a>
        </h2>

        {{-- Excerpt --}}
        <p class="text-gray-500 text-sm leading-relaxed mb-5">
            {{ Str::limit(strip_tags($post->body), 120, '...') }}
        </p>

        {{-- Footer --}}
        <div class="flex items-center justify-between pt-4 border-t border-gray-50">
            <div class="flex items-center space-x-2">
                <div class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center">
                    <span class="text-xs font-bold text-emerald-700">
                        {{ substr($post->user->name, 0, 1) }}
                    </span>
                </div>
                <span class="text-xs font-medium text-gray-600">{{ $post->user->name }}</span>
            </div>

            <div class="flex items-center space-x-3 text-xs text-gray-400">
                <span class="flex items-center space-x-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <span>{{ number_format($post->view_count) }}</span>
                </span>

                <a href="{{ route('posts.show', $post->slug) }}"
                   class="flex items-center space-x-1 text-emerald-600 font-medium hover:text-emerald-700">
                    <span>Read</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</article>
