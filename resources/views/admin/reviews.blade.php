@extends('layouts.admin')

@section('title', 'Reviews Management - Velora Admin')
@section('page-title', 'Reviews & Ratings Management')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <!-- Header -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">All Reviews & Ratings</h3>
            <div class="flex space-x-2">
                <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">All Types</option>
                    <option value="site">Site Reviews</option>
                    <option value="guide">Guide Reviews</option>
                </select>
                <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">All Ratings</option>
                    <option value="5">5 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="2">2 Stars</option>
                    <option value="1">1 Star</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Reviews Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reviewer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($reviews as $review)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $review->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-yellow-600 rounded-full flex items-center justify-center mr-2">
                                <span class="text-white text-xs font-medium">{{ substr($review->user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $review->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $review->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($review->site)
                            <div>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 mb-1">
                                    Site Review
                                </span>
                                <div class="text-sm font-medium text-gray-900">{{ $review->site->name }}</div>
                                <div class="text-xs text-gray-500">{{ ucfirst($review->site->type) }} Site</div>
                            </div>
                        @elseif($review->guide)
                            <div>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 mb-1">
                                    Guide Review
                                </span>
                                <div class="text-sm font-medium text-gray-900">{{ $review->guide->user->name }}</div>
                                <div class="text-xs text-gray-500">Tour Guide</div>
                            </div>
                        @else
                            <span class="text-gray-400">Unknown Target</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex items-center mr-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fas fa-star text-yellow-400"></i>
                                    @else
                                        <i class="fas fa-star text-gray-300"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $review->rating }}/5</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($review->comment)
                            <div class="text-sm text-gray-900 max-w-xs">
                                {{ Str::limit($review->comment, 100) }}
                            </div>
                        @else
                            <span class="text-gray-400 text-sm">No comment</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $review->created_at->format('M d, Y') }}
                        <div class="text-xs text-gray-400">{{ $review->created_at->format('h:i A') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900 transition-colors" title="View Full Review">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-orange-600 hover:text-orange-900 transition-colors" title="Moderate">
                                <i class="fas fa-shield-alt"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-900 transition-colors" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        <i class="fas fa-star text-4xl mb-2 text-gray-300"></i>
                        <p>No reviews found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($reviews->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $reviews->links() }}
    </div>
    @endif
</div>

<!-- Reviews Statistics -->
<div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
    @for($rating = 5; $rating >= 1; $rating--)
    <div class="bg-white rounded-lg shadow-md p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                @for($i = 1; $i <= $rating; $i++)
                    <i class="fas fa-star text-yellow-400 text-sm"></i>
                @endfor
                @for($i = $rating + 1; $i <= 5; $i++)
                    <i class="fas fa-star text-gray-300 text-sm"></i>
                @endfor
            </div>
            <span class="text-lg font-bold text-gray-700">
                {{ $reviews->where('rating', $rating)->count() }}
            </span>
        </div>
        <div class="mt-2">
            <div class="w-full bg-gray-200 rounded-full h-2">
                @php
                    $total = $reviews->count();
                    $count = $reviews->where('rating', $rating)->count();
                    $percentage = $total > 0 ? ($count / $total) * 100 : 0;
                @endphp
                <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
            </div>
        </div>
    </div>
    @endfor
</div>

<!-- Average Rating Card -->
<div class="mt-4 bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center justify-center">
        <div class="text-center">
            <div class="text-4xl font-bold text-gray-800">
                @if($reviews->count() > 0)
                    {{ number_format($reviews->avg('rating'), 1) }}
                @else
                    0.0
                @endif
            </div>
            <div class="flex items-center justify-center mt-2">
                @php $avgRating = $reviews->count() > 0 ? round($reviews->avg('rating')) : 0; @endphp
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $avgRating)
                        <i class="fas fa-star text-yellow-400 text-xl"></i>
                    @else
                        <i class="fas fa-star text-gray-300 text-xl"></i>
                    @endif
                @endfor
            </div>
            <div class="text-gray-600 mt-2">
                Average Rating from {{ $reviews->count() }} reviews
            </div>
        </div>
    </div>
</div>
@endsection