@extends('layouts.frontend')

@section('title', 'Member Dashboard')

@section('content')
@php($member = auth('members')->user())
@php($offers = $member->offers()
    ->where('active', true)
    ->where(function($q){ $q->whereNull('starts_at')->orWhereDate('starts_at','<=',now()); })
    ->where(function($q){ $q->whereNull('ends_at')->orWhereDate('ends_at','>=',now()); })
    ->latest()->take(10)->get())
<section class="image-overlay-dark" data-bg-src="{{ asset('nokw/assets//img/hero/banok.jpg') }}">
    <div class="container" style="padding: 40px 0;">
        <h2 class="text-white mb-3">Welcome, {{ $member->memberName }}</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Profile Overview</h5>
                        <p><strong>NOK ID:</strong> {{ $member->nok_id ?? '-' }}</p>
                        <p><strong>Email:</strong> {{ $member->email }}</p>
                        <p><strong>Mobile:</strong> {{ $member->mobile }}</p>
                        <p><strong>Address:</strong> {{ $member->address }}</p>
                        <p><strong>Joining Date:</strong> {{ optional($member->doj)->toDateString() }}</p>
                        <p><strong>Renewal Date:</strong> {{ optional($member->card_valid_until)->toDateString() }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($member->renewal_status) }}</p>
                        @if($member->photo_path)
                            <img src="{{ asset('storage/'.$member->photo_path) }}" alt="Photo" style="max-width:100%;border-radius:6px;border:1px solid #eee;" />
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Membership Card</h5>
                        
                        @if(optional($member->card_valid_until)->isPast())
                            <div class="alert alert-danger mb-3">
                                ⚠️ Your membership has expired on {{ $member->card_valid_until->format('d M Y') }}
                            </div>
                        @else
                            @php($daysLeft = optional($member->card_valid_until)? now()->diffInDays($member->card_valid_until, false) : null)
                            @if(!is_null($daysLeft) && $daysLeft <= 30 && $daysLeft > 0)
                                <div class="alert alert-warning mb-3">
                                    ⏰ Your membership expires in {{ $daysLeft }} day(s)
                                </div>
                            @endif
                        @endif

                        @if($member->renewal_status === 'pending' && $member->renewal_requested_at)
                            <div class="alert alert-info mb-3">
                                ✅ Renewal request submitted on {{ $member->renewal_requested_at->format('d M Y') }}. 
                                Waiting for admin approval.
                            </div>
                        @endif

                        <a class="vs-btn style5" href="{{ route('membership.card.download', $member->id) }}">Download PDF</a>
                        
                        @if(optional($member->card_valid_until)->isPast() || (!is_null($daysLeft) && $daysLeft <= 30))
                            @if($member->renewal_status !== 'pending' || !$member->renewal_requested_at)
                                <form method="POST" action="{{ route('member.renewal.request') }}" class="mt-3">
                                    @csrf
                                    <button type="submit" class="vs-btn style4">
                                        {{ optional($member->card_valid_until)->isPast() ? 'Request Renewal Now' : 'Request Early Renewal' }}
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Exclusive Offers for Members</h5>
                        @if($member->renewal_status !== 'approved')
                            <div class="alert alert-info">
                                Offers will be available once your membership is approved.
                            </div>
                        @else
                            @forelse($offers as $offer)
                                <div style="border:1px solid #eee;border-radius:6px;padding:12px;margin-bottom:10px;background:#f8f9fa;">
                                    <div style="font-weight:600;font-size:18px;color:#333;">{{ $offer->title }}</div>
                                    @if($offer->promo_code)
                                        <div style="margin-top:8px;">
                                            <span style="font-weight:500;">Promo Code:</span> 
                                            <code style="background:#fff;padding:4px 8px;border-radius:4px;font-size:16px;color:#d63384;">{{ $offer->promo_code }}</code>
                                        </div>
                                    @endif
                                    @if($offer->body)
                                        <div style="margin-top:10px;color:#666;">{{ $offer->body }}</div>
                                    @endif
                                    @if($offer->starts_at || $offer->ends_at)
                                        <div style="font-size:12px;color:#999;margin-top:10px;padding-top:8px;border-top:1px solid #ddd;">
                                            @if($offer->starts_at) 📅 Starts: {{ $offer->starts_at->format('d M Y') }} @endif
                                            @if($offer->ends_at) | ⏰ Ends: {{ $offer->ends_at->format('d M Y') }} @endif
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="alert alert-info">
                                    <strong>No offers assigned yet</strong><br>
                                    Check back later for exclusive discounts and promotions!
                                </div>
                            @endforelse
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('member.logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="vs-btn style4">Logout</button>
        </form>
    </div>
    <style>.card{border-radius:8px;border:1px solid #ddd;}.card-title{margin-bottom:10px;}</style>
</section>
@endsection


