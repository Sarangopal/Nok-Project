@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Membership Renewals'])
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="alert alert-light" role="alert">
                 All <strong>Membership Renewals</strong>. Check it
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Contact No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Membership Id</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Renewals Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
              
        <!-- Dummy Data for Testing -->
        <tr>
            <td class="text-start">John Doe</td>
            <td class="text-center">+91 9876543210</td>
            <td class="text-center">MEMB-001</td>
            <td class="text-center">2025-10-10</td>
            <td class="text-center text-success">Active</td>
        </tr>
        <tr>
            <td class="text-start">Jane Smith</td>
            <td class="text-center">+91 9123456780</td>
            <td class="text-center">MEMB-002</td>
            <td class="text-center">2025-11-05</td>
            <td class="text-center text-warning">Pending</td>
        </tr>
        <tr>
            <td class="text-start">Robert Brown</td>
            <td class="text-center">+91 9988776655</td>
            <td class="text-center">MEMB-003</td>
            <td class="text-center">2025-09-30</td>
            <td class="text-center text-danger">Expired</td>
        </tr>



                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
