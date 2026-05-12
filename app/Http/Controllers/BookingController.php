<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Listing;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request, Listing $listing)
    {

        if (auth()->id() === $listing->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate   = Carbon::parse($validated['end_date']);


        $alreadyBooked = Booking::query()
            ->where('listing_id', $listing->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($startDate, $endDate) {

                $query
                    ->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($query) use ($startDate, $endDate) {
                        $query
                            ->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();

        if ($alreadyBooked) {
            return back()
                ->withErrors([
                    'booking' => 'Selected dates are already booked.',
                ])
                ->withInput();
        }
        $days = $startDate->diffInDays($endDate) + 1;

        $totalPrice = $days * $listing->price_per_day;

        Booking::create([
            'listing_id'   => $listing->id,
            'renter_id'    => auth()->id(),
            'owner_id'     => $listing->user_id,

            'start_date'   => $startDate,
            'end_date'     => $endDate,

            'total_price'  => $totalPrice,
            'deposit'      => $listing->deposit,
            'currency'     => $listing->currency,

            'status'       => 'pending',
        ]);

        return back()->with('success', 'Booking request sent.');
    }
}
