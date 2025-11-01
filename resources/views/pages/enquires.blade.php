@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'User Management'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="alert alert-light" role="alert">
                 All <strong>Enquires</strong>. Check it
                <strong>
                    <a  target="_blank">
                
                    </a>
                </strong>
            </div>
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Enquires</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                  
                        <table class="table align-items-middle table-striped mb-0" id="registeredTable">
                           <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-start">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Email</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Phone</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Subject</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Message</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($enquires as $enquire)
                                <tr>
                                    <td class="text-start">{{ $enquire->name }}</td>
                                    <td class="text-center">{{ $enquire->email }}</td>
                                    <td class="text-center">{{ $enquire->phone }}</td>
                                    <td class="text-center">{{ $enquire->subject }}</td>
                                    <td class="text-center">{{ $enquire->message }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No enquiries found.</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
