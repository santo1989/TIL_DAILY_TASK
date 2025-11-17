<x-backend.layouts.master>

    <x-slot name="pageTitle">
        Admin Dashboard
    </x-slot>

    <x-slot name='breadCrumb'>
        <x-backend.layouts.elements.breadcrumb>
            <x-slot name="pageHeader">
                <div class="row">
                    <div class="col-12">Dashboard</div>

                </div>
            </x-slot>
        </x-backend.layouts.elements.breadcrumb>
    </x-slot>

    <div class="container">
        <div class="row p-1">
            <div class="col-12 pb-1">
                <div class="card">
                    <div class="text-left p-1 card-header">
                        Module Name
                    </div>

                    <div class="card-body">

                        
                            <div class="row justify-content-center">
                                @can('Admin')
                                <div class="col-3 pt-1 pb-1">
                                    <a class="btn btn-sm btn-outline-primary" style="width: 10rem;"
                                        href="{{ route('home') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                                        Home
                                    </a>
                                </div>
                                <div class="col-3 pt-1 pb-1">
                                    <a class="btn btn-sm btn-outline-primary" style="width: 10rem;"
                                        href="{{ route('users.show', ['user' => auth()->user()->id]) }}">
                                        <div class="sb-nav-link-icon"><i class="far fa-address-card"></i></div>
                                        Profile
                                    </a>
                                </div>
                                <div class="col-3 pt-1 pb-1">

                                    <a class="btn btn-sm btn-outline-primary" style="width: 10rem;"
                                        href="{{ route('divisions.index') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                        Division Management
                                    </a>
                                </div>
                                <div class="col-3 pt-1 pb-1">
                                    <a class="btn btn-sm btn-outline-primary" style="width: 10rem;"
                                        href="{{ route('companies.index') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                        Company Management
                                    </a>
                                </div>
                                <div class="col-3 pt-1 pb-1">
                                    <a class="btn btn-sm btn-outline-primary" style="width: 10rem;"
                                        href="{{ route('departments.index') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                        Department Management
                                    </a>
                                </div>
                                <div class="col-3 pt-1 pb-1">
                                    <a class="btn btn-sm btn-outline-primary" style="width: 10rem;"
                                        href="{{ route('designations.index') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                        Designation Management
                                    </a>
                                </div>

                                <div class="col-3 pt-1 pb-1">
                                    <a class="btn btn-sm btn-outline-primary" style="width: 10rem;"
                                        href="{{ route('roles.index') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-user-shield"></i></div>
                                        Role
                                    </a>
                                </div>
                                <div class="col-3 pt-1 pb-1">
                                    <a class="btn btn-sm btn-outline-primary" style="width: 10rem;"
                                        href="{{ route('users.index') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-user-friends"></i></div>
                                        Users
                                    </a>
                                </div>
                                <div class="col-3 pt-1 pb-1">
                                    <a class="btn btn-sm btn-outline-primary" style="width: 10rem;"
                                        href="{{ route('online_user') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                        Online User List
                                    </a>

                                </div>
                            @endcan
                            <!--Admin, TIL_Supervisor, Payroll_Supervisor, HR_Supervisor can access-->
                            @canany(['Admin', 'TIL_Supervisor', 'Payroll_Admin', 'Payroll_Supervisor', 'HR_Supervisor'])
                                <div class="col-3 pt-1 pb-1">
                                    <a class="btn btn-sm btn-outline-primary" style="width: 10rem;"
                                        href="{{ route('attendance.summary') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                        Attendance summary
                                    </a>

                                </div>
                            @endcanany
                            <!--Admin, TIL_Supervisor, HR_Supervisor,Welfare,Welfare_Supervisor can access-->
                            @canany(['Admin', 'TIL_Supervisor', 'HR_Supervisor', 'Welfare', 'Welfare_Supervisor'])
                                <div class="col-3 pt-1 pb-1">
                                    <a class="btn btn-sm btn-outline-primary" style="width: 10rem;"
                                        href="{{ route('comeback.reports') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                        Come Back Reports
                                    </a>

                                </div>
                                <div class="col-3 pt-1 pb-1">
                                    <a class="btn btn-sm btn-outline-primary" style="width: 10rem;"
                                        href="{{ route('operator-absent-analysis.index') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                        Operator Absent Analysis
                                    </a>
                                </div>
                            @endcanany
                            <!--Admin, TIL_Supervisor, HR_Supervisor,HR ,TIL_Administrator, Compliance, Compliance_Supervisor, Time_Section  can access-->
                            @canany(['Admin', 'TIL_Supervisor', 'HR_Supervisor', 'HR', 'TIL_Administrator',
                                'Compliance', 'Compliance_Supervisor', 'Time_Section'])
                                <div class="col-3 pt-1 pb-1">
                                    <a class="btn btn-sm btn-outline-primary" style="width: 10rem;"
                                        href="{{ route('attendance-reports.index') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                        Attendance Reports ( Lunch Out, Late Comer, To be Absent, On Leave )
                                    </a>
                                </div>
                            @endcanany
                            <!--Admin, TIL_Supervisor, HR_Supervisor,HR  can access-->
                            @canany(['Admin', 'TIL_Supervisor', 'HR_Supervisor', 'HR'])
                                <div class="col-3 pt-1 pb-1">
                                    <a class="btn btn-sm btn-outline-primary" style="width: 10rem;"
                                        href="{{ route('recruitment-summaries.index') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                        Recruitment Summary
                                    </a>
                                </div>
                            @endcanany
                            <!--Admin, TIL_Supervisor, HR_Supervisor,IE  can access-->
                            @canany(['Admin', 'TIL_Supervisor', 'HR_Supervisor', 'IE'])
                                <div class="col-3 pt-1 pb-1">
                                    <a class="btn btn-sm btn-outline-primary" style="width: 10rem;"
                                        href="{{ route('operation-details.index') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                        Operation Details, DHU Report
                                    </a>
                                </div>
                            @endcanany
                            <!--Admin, TIL_Supervisor, HR_Supervisor,Store  can access-->
                            @canany(['Admin', 'TIL_Supervisor', 'HR_Supervisor', 'Store'])
                                <div class="col-3 pt-1 pb-1">
                                    <a class="btn btn-sm btn-outline-primary" style="width: 10rem;"
                                        href="{{ route('shipments.index') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i>Shipments</div>

                                    </a>
                                </div>
                            @endcanany
                            <!--Admin, TIL_Supervisor, HR_Supervisor,HR  can access-->
                            @canany(['Admin', 'TIL_Supervisor', 'HR_Supervisor', 'HR'])
                                <div class="col-3 pt-1 pb-1">
                                    <a class="btn btn-sm btn-outline-primary" style="width: 10rem;"
                                        href="{{ route('floor-timings.index') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                        Floor Timings
                                    </a>
                                </div>
                            @endcanany
                            <!--Admin, TIL_Supervisor, HR_Supervisor  can access-->
                            @canany(['Admin', 'TIL_Supervisor', 'HR_Supervisor'])
                                <div class="col-3 pt-1 pb-1">
                                    <a class="btn btn-sm btn-outline-primary" style="width: 10rem;"
                                        href="{{ route('daily-reports.index') }}">
                                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                        Incident, Improvement Area, Other Information
                                    </a>
                                </div>
                            @endcanany
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="text-left p-1 card-header">
                        Reports
                    </div>

                    <div class="card-body">
                        <div class="row justify-content-center">
                            @can('Admin')
                                <div class="col-3 pt-1 pb-1">
                                    <a class="btn btn-sm btn-outline-primary" style="width: 10rem;"
                                        href="{{ route('Report') }} " target="_blank">
                                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                        Report
                                    </a>

                                </div>
                            @endcan

                            <div class="col-3 pt-1 pb-1">
                                <a class="btn btn-sm btn-outline-primary" style="width: 10rem;"
                                    href="{{ route('Report') }} " target="_blank">
                                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                    Today Report
                                </a>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-backend.layouts.master>
