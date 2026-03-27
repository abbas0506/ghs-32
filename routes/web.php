<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BulkInvoiceController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\FeeVoucherController;
use App\Http\Controllers\GallaryController;
use App\Http\Controllers\GradeSubjectController;
use App\Http\Controllers\ImportStudentController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\LessonPlanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SectionAttendanceController;
use App\Http\Controllers\SectionCardController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SectionResultController;
use App\Http\Controllers\SectionScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\SyllabusController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskLineController;
use App\Http\Controllers\TestSubjectController;
use App\Http\Controllers\TestSubjectResultController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\UserScheduleController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\VoucherPaymentController;
use App\Models\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('dashboard');
    } else {
        $events = Event::latest()->take(3)->get();
        return view('index', compact('events'));
        // return view('login');
    }
});


// Route::view('/', 'index');
Route::view('about', 'about');
Route::view('contact', 'contact');
Route::get('faculty', [FacultyController::class, 'index']);
Route::get('gallary', [GallaryController::class, 'index']);

Route::view('login', 'login');
Route::get('switch/as/{role}', [UserController::class, 'switchAs']);

Route::view('forgot', 'forgot');
Route::post('forgot', [AuthController::class, 'forgot']);

Route::post('login', [AuthController::class, 'login']);
Route::view('change/password', 'change_password');
Route::post('change/password', [AuthController::class, 'changePassword'])->name('change.password');


Route::post('login/as', [AuthController::class, 'loginAs'])->name('login.as');
Route::get('signout', [AuthController::class, 'signout'])->name('signout');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::view('config', 'config')->name('config');

    // users
    Route::resource('users', UserController::class);
    Route::resource('user.roles',  UserRoleController::class);


    // subjects
    Route::resource('subjects', SubjectController::class);
    Route::resource('sections', SectionController::class);

    // Grade vs subjects
    Route::resource('grade-subjects', GradeSubjectController::class);

    // Classes/Sections
    Route::get('sections/list/{page}', [SectionController::class, 'list'])->name('sections.list');
    Route::resource('section.students', StudentController::class);
    Route::get('sections/import/{section}', [SectionController::class, 'import'])->name('sections.import');
    Route::post('sections/import', [SectionController::class, 'postImport'])->name('sections.import.post');
    Route::get('sections/{section}/export', [SectionController::class, 'export'])->name('sections.export');
    Route::post('sections/export', [SectionController::class, 'postExport'])->name('sections.export.post');
    Route::get('sections/{section}/clean', [SectionController::class, 'clean'])->name('sections.clean');
    Route::get('section/{section}/reset-index', [SectionController::class, 'resetIndex'])->name('sections.reset');
    Route::post('section/{section}/reset-rollno', [SectionController::class, 'resetRollNo'])->name('sections.reset.rollno');
    Route::post('sections/{section}/clean', [SectionController::class, 'postClean'])->name('sections.clean.post');

    // student cards
    Route::resource('section.cards', SectionCardController::class);
    Route::get('section/cards/{section}/print', [SectionCardController::class, 'print'])->name('section.cards.print');

    // schedule
    Route::resource('section.lecture.schedule', ScheduleController::class);
    Route::get('class-schedule', [SectionScheduleController::class, 'index'])->name('class-schedule');
    Route::get('class-schedule/print', [SectionScheduleController::class, 'print'])->name('class-schedule.print');
    Route::post('class-schedule/post', [SectionScheduleController::class, 'post'])->name('class-schedule.post');
    Route::post('class-schedule/clear', [SectionScheduleController::class, 'clear']);
    Route::get('user-schedule', [UserScheduleController::class, 'index'])->name('user-schedule');
    Route::get('user-schedule/view', [UserScheduleController::class, 'show'])->name('user-schedule.show');
    Route::get('user-schedule/print', [UserScheduleController::class, 'print'])->name('user-schedule.print');
    Route::post('user-schedule/post', [UserScheduleController::class, 'post'])->name('user-schedule.post');

    // attendance
    Route::get('attendance/summary', [SectionAttendanceController::class, 'summary'])->name('attendance.summary');
    Route::get('attendance/filter', [SectionAttendanceController::class, 'filter'])->name('attendance.filter');
    Route::post('attendances/filter', [SectionAttendanceController::class, 'filter'])->name('attendance.filter');
    Route::resource('section.attendance', SectionAttendanceController::class);

    // Fee
    Route::resource('bulk-invoices', BulkInvoiceController::class);
    Route::get('/ledger', [LedgerController::class, 'index'])->name('ledger.index');

    Route::post('bulk-invoices/search/id', [BulkInvoiceController::class, 'searchById'])->name('bulk-invoices.search.id');
    Route::post('bulk-invoices/search/name', [BulkInvoiceController::class, 'searchByName'])->name('bulk-invoices.search.name');
    Route::post('bulk-invoices/search/class', [BulkInvoiceController::class, 'searchByClass'])->name('bulk-invoices.search.class');
    Route::post('bulk-invoices/print/checked', [BulkInvoiceController::class, 'print'])->name('bulk-invoices.print');

    // Fee
    Route::resource('fee-vouchers', FeeVoucherController::class);
    // salaries
    Route::resource('salaries', SalaryController::class);

    // Expenses
    Route::resource('expenses', ExpenseController::class);

    Route::resource('voucher.section.payments', VoucherPaymentController::class);
    Route::get('voucher/{voucher}/section/{section}/missing/import', [VoucherPaymentController::class, 'import'])->name('voucher.section.payments.import');
    Route::post('voucher/{voucher}/section/{section}/missing/import', [VoucherPaymentController::class, 'postImport'])->name('voucher.section.payments.import.post');
    Route::delete('voucher/{voucher}/section/{section}/clean', [VoucherPaymentController::class, 'postClean'])->name('voucher.section.payments.clean');

    // assessment
    Route::resource('tests', TestController::class);
    Route::resource('test.section.results', SectionResultController::class);
    Route::resource('test.test-subjects', TestSubjectController::class);
    Route::resource('test-subject.results', TestSubjectResultController::class);
    Route::resource('test-subject.import', ImportStudentController::class);

    // lessons
    Route::resource('lessons', LessonController::class);

    // syllabus
    Route::resource('syllabi', SyllabusController::class);
    // Route::resource('grade.syllabi', SyllabusController::class);

    // lock /unlock
    Route::patch('test/{id}/lock', [TestController::class, 'lock'])->name('test.lock');
    Route::patch('test/{id}/unlock', [TestController::class, 'unlock'])->name('test.unlock');
    Route::patch('test-subject/{id}/lock', [testSubjectController::class, 'lock'])->name('test-subject.lock');
    Route::patch('test-subject/{id}/unlock', [testSubjectController::class, 'unlock'])->name('test-subject.unlock');

    // Tasks
    Route::resource('tasks', TaskController::class);
    Route::resource('task.task-lines', TaskLineController::class);

    // Reports
    Route::get('reports/test-subjects/{s}/result/pdf', [ReportController::class, 'subjectResult'])->name('subject-result');
    Route::get('reports/tests/{t}/sections/{s}/result/pdf', [ReportController::class, 'sectionResult'])->name('section-result');
    Route::get('reports/tests/{t}/sections/{s}/positions/pdf', [ReportController::class, 'sectionPositions'])->name('section-positions');
    Route::get('reports/tests/{t}/sections/{s}/report-cards/pdf', [ReportController::class, 'reportCards'])->name('report-cards');
});
