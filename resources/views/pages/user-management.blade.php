@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'User Management'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="alert alert-light" role="alert">
                 All <strong>Registered Members</strong>. Check it
                <strong>
                    <a  target="_blank">
                
                    </a>
                </strong>
            </div>
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Users</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="registeredTable">
                           
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Existing or not</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Membership No</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Civil ID</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Contact</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if($members->count() > 0)
                                @foreach($members as $member)
                                    <tr>
                                        {{-- Name --}}
                                        <td>
                                            <div class="d-flex px-3 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $member->memberName }}</h6>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Existing or not --}}
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0">{{ $member->member_type }}</p>
                                        </td>

                                        {{-- Membership No --}}
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-sm font-weight-bold mb-0">{{ $member->nok_id }}</p>
                                        </td>

                                        {{-- Civil ID --}}
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-sm font-weight-bold mb-0">{{ $member->civil_id }}</p>
                                        </td>

                                        {{-- Contact --}}
                                        <td class="align-middle text-center text-sm">
                                            <p class="text-sm font-weight-bold mb-0">{{ $member->mobile }}</p>
                                        </td>

                                        {{-- Action --}}
                                        <td class="align-middle text-center">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a href="{{ route('members.edit', $member->id) }}" class="text-sm font-weight-bold mb-0">Edit</a>
                                                <form action="{{ route('members.destroy', $member->id) }}" method="POST" class="ms-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm font-weight-bold mb-0 btn btn-link p-0">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">No members found.</td>
                                </tr>
                            @endif
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
