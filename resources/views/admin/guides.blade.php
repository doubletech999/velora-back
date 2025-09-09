@extends('layouts.admin')

@section('title', 'Guides Management - Velora Admin')
@section('page-title', 'Tour Guides Management')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <!-- Header -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">All Tour Guides</h3>
            <div class="flex space-x-2">
                <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">All Status</option>
                    <option value="approved">Approved</option>
                    <option value="pending">Pending</option>
                </select>
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add Guide
                </button>
            </div>
        </div>
    </div>

    <!-- Guides Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guide Info</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Languages</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rate</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($guides as $guide)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $guide->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white font-medium">{{ substr($guide->user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $guide->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $guide->user->email }}</div>
                                <div class="text-sm text-gray-500">{{ $guide->phone }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-wrap gap-1">
                            @foreach(explode(',', $guide->languages) as $language)
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ trim($language) }}
                            </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @if($guide->hourly_rate)
                            ${{ number_format($guide->hourly_rate, 2) }}/hour
                        @else
                            <span class="text-gray-400">Not set</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            @if($guide->is_approved) bg-green-100 text-green-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $guide->is_approved ? 'Approved' : 'Pending' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $guide->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900 transition-colors" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if(!$guide->is_approved)
                            <button class="text-green-600 hover:text-green-900 transition-colors" title="Approve">
                                <i class="fas fa-check"></i>
                            </button>
                            @endif
                            <button class="text-orange-600 hover:text-orange-900 transition-colors" title="Edit">
                                <i class="fas fa-edit"></i>
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
                        <i class="fas fa-user-tie text-4xl mb-2 text-gray-300"></i>
                        <p>No guides found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($guides->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $guides->links() }}
    </div>
    @endif
</div>

<!-- Guide Details Modal (Optional for future enhancement) -->
<div id="guideModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-bold text-gray-900">Guide Details</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">Guide information will be displayed here.</p>
            </div>
            <div class="items-center px-4 py-3">
                <button class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md hover:bg-gray-600">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection