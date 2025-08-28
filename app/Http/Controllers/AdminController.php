<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Site;
use App\Models\Guide;
use App\Models\Trip;
use App\Models\Review;
use App\Models\Booking;

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

    public function users()
    {
        $users = User::paginate(15);
        return view('admin.users', compact('users'));
    }

    public function sites()
    {
        $sites = Site::paginate(15);
        return view('admin.sites', compact('sites'));
    }

    public function guides()
    {
        $guides = Guide::with('user')->paginate(15);
        return view('admin.guides', compact('guides'));
    }

    public function trips()
    {
        $trips = Trip::with('user')->paginate(15);
        return view('admin.trips', compact('trips'));
    }

    public function reviews()
    {
        $reviews = Review::with(['user', 'site', 'guide'])->paginate(15);
        return view('admin.reviews', compact('reviews'));
    }

    public function bookings()
    {
        $bookings = Booking::with(['user', 'guide'])->paginate(15);
        return view('admin.bookings', compact('bookings'));
    }
}
