@extends('layouts.admin')

@section('title', 'Trips Management - Velora Admin')
@section('page-title', 'Trip Planning Management')

@section('content')
<div class="bg-white rounded-lg shadow-md">
    <!-- Header -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">All Planned Trips</h3>
            <div class="flex space-x-2">
                <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">All Trips</option>
                    <option value="upcoming">Upcoming</option>
                    <option value="ongoing">Ongoing</option>
                    <option value="completed">Completed</option>
                </select>
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add Trip
                </button>
            </div>
        </div>
    </div>

    <!-- Trips Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trip Info</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Traveler</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sites</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($trips as $trip)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $trip->id }}</td>
                    <td class="px-6 py-4">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $trip->trip_name }}</div>
                            @if($trip->description)
                            <div class="text-sm text-gray-500">{{ Str::limit($trip->description, 60) }}</div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-orange-600 rounded-full flex items-center justify-center mr-2">
                                <span class="text-white text-xs font-medium">{{ substr($trip->user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $trip->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $trip->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div>
                            <div class="font-medium">{{ $trip->start_date->format('M d, Y') }}</div>
                            <div class="text-gray-500">to {{ $trip->end_date->format('M d, Y') }}</div>
                            <div class="text-xs text-blue-600">
                                {{ $trip->start_date->diffInDays($trip->end_date) + 1 }} day(s)
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ count($trip->sites) }} sites
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $now = now();
                            $status = 'upcoming';
                            $statusColor = 'bg-blue-100 text-blue-800';
                            
                            if ($now->isAfter($trip->end_date)) {
                                $status = 'completed';
                                $statusColor = 'bg-gray-100 text-gray-800';
                            } elseif ($now->isBetween($trip->start_date, $trip->end_date)) {
                                $status = 'ongoing';
                                $statusColor = 'bg-green-100 text-green-800';
                            }
                        @endphp
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColor }}">
                            {{ ucfirst($status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <button class="text-blue-600 hover:text-blue-900 transition-colors" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="text-green-600 hover:text-green-900 transition-colors" title="View Sites">
                                <i class="fas fa-map-marked-alt"></i>
                            </button>
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
                        <i class="fas fa-route text-4xl mb-2 text-gray-300"></i>
                        <p>No trips found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($trips->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $trips->links() }}
    </div>
    @endif
</div>

<!-- Quick Stats -->
<div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white rounded-lg shadow-md p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-blue-100 text-blue-600 mr-3">
                <i class="fas fa-calendar-plus"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Upcoming Trips</p>
                <p class="text-lg font-semibold">{{ $trips->where('start_date', '>', now())->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-green-100 text-green-600 mr-3">
                <i class="fas fa-play"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Ongoing Trips</p>
                <p class="text-lg font-semibold">{{ $trips->filter(function($trip) { return now()->isBetween($trip->start_date, $trip->end_date); })->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-4">
        <div class="flex items-center">
            <div class="p-2 rounded-full bg-gray-100 text-gray-600 mr-3">
                <i class="fas fa-check"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600">Completed Trips</p>
                <p class="text-lg font-semibold">{{ $trips->where('end_date', '<', now())->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection