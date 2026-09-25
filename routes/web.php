<?php

use App\Http\Controllers\Admin\AdminAuditLogController;
use App\Http\Controllers\Admin\AdminCmsController;
use App\Http\Controllers\Admin\AdminConsultationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminInstitutionController;
use App\Http\Controllers\Admin\AdminInstrumentController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminParticipantController;
use App\Http\Controllers\Admin\AdminPeriodController;
use App\Http\Controllers\Admin\AdminProgramController;
use App\Http\Controllers\Admin\AdminResultController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminTrainingController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminMediaController;
use App\Http\Controllers\Admin\AdminMasterDataController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ParticipantDashboardController;
use App\Http\Controllers\PublicAssessmentController;
use App\Http\Controllers\PublicCatalogController;
use App\Http\Controllers\PublicCmsController;
use App\Http\Controllers\PublicConsultationController;
use App\Http\Controllers\PublicTrainingController;
use App\Http\Controllers\Api\MasterDataApiController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\QuickCheckApiController;

// API Master Data Cascading Autocomplete
Route::prefix('api/master')->group(function () {
    Route::get('/provinces', [MasterDataApiController::class, 'provinces']);
    Route::get('/regencies', [MasterDataApiController::class, 'regencies']);
    Route::get('/schools', [MasterDataApiController::class, 'schools']);
    Route::get('/universities', [MasterDataApiController::class, 'universities']);
    Route::get('/faculties', [MasterDataApiController::class, 'faculties']);
    Route::get('/study-programs', [MasterDataApiController::class, 'studyPrograms']);
    Route::get('/occupations', [MasterDataApiController::class, 'occupations']);
    Route::post('/pending-institutions', [MasterDataApiController::class, 'storePendingInstitution']);
    Route::post('/instant-university', [MasterDataApiController::class, 'instantUniversity']);
});

Route::post('/api/assessment/quick-check', [QuickCheckApiController::class, 'check'])->name('api.assessment.quick_check');
Route::post('/api/events/verify', [PublicAssessmentController::class, 'verifyEventCode'])->name('api.events.verify');


// --- PUBLIC ROUTES ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-ruhiologi', [HomeController::class, 'aboutRuhiology'])->name('about.ruhiology');
Route::get('/tentang-institute', [HomeController::class, 'aboutInstitute'])->name('about.institute');

// RQ Assessment Public Engine
Route::get('/assessment', [PublicAssessmentController::class, 'index'])->name('assessment.index');
Route::post('/assessment/register', [PublicAssessmentController::class, 'register'])->name('assessment.register');
Route::post('/assessment/check-score', [PublicAssessmentController::class, 'checkScore'])->name('assessment.check_score');
Route::post('/assessment/verify', [PublicAssessmentController::class, 'verify'])->name('assessment.verify');
Route::get('/assessment/take/{period_code}/{type}', [PublicAssessmentController::class, 'take'])->name('assessment.take');
Route::post('/assessment/submit/{period_code}/{type}', [PublicAssessmentController::class, 'submit'])->name('assessment.submit');
Route::get('/assessment/result/{submission_code}', [PublicAssessmentController::class, 'result'])->name('assessment.result');
Route::get('/assessment/result/{submission_code}/certificate', [PublicAssessmentController::class, 'certificate'])->name('assessment.certificate');
Route::post('/assessment/reflection/{submission_code}', [PublicAssessmentController::class, 'submitReflection'])->name('assessment.reflection');

// Training Center
Route::get('/training', [PublicTrainingController::class, 'index'])->name('training.index');
Route::get('/training/{slug}', [PublicTrainingController::class, 'show'])->name('training.show');
Route::post('/training/{slug}/register', [PublicTrainingController::class, 'register'])->name('training.register');

// Book Store / Catalog
Route::get('/katalog', [PublicCatalogController::class, 'index'])->name('catalog.index');
Route::get('/katalog/{slug}', [PublicCatalogController::class, 'show'])->name('catalog.show');
Route::post('/katalog/order', [PublicCatalogController::class, 'order'])->name('catalog.order');
Route::get('/order/invoice/{order_number}', [PublicCatalogController::class, 'invoice'])->name('catalog.invoice');

// CMS (Articles, News, Quotes)
Route::get('/artikel', [PublicCmsController::class, 'articles'])->name('articles.index');
Route::get('/artikel/{slug}', [PublicCmsController::class, 'articleShow'])->name('articles.show');
Route::get('/berita', [PublicCmsController::class, 'news'])->name('news.index');
Route::get('/berita/{slug}', [PublicCmsController::class, 'newsShow'])->name('news.show');
Route::get('/inspirasi', [PublicCmsController::class, 'quotes'])->name('quotes.index');

// Consultation
Route::get('/konsultasi', [PublicConsultationController::class, 'index'])->name('consultation.index');
Route::post('/konsultasi', [PublicConsultationController::class, 'store'])->name('consultation.store');

// AUTH ROUTES
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// PARTICIPANT DASHBOARD
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ParticipantDashboardController::class, 'index'])->name('dashboard');
});

// --- ADMINISTRATIVE MANAGEMENT SYSTEM (/admin) ---
Route::middleware(['auth', \App\Http\Middleware\EnsureAdminAccess::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Master Data Management (Regional, Academic, Importer)
        Route::get('/master-data', [AdminMasterDataController::class, 'index'])->name('master_data.index');
        Route::post('/master-data/provinces', [AdminMasterDataController::class, 'storeProvince'])->name('master_data.provinces.store');
        Route::post('/master-data/regencies', [AdminMasterDataController::class, 'storeRegency'])->name('master_data.regencies.store');
        Route::post('/master-data/schools', [AdminMasterDataController::class, 'storeSchool'])->name('master_data.schools.store');
        Route::post('/master-data/universities', [AdminMasterDataController::class, 'storeUniversity'])->name('master_data.universities.store');
        Route::post('/master-data/occupations', [AdminMasterDataController::class, 'storeOccupation'])->name('master_data.occupations.store');
        Route::put('/master-data/pending/{pending}', [AdminMasterDataController::class, 'updatePendingStatus'])->name('master_data.pending.update');
        Route::post('/master-data/import', [AdminMasterDataController::class, 'importCsv'])->name('master_data.import');

        // Multi-Institution Management
        Route::resource('institutions', AdminInstitutionController::class);

        // Programs Management
        Route::resource('programs', AdminProgramController::class);

        // Participant Management
        Route::resource('participants', AdminParticipantController::class);

        // Assessment Engine: Instruments, Dimensions, Questions & Scoring
        Route::get('/instruments', [AdminInstrumentController::class, 'index'])->name('instruments.index');
        Route::get('/instruments/{instrument}', [AdminInstrumentController::class, 'show'])->name('instruments.show');
        Route::post('/instruments', [AdminInstrumentController::class, 'storeInstrument'])->name('instruments.store');
        Route::post('/instruments/{instrument}/dimensions', [AdminInstrumentController::class, 'storeDimension'])->name('instruments.dimensions.store');
        Route::post('/instruments/{instrument}/questions', [AdminInstrumentController::class, 'storeQuestion'])->name('instruments.questions.store');
        Route::post('/instruments/{instrument}/scoring', [AdminInstrumentController::class, 'updateScoringRule'])->name('instruments.scoring.update');

        // Assessment Events Management (Event / Batch Program)
        Route::get('/events', [AdminEventController::class, 'index'])->name('events.index');
        Route::post('/events', [AdminEventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}', [AdminEventController::class, 'show'])->name('events.show');
        Route::put('/events/{event}', [AdminEventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [AdminEventController::class, 'destroy'])->name('events.destroy');

        // Assessment Periods (Pretest / Posttest)
        Route::get('/periods', [AdminPeriodController::class, 'index'])->name('periods.index');
        Route::post('/periods', [AdminPeriodController::class, 'store'])->name('periods.store');
        Route::put('/periods/{period}', [AdminPeriodController::class, 'update'])->name('periods.update');

        // Assessment Results & Multi-dimensional Reporting Export
        Route::get('/results', [AdminResultController::class, 'index'])->name('results.index');
        Route::get('/results/{submission}', [AdminResultController::class, 'show'])->name('results.show');
        Route::get('/reports/export-csv', [AdminResultController::class, 'exportCsv'])->name('reports.export_csv');
        Route::get('/reports/export-pdf', [AdminResultController::class, 'exportPdf'])->name('reports.export_pdf');

        // Training Center Management
        Route::get('/training', [AdminTrainingController::class, 'index'])->name('training.index');
        Route::get('/training/create', [AdminTrainingController::class, 'create'])->name('training.create');
        Route::post('/training', [AdminTrainingController::class, 'store'])->name('training.store');
        Route::post('/training/{training}/batches', [AdminTrainingController::class, 'storeBatch'])->name('training.batches.store');
        Route::get('/training-registrations', [AdminTrainingController::class, 'registrations'])->name('training.registrations');
        Route::put('/training-registrations/{registration}', [AdminTrainingController::class, 'updateRegistrationStatus'])->name('training.registrations.update');

        // Store & Order Management
        Route::get('/products', [AdminCmsController::class, 'products'])->name('products.index');
        Route::post('/products', [AdminCmsController::class, 'storeProduct'])->name('products.store');
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update_status');

        // Consultation Management
        Route::get('/consultations', [AdminConsultationController::class, 'index'])->name('consultations.index');
        Route::put('/consultations/{consultation}', [AdminConsultationController::class, 'updateStatus'])->name('consultations.update_status');

        // CMS (Articles, Quotes, Testimonials)
        Route::get('/articles', [AdminCmsController::class, 'articles'])->name('articles.index');
        Route::post('/articles', [AdminCmsController::class, 'storeArticle'])->name('articles.store');
        Route::get('/quotes', [AdminCmsController::class, 'quotes'])->name('quotes.index');
        Route::post('/quotes', [AdminCmsController::class, 'storeQuote'])->name('quotes.store');
        Route::get('/testimonials', [AdminCmsController::class, 'testimonials'])->name('testimonials.index');
        Route::put('/testimonials/{testimonial}', [AdminCmsController::class, 'updateTestimonialStatus'])->name('testimonials.update_status');

        // Media Library
        Route::get('/media', [AdminMediaController::class, 'index'])->name('media.index');
        Route::post('/media', [AdminMediaController::class, 'store'])->name('media.store');
        Route::delete('/media/{media}', [AdminMediaController::class, 'destroy'])->name('media.destroy');

        // User Management
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.update_role');

        // Audit Logs (Read-only)
        Route::get('/audit-logs', [AdminAuditLogController::class, 'index'])->name('audit_logs.index');

        // System Settings
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    });
