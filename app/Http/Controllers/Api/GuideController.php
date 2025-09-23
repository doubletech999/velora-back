<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guide;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class GuideController extends Controller
{
    /**
     * Display a listing of approved guides.
     */
    public function index(Request $request)
    {
        $query = Guide::with('user')->where('is_approved', true);

        // Filter by languages
        if ($request->has('languages')) {
            $languages = $request->languages;
            $query->where('languages', 'like', '%' . $languages . '%');
        }

        // Filter by hourly rate range
        if ($request->has('min_rate')) {
            $query->where('hourly_rate', '>=', $request->min_rate);
        }

        if ($request->has('max_rate')) {
            $query->where('hourly_rate', '<=', $request->max_rate);
        }

        // Search by name
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $guides = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $guides
        ]);
    }

    /**
     * Store a newly created guide profile.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bio' => 'required|string|max:1000',
            'languages' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'hourly_rate' => 'required|numeric|min:0|max:999.99'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        // Check if user already has a guide profile
        if ($user->guide) {
            return response()->json([
                'success' => false,
                'message' => 'User already has a guide profile'
            ], 409);
        }

        // Update user role to guide
        $user->update(['role' => 'guide']);

        $guide = Guide::create([
            'user_id' => $user->id,
            'bio' => $request->bio,
            'languages' => $request->languages,
            'phone' => $request->phone,
            'hourly_rate' => $request->hourly_rate,
            'is_approved' => false // Requires admin approval
        ]);

        $guide->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Guide profile created successfully. Awaiting admin approval.',
            'data' => $guide
        ], 201);
    }

    /**
     * Display the specified guide.
     */
    public function show(string $id)
    {
        $guide = Guide::with(['user', 'reviews.user'])->find($id);

        if (!$guide) {
            return response()->json([
                'success' => false,
                'message' => 'Guide not found'
            ], 404);
        }

        // Calculate average rating
        $averageRating = $guide->reviews->avg('rating');
        $totalReviews = $guide->reviews->count();

        $guideData = $guide->toArray();
        $guideData['average_rating'] = $averageRating ? round($averageRating, 1) : 0;
        $guideData['total_reviews'] = $totalReviews;

        return response()->json([
            'success' => true,
            'data' => $guideData
        ]);
    }

    /**
     * Update the specified guide profile.
     */
    public function update(Request $request, string $id)
    {
        $guide = Guide::find($id);

        if (!$guide) {
            return response()->json([
                'success' => false,
                'message' => 'Guide not found'
            ], 404);
        }

        // Check if the authenticated user owns this guide profile
        if (Auth::id() !== $guide->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this guide profile'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'bio' => 'string|max:1000',
            'languages' => 'string|max:255',
            'phone' => 'string|max:20',
            'hourly_rate' => 'numeric|min:0|max:999.99'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // If profile was updated, set approval status to pending again
        $needsReapproval = false;
        if ($request->has(['bio', 'languages', 'phone', 'hourly_rate'])) {
            $needsReapproval = true;
        }

        $updateData = $request->only(['bio', 'languages', 'phone', 'hourly_rate']);
        
        if ($needsReapproval && $guide->is_approved) {
            $updateData['is_approved'] = false;
        }

        $guide->update($updateData);
        $guide->load('user');

        $message = $needsReapproval && $guide->is_approved === false 
            ? 'Guide profile updated. Awaiting admin re-approval.'
            : 'Guide profile updated successfully.';

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $guide
        ]);
    }

    /**
     * Remove the specified guide profile.
     */
    public function destroy(string $id)
    {
        $guide = Guide::find($id);

        if (!$guide) {
            return response()->json([
                'success' => false,
                'message' => 'Guide not found'
            ], 404);
        }

        // Check if the authenticated user owns this guide profile
        if (Auth::id() !== $guide->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this guide profile'
            ], 403);
        }

        // Update user role back to regular user
        $guide->user->update(['role' => 'user']);

        $guide->delete();

        return response()->json([
            'success' => true,
            'message' => 'Guide profile deleted successfully'
        ]);
    }

    /**
     * Get current user's guide profile.
     */
    public function myProfile()
    {
        $user = Auth::user();
        
        if (!$user->guide) {
            return response()->json([
                'success' => false,
                'message' => 'No guide profile found for current user'
            ], 404);
        }

        $guide = $user->guide->load(['user', 'reviews.user']);

        // Calculate average rating
        $averageRating = $guide->reviews->avg('rating');
        $totalReviews = $guide->reviews->count();

        $guideData = $guide->toArray();
        $guideData['average_rating'] = $averageRating ? round($averageRating, 1) : 0;
        $guideData['total_reviews'] = $totalReviews;

        return response()->json([
            'success' => true,
            'data' => $guideData
        ]);
    }

    /**
     * Get guide's available time slots.
     */
    public function availability(Request $request, string $id)
    {
        $guide = Guide::find($id);

        if (!$guide) {
            return response()->json([
                'success' => false,
                'message' => 'Guide not found'
            ], 404);
        }

        $date = $request->get('date', now()->format('Y-m-d'));

        // Get existing bookings for the date
        $existingBookings = $guide->bookings()
            ->where('booking_date', $date)
            ->where('status', '!=', 'cancelled')
            ->get(['start_time', 'end_time']);

        // Define available time slots (9 AM to 6 PM)
        $availableSlots = [];
        for ($hour = 9; $hour <= 17; $hour++) {
            $timeSlot = sprintf('%02d:00:00', $hour);
            
            // Check if this slot conflicts with existing bookings
            $isAvailable = true;
            foreach ($existingBookings as $booking) {
                if ($timeSlot >= $booking->start_time && $timeSlot < $booking->end_time) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                $availableSlots[] = [
                    'time' => $timeSlot,
                    'display_time' => date('g:i A', strtotime($timeSlot))
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'guide_id' => $guide->id,
                'date' => $date,
                'available_slots' => $availableSlots,
                'hourly_rate' => $guide->hourly_rate
            ]
        ]);
    }
}