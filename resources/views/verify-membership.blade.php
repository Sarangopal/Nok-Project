@extends('layouts.frontend')

@section('title', 'Verify Membership')

@section('content')
<section class="image-overlay-dark" data-bg-src="{{ asset('nokw/assets/img/hero/banok.jpg') }}">
    <div class="container" style="padding: 60px 0;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="border:1px solid #ddd;border-radius:8px;background:#fff;">
                    <div class="card-body" style="padding:32px;">
                        <h3 class="mb-2" style="color:#333;">Membership Verification</h3>
                        <p class="text-muted mb-4">Verify NOK membership status by entering Civil ID</p>

                        {{-- Validation Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul style="margin:0;padding-left:20px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Form --}}
                        <form method="POST" action="{{ route('verify-member.post') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="civil_id" style="font-weight:600;margin-bottom:6px;color:#333;">Civil ID or NOK ID *</label>
                                <input type="text" name="civil_id" id="civil_id" value="{{ old('civil_id', $prefillCivilId ?? '') }}" placeholder="Enter Civil ID or NOK ID (e.g., NOK001002)" class="form-control" style="padding:12px;border:1px solid #ddd;border-radius:6px;" required>
                                <small class="text-muted">You can enter either your Civil ID or your NOK membership ID</small>
                            </div>
                            {{-- <div class="mb-3">
                                <label for="email" style="font-weight:600;margin-bottom:6px;color:#333;">Email (Optional - for double verification)</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Enter email address" class="form-control" style="padding:12px;border:1px solid #ddd;border-radius:6px;">
                            </div> --}}
                            <button type="submit" class="vs-btn style5" style="width:100%;">Verify Membership</button>
                        </form>

                        {{-- Display result --}}
                        @if(isset($status))
                            <hr style="margin:24px 0;">
                            
                            @if($status === 'active')
                                <div class="alert alert-success" style="font-size:16px;display:flex;align-items:center;gap:10px;">
                                    <span style="font-size:24px;">{{ $statusIcon }}</span>
                                    <strong>{{ $message }}</strong>
                                </div>

                                {{-- Member Details Card --}}
                                <div style="border:1px solid #e0e0e0;border-radius:8px;padding:20px;background:#f9f9f9;margin-top:16px;">
                                    <h5 style="margin-bottom:16px;color:#333;"><strong>Member Details</strong></h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <strong style="color:#666;">Member Name:</strong><br>
                                            {{ $member->memberName }}
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <strong style="color:#666;">NOK ID:</strong><br>
                                            {{ $member->nok_id ?? 'N/A' }}
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <strong style="color:#666;">Civil ID:</strong><br>
                                            {{ $member->civil_id }}
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <strong style="color:#666;">Date of Joining:</strong><br>
                                            {{ optional($member->doj)->format('d M Y') ?? 'N/A' }}
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <strong style="color:#666;">Card Issued:</strong><br>
                                            {{ optional($member->card_issued_at)->format('d M Y') ?? 'N/A' }}
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <strong style="color:#666;">Valid Until:</strong><br>
                                            {{ optional($member->card_valid_until)->format('d M Y') ?? 'N/A' }}
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <strong style="color:#666;">Membership Card:</strong><br>
                                            <a class="btn btn-sm btn-primary" href="{{ route('membership.card.download', $member->id) }}">Download PDF</a>
                                        </div>
                                        <div class="col-md-6 mb-2" style="text-align:center;">
                                            @if(!empty($member->qr_code_path) && file_exists(public_path('storage/'.$member->qr_code_path)))
                                                <a href="{{ route('membership.card.download', $member->id) }}" title="Download Membership Card">
                                                    <img src="{{ asset('storage/'.$member->qr_code_path) }}" alt="QR Code" style="width:140px;height:140px;border:1px solid #ddd;border-radius:6px;" />
                                                </a>
                                                <div class="text-muted" style="font-size:12px;">Scan to verify or click to download</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <span class="badge bg-success" style="padding:8px 16px;font-size:14px;">🟢 Active Member</span>
                                    </div>
                                </div>

                            @elseif($status === 'expired')
                                <div class="alert alert-danger" style="font-size:16px;display:flex;align-items:center;gap:10px;">
                                    <span style="font-size:24px;">{{ $statusIcon }}</span>
                                    <strong>{{ $message }}</strong>
                                </div>
                                <div style="border:1px solid #f5c6cb;border-radius:8px;padding:20px;background:#f8d7da;margin-top:16px;">
                                    <p style="margin:0;color:#721c24;"><strong>Member Name:</strong> {{ $member->memberName }}</p>
                                    <p style="margin:0;color:#721c24;"><strong>Expired On:</strong> {{ optional($member->card_valid_until)->format('d M Y') ?? 'N/A' }}</p>
                                    <span class="badge bg-danger mt-2" style="padding:6px 12px;">🔴 Expired</span>
                                </div>

                            @elseif($status === 'pending')
                                <div class="alert alert-warning" style="font-size:16px;display:flex;align-items:center;gap:10px;">
                                    <span style="font-size:24px;">{{ $statusIcon }}</span>
                                    <strong>{{ $message }}</strong>
                                </div>
                                <p style="color:#856404;margin-top:12px;">This membership is awaiting admin approval.</p>

                            @elseif($status === 'inactive')
                                <div class="alert alert-secondary" style="font-size:16px;display:flex;align-items:center;gap:10px;">
                                    <span style="font-size:24px;">{{ $statusIcon }}</span>
                                    <strong>{{ $message }}</strong>
                                </div>

                            @else
                                <div class="alert alert-danger" style="font-size:16px;display:flex;align-items:center;gap:10px;">
                                    <span style="font-size:24px;">{{ $statusIcon }}</span>
                                    <strong>{{ $message }}</strong>
                                </div>
                                <p style="color:#721c24;margin-top:12px;">No membership record found with the provided Civil ID.</p>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
