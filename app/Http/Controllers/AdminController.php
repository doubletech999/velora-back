<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Site;
use App\Models\Guide;
use App\Models\Trip;
use App\Models\Review;
use App\Models\Booking;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'sites' => Site::count(),
            'guides' => Guide::count(),
            'trips' => Trip::count(),
            'reviews' => Review::count(),
            'bookings' => Booking::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // ========================================
    // USERS MANAGEMENT
    // ========================================
    
    public function users(Request $request)
    {
        $query = User::query();
        
        // Filter by role if provided
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }
        
        $users = $query->orderBy('id', 'desc')->paginate(15);
        return view('admin.users', compact('users'));
    }
    
    public function createUser(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,guide,admin',
            'language' => 'required|in:en,ar',
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.unique' => 'This email is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'role.required' => 'Role is required',
            'language.required' => 'Language is required',
        ]);
        
        try {
            // Create new user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'language' => $validated['language'],
                'email_verified_at' => now(),
            ]);
            
            return redirect()->route('admin.users')
                ->with('success', 'User "' . $user->name . '" created successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }
    
    public function showUser($id)
    {
        $user = User::with(['guide', 'trips', 'reviews', 'bookings'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }
    
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }
    
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:user,guide,admin',
            'language' => 'required|in:en,ar',
        ]);
        
        try {
            $user->update($validated);
            
            return redirect()->route('admin.users')
                ->with('success', 'User updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }
    
    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Prevent deleting admin users
            if ($user->role === 'admin') {
                return redirect()->back()
                    ->with('error', 'Cannot delete admin users!');
            }
            
            $userName = $user->name;
            $user->delete();
            
            return redirect()->route('admin.users')
                ->with('success', 'User "' . $userName . '" deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    // ========================================
    // SITES MANAGEMENT
    // ========================================
    
    public function sites()
    {
        $sites = Site::orderBy('id', 'desc')->paginate(15);
        return view('admin.sites', compact('sites'));
    }
    
    public function deleteSite($id)
    {
        try {
            $site = Site::findOrFail($id);
            $siteName = $site->name;
            $site->delete();
            
            return redirect()->route('admin.sites')
                ->with('success', 'Site "' . $siteName . '" deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete site: ' . $e->getMessage());
        }
    }

    // ========================================
    // GUIDES MANAGEMENT
    // ========================================
    
    public function guides()
    {
        $guides = Guide::with('user')->orderBy('id', 'desc')->paginate(15);
        return view('admin.guides', compact('guides'));
    }
    
    public function approveGuide($id)
    {
        try {
            $guide = Guide::findOrFail($id);
            $guide->update(['is_approved' => true]);
            
            return redirect()->route('admin.guides')
                ->with('success', 'Guide approved successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to approve guide: ' . $e->getMessage());
        }
    }
    
    public function deleteGuide($id)
    {
        try {
            $guide = Guide::findOrFail($id);
            
            // Update user role back to regular user
            $guide->user->update(['role' => 'user']);
            
            $guideName = $guide->user->name;
            $guide->delete();
            
            return redirect()->route('admin.guides')
                ->with('success', 'Guide "' . $guideName . '" deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete guide: ' . $e->getMessage());
        }
    }

    // ========================================
    // TRIPS MANAGEMENT
    // ========================================
    
    public function trips()
    {
        $trips = Trip::with('user')->orderBy('id', 'desc')->paginate(15);
        return view('admin.trips', compact('trips'));
    }
    
    public function deleteTrip($id)
    {
        try {
            $trip = Trip::findOrFail($id);
            $tripName = $trip->trip_name;
            $trip->delete();
            
            return redirect()->route('admin.trips')
                ->with('success', 'Trip "' . $tripName . '" deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete trip: ' . $e->getMessage());
        }
    }

    // ========================================
    // REVIEWS MANAGEMENT
    // ========================================
    
    public function reviews()
    {
        $reviews = Review::with(['user', 'site', 'guide'])->orderBy('id', 'desc')->paginate(15);
        return view('admin.reviews', compact('reviews'));
    }
    
    public function deleteReview($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->delete();
            
            return redirect()->route('admin.reviews')
                ->with('success', 'Review deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete review: ' . $e->getMessage());
        }
    }

    // ========================================
    // BOOKINGS MANAGEMENT
    // ========================================
    
    public function bookings()
    {
        $bookings = Booking::with(['user', 'guide'])->orderBy('id', 'desc')->paginate(15);
        return view('admin.bookings', compact('bookings'));
    }
    
    public function deleteBooking($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->delete();
            
            return redirect()->route('admin.bookings')
                ->with('success', 'Booking deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete booking: ' . $e->getMessage());
        }
    }
}