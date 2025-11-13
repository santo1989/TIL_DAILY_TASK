<?php

use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\AttendanceSummaryController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\ComeBackReportController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\FloorTimingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OperationDetailController;
use App\Http\Controllers\OperatorAbsentAnalysisController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\RecruitmentSummaryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlanningDataController;
use App\Http\Controllers\ReportController;
use App\Models\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');

// });

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/s', function () {
    return view('search');
});

Route::get('/fetch-data', [PlanningDataController::class, 'fetchData']);


Route::get('/search',  [PlanningDataController::class, 'search'])->name('search');
Route::get('/user-of-supervisor', function () {
    return view('backend.users.superindex');
})->name('superindex');

//New registration ajax route

Route::get('/get-company-designation/{divisionId}', [CompanyController::class, 'getCompanyDesignations'])->name('get_company_designation');


Route::get('/get-department/{company_id}', [CompanyController::class, 'getdepartments'])->name('get_departments');

//dashoard for Report
Route::get('/trg', [PlanningDataController::class, 'trg'])->name('trg');

Route::middleware('auth')->group(function () {
    // Route::get('/check', function () {
    //     return "Hello world";
    // });

    Route::get('/home', function () {
        return view('backend.home');
    })->name('home');


    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');


    //user

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get(
        '/users/{user}/edit',
        [UserController::class, 'edit']
    )->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/online-user', [UserController::class, 'onlineuserlist'])->name('online_user');

    Route::post('/users/{user}/users_active', [UserController::class, 'user_active'])->name('users.active');

    Route::post('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.role');

    //divisions

    Route::get('/divisions', [DivisionController::class, 'index'])->name('divisions.index');
    Route::get('/divisions/create', [DivisionController::class, 'create'])->name('divisions.create');
    Route::post('/divisions', [DivisionController::class, 'store'])->name('divisions.store');
    Route::get('/divisions/{division}', [DivisionController::class, 'show'])->name('divisions.show');
    Route::get('/divisions/{division}/edit', [DivisionController::class, 'edit'])->name('divisions.edit');
    Route::put('/divisions/{division}', [DivisionController::class, 'update'])->name('divisions.update');
    Route::delete('/divisions/{division}', [DivisionController::class, 'destroy'])->name('divisions.destroy');

    // companies
    Route::resource('companies', CompanyController::class);

    //departments
    Route::resource('departments', DepartmentController::class);

    // designations
    Route::resource('designations', DesignationController::class);

    ///buyers
    Route::get('/buyers', [BuyerController::class, 'index'])->name('buyers.index');
    Route::get('/buyers/create', [BuyerController::class, 'create'])->name('buyers.create');
    Route::post('/buyers', [BuyerController::class, 'store'])->name('buyers.store');
    Route::get('/buyers/{buyer}', [BuyerController::class, 'show'])->name('buyers.show');
    Route::get('/buyers/{buyer}/edit', [BuyerController::class, 'edit'])->name('buyers.edit');
    Route::put('/buyers/{buyer}', [BuyerController::class, 'update'])->name('buyers.update');
    Route::delete('/buyers/{buyer}', [BuyerController::class, 'destroy'])->name('buyers.destroy');
    Route::post('/buyers/{buyer}/buyers_active', [BuyerController::class, 'buyer_active'])->name('buyers.active');
    Route::get('/get_buyer', [BuyerController::class, 'get_buyer'])->name('get_buyer');

    ///suppliers
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show');
    Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    Route::post('/suppliers/{supplier}/suppliers_active', [SupplierController::class, 'supplier_active'])->name('suppliers.active');
    Route::get('/get_supplier', [SupplierController::class, 'get_supplier'])->name('get_supplier');

    //planning_data

    //planning_data
    Route::get('/planning_data/fetch', [PlanningDataController::class, 'fetchData'])->name('planning_data.fetch');

    Route::get('/planning_data', [
        PlanningDataController::class,
        'index'
    ])->name('planning_data.index');
    Route::get('/planning_data', [
        PlanningDataController::class,
        'index'
    ])->name('planning_data.index');
    Route::get('/old_index', [PlanningDataController::class, 'old_index'])->name('old_index');

    Route::get('/planning_data/create', [PlanningDataController::class, 'create'])->name('planning_data.create');
    Route::post('/planning_data_store', [PlanningDataController::class, 'store'])->name('planning_data_store');
    Route::get('/planning_data/{planning_data}', [PlanningDataController::class, 'show'])->name('planning_data.show');
    Route::get('/planning_data/{planning_data}/edit', [PlanningDataController::class, 'edit'])->name('planning_data_edit');
    Route::put('/planning_data/{planning_data}', [PlanningDataController::class, 'update'])->name('planning_data_update');
    Route::delete('/planning_data/{planning_data}', [PlanningDataController::class, 'destroy'])->name('planning_data.destroy');
    //line_destroy
    Route::delete(
        '/line_destroy',
        [PlanningDataController::class, 'line_destroy']
    )->name('planning_data.line_destroy');
    //planning_data_timeEntry
    Route::post('/planning_data_timeEntry/{planning_data_timeEntry_id}', [
        PlanningDataController::class,
        'timeEntry'
    ])->name('planning_data_timeEntry');
    //alterdata_store
    Route::post('/alterdata_store', [
        PlanningDataController::class,
        'alterdata_store'
    ])->name('alterdata_store');
    //alterdata_store_update
    Route::post('/alterdata_store_update', [
        PlanningDataController::class,
        'alterdata_store_update'
    ])->name('alterdata_store_update');
    //planning_data_timeEntry_copy
    Route::post('/planning_data_timeEntry_copy/{planning_data_timeEntry_id}', [
        PlanningDataController::class,
        'timeEntry_copy'
    ])->name('planning_data_timeEntry_copy');

    //report
    // Route::post('/generateReport', [PlanningDataController::class, 'generateReport'])->name('generateReport');



    Route::prefix('attendance')->group(function () {
        Route::get('/summary', [AttendanceSummaryController::class, 'index'])
            ->name('attendance.summary');

        Route::post('/summary/upload', [AttendanceSummaryController::class, 'upload'])
            ->name('attendance.summary.upload');
        Route::get('/summary/{attendanceSummary}/edit', [AttendanceSummaryController::class, 'edit'])
            ->name('attendance.summary.edit');
        Route::put('/summary/{attendanceSummary}', [AttendanceSummaryController::class, 'update'])
            ->name('attendance.summary.update');
        Route::get('/download-attendance-template', [AttendanceSummaryController::class, 'downloadTemplate'])
            ->name('attendance.summary.download.template');
        Route::get('/summary/create', [AttendanceSummaryController::class, 'create'])
            ->name('attendance.summary.create');
        Route::post('/summary/store', [AttendanceSummaryController::class, 'store'])
            ->name('attendance.summary.store');
        Route::get('/summary/{attendanceSummary}', [AttendanceSummaryController::class, 'show'])
            ->name('attendance.summary.show');
        Route::delete('/summary/{attendanceSummary}', [AttendanceSummaryController::class, 'destroy'])
            ->name('attendance.summary.destroy');
    });

    Route::prefix('comeback-reports')->group(function () {
        Route::get('/', [ComeBackReportController::class, 'index'])->name('comeback.reports');
        Route::post('/upload', [ComeBackReportController::class, 'comebackreportsupload'])->name('comeback.reports.upload');
        Route::get('/download-template', [ComeBackReportController::class, 'downloadTemplate'])->name('comeback.reports.download.template');
        Route::get('/create', [ComeBackReportController::class, 'create'])->name('comeback.reports.create');
        Route::get('/{id}/edit', [ComeBackReportController::class, 'edit'])->name('comeback.reports.edit');
        Route::put('/{id}', [ComeBackReportController::class, 'update'])->name('comeback.reports.update');
        Route::delete('/{id}', [ComeBackReportController::class, 'destroy'])->name('comeback.reports.destroy');
    });

    Route::prefix('operator-absent-analysis')->group(function () {
        Route::get('/', [OperatorAbsentAnalysisController::class, 'index'])->name('operator-absent-analysis.index');
        // operator - absent - analysis . download . template
        Route::get('/download-template', [OperatorAbsentAnalysisController::class, 'downloadTemplate'])->name('operator-absent-analysis.download.template');
        Route::post('/upload', [OperatorAbsentAnalysisController::class, 'upload'])->name('operator-absent-analysis.upload');
        Route::get('/{id}/edit', [OperatorAbsentAnalysisController::class, 'edit'])->name('operator-absent-analysis.edit');
        Route::put('/{id}', [OperatorAbsentAnalysisController::class, 'update'])->name('operator-absent-analysis.update');
        Route::delete('/{id}', [OperatorAbsentAnalysisController::class, 'destroy'])->name('operator-absent-analysis.destroy');
    });

    Route::prefix('attendance-reports')->group(function () {
        Route::get('/', [AttendanceReportController::class, 'index'])->name('attendance-reports.index');
        //attendance-reports.download.template
        Route::get('/download-template', [AttendanceReportController::class, 'Attendance_Reports_Lunch_Late_Absen_LeavedownloadTemplate'])->name('attendance-reports.download.template');
        Route::post('/upload', [AttendanceReportController::class, 'upload'])->name('attendance-reports.upload');
        Route::get('/{report}/edit', [AttendanceReportController::class, 'edit'])->name('attendance-reports.edit');
        Route::put('/{report}', [AttendanceReportController::class, 'update'])->name('attendance-reports.update');
        Route::delete('/{report}', [AttendanceReportController::class, 'destroy'])->name('attendance-reports.destroy');
    });

    Route::prefix('recruitment-summaries')->group(function () {
        Route::get('/', [RecruitmentSummaryController::class, 'index'])->name('recruitment-summaries.index');
        Route::get('/download-template', [RecruitmentSummaryController::class, 'downloadTemplate'])->name('recruitment-summaries.download.template');
        Route::post('/upload', [RecruitmentSummaryController::class, 'upload'])->name('recruitment-summaries.upload');
        Route::get('/{summary}/edit', [RecruitmentSummaryController::class, 'edit'])->name('recruitment-summaries.edit');
        Route::put('/{summary}', [RecruitmentSummaryController::class, 'update'])->name('recruitment-summaries.update');
        Route::delete('/{summary}', [RecruitmentSummaryController::class, 'destroy'])->name('recruitment-summaries.destroy');
    });

    Route::prefix('operation-details')->group(function () {
        Route::get('/', [OperationDetailController::class, 'index'])->name('operation-details.index');
        Route::get('/download-template', [OperationDetailController::class, 'downloadTemplate'])->name('operation-details.download.template');
        Route::post('/upload', [OperationDetailController::class, 'upload'])->name('operation-details.upload');
        Route::get('/{detail}/edit', [OperationDetailController::class, 'edit'])->name('operation-details.edit');
        Route::put('/{detail}', [OperationDetailController::class, 'update'])->name('operation-details.update');
        Route::delete('/{detail}', [OperationDetailController::class, 'destroy'])->name('operation-details.destroy');
    });

    Route::prefix('shipments')->group(function () {
        Route::get('/', [ShipmentController::class, 'index'])->name('shipments.index');
        Route::get('/create', [ShipmentController::class, 'create'])->name('shipments.create');
        Route::post('/', [ShipmentController::class, 'store'])->name('shipments.store');
        Route::get('/{shipment}',    [ShipmentController::class, 'show'])->name('shipments.show');
        Route::get('/download-template', [ShipmentController::class, 'downloadTemplate'])->name('shipments.download.template');
        Route::post('/upload', [ShipmentController::class, 'upload'])->name('shipments.upload');
        Route::get('/{shipment}/edit', [ShipmentController::class, 'edit'])->name('shipments.edit');
        Route::put('/{shipment}', [ShipmentController::class, 'update'])->name('shipments.update');
        Route::delete('/{shipment}', [ShipmentController::class, 'destroy'])->name('shipments.destroy');
    });

    // routes/web.php

    Route::prefix('floor-timings')->group(function () {
        Route::get('/', [FloorTimingController::class, 'index'])->name('floor-timings.index');
        Route::get('/download-template', [FloorTimingController::class, 'downloadTemplate'])->name('floor-timings.download.template');
        Route::post('/upload', [FloorTimingController::class, 'upload'])->name('floor-timings.upload');
        Route::get('/{timing}/edit', [FloorTimingController::class, 'edit'])->name('floor-timings.edit');
        Route::put('/{timing}', [FloorTimingController::class, 'update'])->name('floor-timings.update');
        Route::delete('/{timing}', [FloorTimingController::class, 'destroy'])->name('floor-timings.destroy');
    });

    Route::prefix('daily-reports')->group(function () {
        Route::get('/', [DailyReportController::class, 'index'])->name('daily-reports.index');
        Route::get('/create', [DailyReportController::class, 'create'])->name('daily-reports.create');
        Route::post('/', [DailyReportController::class, 'store'])->name('daily-reports.store');
        Route::get('/{report}/edit', [DailyReportController::class, 'edit'])->name('daily-reports.edit');
        Route::put('/{report}', [DailyReportController::class, 'update'])->name('daily-reports.update');
        Route::delete('/{report}', [DailyReportController::class, 'destroy'])->name('daily-reports.destroy');
    });
});

// Route::get('/todayReport', [ReportController::class, 'todayReport'])->name('todayReport');
// //todayGraph
// Route::get('/todayGraph', [ReportController::class, 'todayGraph'])->name('todayGraph');

Route::get('/Report', [ReportController::class, 'Report'])->name('Report');

// Route::get('/dashboard/full', [ReportController::class, 'fullDashboard'])->name('dashboard.full');
// Route::get('/dashboard/summary', [ReportController::class, 'summaryDashboard'])->name('dashboard.summary');
// Route::get('/dashboard/graphical', [ReportController::class, 'graphicalDashboard'])->name('dashboard.graphical');

























Route::get('/read/{notification}', [NotificationController::class, 'read'])->name('notification.read');


require __DIR__ . '/auth.php';

//php artisan command

Route::get('/foo', function () {
    Artisan::call('storage:link');
});

Route::get('/cleareverything', function () {
    $clearcache = Artisan::call('cache:clear');
    echo "Cache cleared<br>";

    $clearview = Artisan::call('view:clear');
    echo "View cleared<br>";

    $clearconfig = Artisan::call('config:cache');
    echo "Config cleared<br>";
});

Route::get('/key =', function () {
    $key =  Artisan::call('key:generate');
    echo "key:generate<br>";
});

Route::get('/migrate', function () {
    $migrate = Artisan::call('migrate');
    echo "migration create<br>";
});

Route::get('/migrate-fresh', function () {
    $fresh = Artisan::call('migrate:fresh --seed');
    echo "migrate:fresh --seed create<br>";
});

Route::get('/optimize', function () {
    $optimize = Artisan::call('optimize:clear');
    echo "optimize cleared<br>";
});
Route::get('/route-clear', function () {
    $route_clear = Artisan::call('route:clear');
    echo "route cleared<br>";
});

Route::get('/route-cache', function () {
    $route_cache = Artisan::call('route:cache');
    echo "route cache<br>";
});

Route::get('/updateapp', function () {
    $dump_autoload = Artisan::call('dump-autoload');
    echo 'dump-autoload complete';
});
