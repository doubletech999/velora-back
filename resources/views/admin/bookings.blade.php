@extends('layouts.admin')

@section('title', 'Bookings Management - Velora Admin')
@section('page-title', 'Bookings Management')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <!-- Header -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">All Guide Bookings</h3>
            <div class="flex space-x-2">
                <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <input type="date" class="border border-gray-300 rounded-lg px-3 py-2 text-sm" placeholder="Filter by date">
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guide</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-2">
                                <span class="text-white text-xs font-medium">{{ substr($booking->user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center mr-2">
                                <span class="text-white text-xs font-medium">{{ substr($booking->guide->user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $booking->guide->user->name }}</div>
                                <div class="text-xs text-gray-500">${{ number_format($booking->guide->hourly_rate, 2) }}/hour</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $booking->booking_date->format('M d, Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ date('g:i A', strtotime($booking->start_time)) }} - 
                                {{ date('g:i A', strtotime($booking->end_time)) }}
                            </div>
                            @php
                                $isToday = $booking->booking_date->isToday();
                                $isPast = $booking->booking_date->isPast();
                                $isFuture = $booking->booking_date->isFuture();
                            @endphp
                            @if($isToday)
                                <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded bg-green-100 text-green-800">Today</span>
                            @elseif($isFuture)
                                <span class="inline-flex px-1 py-0.5 text-xs font-semibold rounded bg-blue-100 text-blue-800">Upcoming</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @php
                            $startTime = \Carbon\Carbon::createFromFormat('H:i:s', $booking->start_time);
                            $endTime = \Carbon\Carbon::createFromFormat('H:i:s', $booking->end_time);
                            $duration = $startTime->diffInHours($endTime);
                        @endphp
                        {{ $duration }} hour{{ $duration > 1 ? 's' : '' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${{ number_format($booking->total_price, 2) }}</div>
                        <div class="text-xs text-gray-500">Total Amount</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            @if($booking->status === 'confirmed') bg-green-100 text-green-800
                            @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($booking->status === 'completed') bg-blue-100 text-blue-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900 transition-colors" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if($booking->status === 'pending')
                            <button class="text-green-600 hover:text-green-900 transition-colors" title="Confirm">
                                <i class="fas fa-check"></i>
                            </button>
                            @endif
                            @if(in_array($booking->status, ['pending', 'confirmed']))
                            <button class="text-orange-600 hover:text-orange-900 transition-colors" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-900 transition-colors" title="Cancel">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                        <i class="fas fa-calendar-check text-4xl mb-2 text-gray-300"></i>
                        <p>No bookings found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $bookings->links() }}
    </div>
    @endif
</div>

<!-- Bookings Statistics -->
<div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Pending Bookings -->
    <div class="bg-white rounded-lg shadow-md p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-yellow-100 text-yellow-600 mr-3">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Pending Bookings</p>
                <p class="text-lg font-semibold">{{ $bookings->where('status', 'pending')->count() }}</p>
            </div>
        </div>
    </div>
    
    <!-- Confirmed Bookings -->
    <div class="bg-white rounded-lg shadow-md p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Confirmed Bookings</p>
                <p class="text-lg font-semibold">{{ $bookings->where('status', 'confirmed')->count() }}</p>
            </div>
        </div>
    </div>
    
    <!-- Today's Bookings -->
    <div class="bg-white rounded-lg shadow-md p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Today's Bookings</p>
                <p class="text-lg font-semibold">{{ $bookings->filter(function($booking) { return $booking->booking_date->isToday(); })->count() }}</p>
            </div>
        </div>
    </div>
    
    <!-- Total Revenue -->
    <div class="bg-white rounded-lg shadow-md p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-purple-100 text-purple-600 mr-3">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Total Revenue</p>
                <p class="text-lg font-semibold">${{ number_format($bookings->where('status', '!=', 'cancelled')->sum('total_price'), 2) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Notes Section -->
@if($bookings->whereNotNull('notes')->count() > 0)
<div class="mt-6 bg-white rounded-lg shadow-md p-6">
    <h4 class="text-lg font-semibold text-gray-800 mb-4">Recent Booking Notes</h4>
    <div class="space-y-3">
        @foreach($bookings->whereNotNull('notes')->take(3) as $booking)
        <div class="border-l-4 border-blue-500 pl-4">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-900">Booking #{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
                <span class="text-xs text-gray-500">{{ $booking->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-sm text-gray-600 mt-1">{{ $booking->notes }}</p>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection