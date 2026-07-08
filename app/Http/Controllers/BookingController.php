<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\BookingCreated;

use Carbon\Carbon;

class BookingController extends Controller
{

public function store(Request $request, Listing $listing)
{
    abort_if($listing->status !== 'active', 404);

    $pricingMode = $request->input('pricing_mode', 'day');

    if ($pricingMode === 'day') {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $startDate  = $request->start_date;
        $endDate    = $request->end_date;
        $days       = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
        $totalPrice = $days * $listing->price_per_day;
    } else {
        $request->validate([
            'booking_date' => 'required|date|after_or_equal:today',
            'start_hour'   => 'required|string',
            'end_hour'     => 'required|string',
        ]);

        $startDate = $request->booking_date;
        $endDate   = $request->booking_date;

        $start = Carbon::parse($request->booking_date . ' ' . $request->start_hour);
        $end   = Carbon::parse($request->booking_date . ' ' . $request->end_hour);

        if ($end->lte($start)) {
            return back()->withErrors(['end_hour' => __('messages.booking-end-time-after-start')])->withInput();
        }

        $hours      = $start->diffInHours($end);
        $totalPrice = $hours * $listing->price_per_hour;
    }

    if (auth()->id() === $listing->user_id) {
        return back()->withErrors(['start_date' => __('messages.booking-cannot-book-own-listing')]);
    }

    try {
        $booking = DB::transaction(function () use ($listing, $pricingMode, $startDate, $endDate, $request, $totalPrice) {
            Listing::where('id', $listing->id)->lockForUpdate()->first();

            if ($pricingMode === 'day') {
                $conflict = $listing->activeBookings()
                    ->where('pricing_mode', 'day')
                    ->where(function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('start_date', [$startDate, $endDate])
                          ->orWhereBetween('end_date', [$startDate, $endDate])
                          ->orWhere(function ($q) use ($startDate, $endDate) {
                              $q->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                          });
                    })
                    ->exists();
            } else {
                $conflict = $listing->activeBookings()
                    ->where('pricing_mode', 'hour')
                    ->where('start_date', $startDate)
                    ->where(function ($q) use ($request) {
                        $q->where('start_hour', '<', $request->end_hour)
                          ->where('end_hour', '>', $request->start_hour);
                    })
                    ->exists();
            }

            if ($conflict) {
                throw new \RuntimeException('booking_conflict');
            }

            return Booking::create([
                'listing_id'   => $listing->id,
                'renter_id'    => auth()->id(),
                'owner_id'     => $listing->user_id,
                'start_date'   => $startDate,
                'end_date'     => $endDate,
                'start_hour'   => $pricingMode === 'hour' ? $request->start_hour : null,
                'end_hour'     => $pricingMode === 'hour' ? $request->end_hour : null,
                'pricing_mode' => $pricingMode,
                'total_price'  => $totalPrice,
                'deposit'      => $listing->deposit,
                'currency'     => $listing->currency,
                'status'       => 'pending',
            ]);
        });
    } catch (\RuntimeException $e) {
        if ($e->getMessage() === 'booking_conflict') {
            return back()->withErrors(['start_date' => __('messages.booking-dates-already-booked')])->withInput();
        }
        throw $e;
    }

    event(new BookingCreated($booking));
    return back()->with('success', __('messages.booking-request-sent'));
}



    public function confirm(Booking $booking)
    {
        abort_if(auth()->id() !== $booking->owner_id, 403);

        if (!$booking->isPending()) {
        return back()->withErrors(['booking' => __('messages.booking-cannot-confirmed')]);

        }

        $booking->update(['status' => 'confirmed']);

       return back()->with('success', __('messages.booking-confirmed'));
    }

    public function cancel(Booking $booking)
    {
        abort_if(
            auth()->id() !== $booking->renter_id && auth()->id() !== $booking->owner_id,
            403
        );

        if ($booking->isCancelled() || $booking->isCompleted()) {
           return back()->withErrors(['booking' => __('messages.booking-cannot-cancelled')]);
        }

        $booking->update([
            'status'       => 'cancelled',
            'cancelled_by' => auth()->id() === $booking->renter_id ? 'renter' : 'owner',
        ]);

        return back()->with('success', __('messages.booking-cancelled'));
    }

    public function apiIndex()
    {
    $user = auth()->user();
    return response()->json([
        'incoming' => $user->bookingsAsOwner()->with(['listing.images', 'renter'])->latest()->get()->map(fn($b) => [
            'id'          => $b->id,
            'status'      => $b->status,
            'total_price' => $b->total_price,
            'currency'    => $b->currency,
            'start_date'  => $b->start_date->format('d M'),
            'end_date'    => $b->end_date->format('d M Y'),
            'listing'     => [
                'title' => $b->listing->title,
                'image' => $b->listing->images->first()?->path
                ],
            'renter'      => [
                'name'   => $b->renter->name,
                'avatar' => $b->renter->avatar,
            ],
        ]),
        'rentals' => $user->bookingsAsRenter()->with(['listing.images', 'listing.city'])->latest()->get()->map(fn($b) => [
            'id'           => $b->id,
            'status'       => $b->status,
            'total_price'  => $b->total_price,
            'currency'     => $b->currency,
            'pricing_mode' => $b->pricing_mode,
            'start_date'   => $b->start_date->format('d M'),
            'end_date'     => $b->end_date->format('d M Y'),
            'start_hour'   => $b->start_hour,
            'end_hour'     => $b->end_hour,
            'listing'      => [
                'title' => $b->listing->title,
                'slug'  => $b->listing->slug,
                'city'  => $b->listing->city->name,
                'image' => $b->listing->images->first()?->path,
            ],
        ]),
    ]);
    }

    public function destroy(Booking $booking)
{
    abort_if(
        auth()->id() !== $booking->renter_id && auth()->id() !== $booking->owner_id,
        403
    );

    if (!in_array($booking->status, ['cancelled', 'completed'])) {
        abort(403);
    }

    $booking->delete();
    return response()->json(['ok' => true]);
}

    public function pendingCount()
    {
        return response()->json([
            'count' => auth()->user()->bookingsAsOwner()->where('status', 'pending')->count()
        ]);
    }
}
