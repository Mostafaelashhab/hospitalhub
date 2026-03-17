<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $clinic = auth()->user()->clinic;

        $query = Review::where('clinic_id', $clinic->id)
            ->with(['patient', 'doctor', 'appointment']);

        if ($doctorId = $request->get('doctor_id')) {
            $query->where('doctor_id', $doctorId);
        }

        if ($rating = $request->get('rating')) {
            $query->where('rating', $rating);
        }

        $reviews = $query->latest()->paginate(15);

        // Stats
        $statsQuery = Review::where('clinic_id', $clinic->id);
        $stats = [
            'avg_rating'   => round((clone $statsQuery)->avg('rating') ?? 0, 1),
            'total'        => (clone $statsQuery)->count(),
            'five_star'    => (clone $statsQuery)->where('rating', 5)->count(),
            'one_star'     => (clone $statsQuery)->where('rating', 1)->count(),
        ];

        $doctors = Doctor::where('clinic_id', $clinic->id)->get();

        return view('admin.reviews.index', compact('reviews', 'stats', 'doctors'));
    }

    public function toggleVisibility(Review $review)
    {
        abort_if($review->clinic_id !== auth()->user()->clinic->id, 403);

        $review->update(['is_visible' => !$review->is_visible]);

        return back()->with('success', __('app.toggle_visibility'));
    }

    public function destroy(Review $review)
    {
        abort_if($review->clinic_id !== auth()->user()->clinic->id, 403);

        $review->delete();

        return back()->with('success', __('app.review_deleted'));
    }
}
