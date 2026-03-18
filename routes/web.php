<?php

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\DiagnosisController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\DrugSearchController;
use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\ClinicRegistrationController;
use App\Http\Controllers\ClinicPageController;
use App\Http\Controllers\OnlineBookingController;
use App\Http\Controllers\Admin\ClinicWebsiteController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\Admin\OfferController as AdminOfferController;
use App\Http\Controllers\Admin\PrescriptionController;
use App\Http\Controllers\Admin\InsuranceController;
use App\Http\Controllers\Admin\PatientFileController;
use App\Http\Controllers\Admin\QueueController;
use App\Http\Controllers\Admin\DoctorLeaveController;
use App\Http\Controllers\Admin\DiagnosisTemplateController;
use App\Http\Controllers\Admin\DrugInteractionController;
use App\Http\Controllers\Doctor\DiagnosisTemplateController as DoctorDiagnosisTemplateController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\PatientLedgerController;
use App\Http\Controllers\Admin\PregnancyController;
use App\Http\Controllers\Admin\PhotoTimelineController;
use App\Http\Controllers\Admin\PatientMedicalController;
use App\Http\Controllers\Admin\AIRadiologyController;
use App\Http\Controllers\Admin\RechargeController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\MedicalReportController;
use App\Http\Controllers\Admin\ChronicDashboardController;
use App\Http\Controllers\Admin\GrowthChartController;
use App\Http\Controllers\WaitingRoomController;
use App\Http\Controllers\SuperAdmin\ClinicManagementController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperDashboardController;
use App\Http\Controllers\SuperAdmin\OfferController as SuperOfferController;
use App\Http\Controllers\SuperAdmin\SettingsController as SuperSettingsController;
use Illuminate\Support\Facades\Route;

// SEO
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// Landing page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Language switcher
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Clinic Registration
Route::middleware('guest')->group(function () {
    Route::get('/register-clinic', [ClinicRegistrationController::class, 'showForm'])->name('register.clinic');
    Route::post('/register-clinic', [ClinicRegistrationController::class, 'register'])->name('register.clinic.store');
});
Route::get('/register-clinic/success', [ClinicRegistrationController::class, 'success'])->name('register.clinic.success');

// Public clinic website
Route::get('/clinic/{slug}', [ClinicPageController::class, 'show'])->name('clinic.page');
Route::post('/clinic/{slug}/book', [OnlineBookingController::class, 'store'])->name('clinic.book');

// Waiting Room Display (public, no auth required)
Route::get('/waiting-room/{slug}', [WaitingRoomController::class, 'show'])->name('waiting-room');

// Clinic suspended page
Route::get('/clinic-suspended', function () {
    return view('clinic.suspended');
})->name('clinic.suspended');

// ===== Super Admin Routes =====
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('super.')->group(function () {
    Route::get('/dashboard', [SuperDashboardController::class, 'index'])->name('dashboard');
    Route::get('/clinics', [ClinicManagementController::class, 'index'])->name('clinics.index');
    Route::get('/clinics/{clinic}', [ClinicManagementController::class, 'show'])->name('clinics.show');
    Route::patch('/clinics/{clinic}/status', [ClinicManagementController::class, 'updateStatus'])->name('clinics.status');
    Route::post('/clinics/{clinic}/add-points', [ClinicManagementController::class, 'addPoints'])->name('clinics.add-points');
    Route::post('/clinics/{clinic}/deduct-points', [ClinicManagementController::class, 'deductPoints'])->name('clinics.deduct-points');
    Route::patch('/clinics/{clinic}/free-mode', [ClinicManagementController::class, 'toggleFreeMode'])->name('clinics.free-mode');
    Route::post('/send-notification', [ClinicManagementController::class, 'sendNotification'])->name('send-notification');

    // Offers
    Route::get('/offers', [SuperOfferController::class, 'index'])->name('offers.index');
    Route::get('/offers/create', [SuperOfferController::class, 'create'])->name('offers.create');
    Route::post('/offers', [SuperOfferController::class, 'store'])->name('offers.store');
    Route::get('/offers/{offer}/edit', [SuperOfferController::class, 'edit'])->name('offers.edit');
    Route::put('/offers/{offer}', [SuperOfferController::class, 'update'])->name('offers.update');
    Route::patch('/offers/{offer}/toggle', [SuperOfferController::class, 'toggleStatus'])->name('offers.toggle');
    Route::delete('/offers/{offer}', [SuperOfferController::class, 'destroy'])->name('offers.destroy');

    // Recharge Requests
    Route::get('/recharge-requests', [ClinicManagementController::class, 'rechargeRequests'])->name('recharge.index');
    Route::patch('/recharge/{rechargeRequest}/approve', [ClinicManagementController::class, 'approveRecharge'])->name('recharge.approve');
    Route::patch('/recharge/{rechargeRequest}/reject', [ClinicManagementController::class, 'rejectRecharge'])->name('recharge.reject');

    // Platform Settings
    Route::get('/settings', [SuperSettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SuperSettingsController::class, 'update'])->name('settings.update');
});

// ===== Clinic Dashboard Routes =====
// Dashboard accessible even when pending (admin + staff)
Route::middleware(['auth', 'role:clinic_staff'])->prefix('dashboard')->name('dashboard')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index']);
});

// All clinic routes require active clinic (admin + staff with permissions)
Route::middleware(['auth', 'role:clinic_staff', 'clinic.active'])->prefix('dashboard')->name('dashboard')->group(function () {
    // Appointments
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('.appointments.index')->middleware('permission:appointments.view');
    Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('.appointments.create')->middleware('permission:appointments.create');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('.appointments.store')->middleware('permission:appointments.create');
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('.appointments.show')->middleware('permission:appointments.view');
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('.appointments.status')->middleware('permission:appointments.change_status');

    // Doctors
    Route::get('/doctors', [DoctorController::class, 'index'])->name('.doctors.index')->middleware('permission:doctors.view');
    Route::get('/doctors/create', [DoctorController::class, 'create'])->name('.doctors.create')->middleware('permission:doctors.create');
    Route::post('/doctors', [DoctorController::class, 'store'])->name('.doctors.store')->middleware('permission:doctors.create');
    Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('.doctors.show')->middleware('permission:doctors.view');
    Route::get('/doctors/{doctor}/edit', [DoctorController::class, 'edit'])->name('.doctors.edit')->middleware('permission:doctors.edit');
    Route::put('/doctors/{doctor}', [DoctorController::class, 'update'])->name('.doctors.update')->middleware('permission:doctors.edit');
    Route::patch('/doctors/{doctor}/toggle', [DoctorController::class, 'toggleStatus'])->name('.doctors.toggle')->middleware('permission:doctors.edit');
    Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy'])->name('.doctors.destroy')->middleware('permission:doctors.edit');

    // Patients
    Route::get('/patients', [PatientController::class, 'index'])->name('.patients.index')->middleware('permission:patients.view');
    Route::get('/patients/create', [PatientController::class, 'create'])->name('.patients.create')->middleware('permission:patients.create');
    Route::post('/patients', [PatientController::class, 'store'])->name('.patients.store')->middleware('permission:patients.create');
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('.patients.show')->middleware('permission:patients.view');
    Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('.patients.edit')->middleware('permission:patients.edit');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('.patients.update')->middleware('permission:patients.edit');
    Route::get('/patients/{patient}/timeline', [PatientController::class, 'timeline'])->name('.patients.timeline')->middleware('permission:patients.view');
    Route::get('/patients/{patient}/chronic-dashboard', [ChronicDashboardController::class, 'show'])->name('.patients.chronic-dashboard')->middleware('permission:patients.view');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('.patients.destroy')->middleware('permission:patients.edit');
    Route::get('/patients/{patient}/growth-chart', [GrowthChartController::class, 'show'])->name('.patients.growth-chart')->middleware('permission:patients.view');

    // Pregnancy Tracker
    Route::get('/patients/{patient}/pregnancy', [PregnancyController::class, 'index'])->name('.patients.pregnancy.index')->middleware('permission:patients.view');
    Route::get('/patients/{patient}/pregnancy/create', [PregnancyController::class, 'create'])->name('.patients.pregnancy.create')->middleware('permission:patients.edit');
    Route::post('/patients/{patient}/pregnancy', [PregnancyController::class, 'store'])->name('.patients.pregnancy.store')->middleware('permission:patients.edit');
    Route::get('/pregnancy/{pregnancy}', [PregnancyController::class, 'show'])->name('.pregnancy.show')->middleware('permission:patients.view');
    Route::post('/pregnancy/{pregnancy}/visit', [PregnancyController::class, 'addVisit'])->name('.pregnancy.visit')->middleware('permission:patients.edit');
    Route::patch('/pregnancy/{pregnancy}/complete', [PregnancyController::class, 'complete'])->name('.pregnancy.complete')->middleware('permission:patients.edit');
    Route::delete('/pregnancy/{pregnancy}', [PregnancyController::class, 'destroy'])->name('.pregnancy.destroy')->middleware('permission:patients.edit');

    // Patient Files
    Route::post('/patients/{patient}/files', [PatientFileController::class, 'store'])->name('.patients.files.store')->middleware('permission:patients.edit');
    Route::get('/patient-files/{patientFile}/download', [PatientFileController::class, 'download'])->name('.patients.files.download')->middleware('permission:patients.view');
    Route::delete('/patient-files/{patientFile}', [PatientFileController::class, 'destroy'])->name('.patients.files.destroy')->middleware('permission:patients.edit');

    // Photo Timeline (Before / After)
    Route::get('/patients/{patient}/photos', [PhotoTimelineController::class, 'index'])->name('.patients.photos.index')->middleware('permission:patients.view');
    Route::post('/patients/{patient}/photos', [PhotoTimelineController::class, 'store'])->name('.patients.photos.store')->middleware('permission:patients.edit');
    Route::get('/patients/{patient}/photos/compare', [PhotoTimelineController::class, 'compare'])->name('.patients.photos.compare')->middleware('permission:patients.view');
    Route::delete('/photo-records/{photo}', [PhotoTimelineController::class, 'destroy'])->name('.photo-records.destroy')->middleware('permission:patients.edit');

    // Insurance Providers
    Route::get('/insurance', [InsuranceController::class, 'index'])->name('.insurance.index');
    Route::post('/insurance', [InsuranceController::class, 'store'])->name('.insurance.store');
    Route::put('/insurance/{provider}', [InsuranceController::class, 'update'])->name('.insurance.update');
    Route::delete('/insurance/{provider}', [InsuranceController::class, 'destroy'])->name('.insurance.destroy');

    // Patient Insurance
    Route::post('/patients/{patient}/insurance', [InsuranceController::class, 'assignToPatient'])->name('.patients.insurance.store')->middleware('permission:patients.edit');
    Route::patch('/patient-insurance/{insurance}/remove', [InsuranceController::class, 'removeFromPatient'])->name('.patients.insurance.remove')->middleware('permission:patients.edit');

    // Patient Medical Features
    Route::post('/patients/{patient}/vitals', [PatientMedicalController::class, 'storeVitals'])->name('.patients.vitals.store')->middleware('permission:patients.edit');
    Route::post('/patients/{patient}/diseases', [PatientMedicalController::class, 'storeDisease'])->name('.patients.diseases.store')->middleware('permission:patients.edit');
    Route::patch('/chronic-diseases/{disease}/toggle', [PatientMedicalController::class, 'toggleDisease'])->name('.patients.diseases.toggle')->middleware('permission:patients.edit');
    Route::delete('/chronic-diseases/{disease}', [PatientMedicalController::class, 'destroyDisease'])->name('.patients.diseases.destroy')->middleware('permission:patients.edit');
    Route::post('/patients/{patient}/medications', [PatientMedicalController::class, 'storeMedication'])->name('.patients.medications.store')->middleware('permission:patients.edit');
    Route::patch('/medications/{medication}/toggle', [PatientMedicalController::class, 'toggleMedication'])->name('.patients.medications.toggle')->middleware('permission:patients.edit');
    Route::delete('/medications/{medication}', [PatientMedicalController::class, 'destroyMedication'])->name('.patients.medications.destroy')->middleware('permission:patients.edit');
    Route::post('/patients/{patient}/notes', [PatientMedicalController::class, 'storeNote'])->name('.patients.notes.store')->middleware('permission:patients.edit');
    Route::delete('/medical-notes/{note}', [PatientMedicalController::class, 'destroyNote'])->name('.patients.notes.destroy')->middleware('permission:patients.edit');

    // AI Radiology
    Route::get('/patients/{patient}/ai-radiology', [AIRadiologyController::class, 'index'])->name('.ai-radiology.index')->middleware('permission:patients.view');
    Route::post('/patients/{patient}/ai-radiology/analyze', [AIRadiologyController::class, 'analyze'])->name('.ai-radiology.analyze')->middleware('permission:patients.edit');
    Route::post('/patients/{patient}/ai-radiology/analyze-ajax', [AIRadiologyController::class, 'analyzeAjax'])->name('.ai-radiology.analyze-ajax')->middleware('permission:patients.edit');

    // Dental Chart
    Route::get('/patients/{patient}/dental-chart', [\App\Http\Controllers\Admin\DentalChartController::class, 'show'])->name('.patients.dental-chart.show')->middleware('permission:patients.view');
    Route::post('/patients/{patient}/dental-chart', [\App\Http\Controllers\Admin\DentalChartController::class, 'store'])->name('.patients.dental-chart.store')->middleware('permission:patients.edit');
    Route::get('/patients/{patient}/dental-chart/history', [\App\Http\Controllers\Admin\DentalChartController::class, 'history'])->name('.patients.dental-chart.history')->middleware('permission:patients.view');

    // Staff Management (admin only)
    Route::get('/staff', [StaffController::class, 'index'])->name('.staff.index')->middleware('permission:staff.view');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('.staff.create')->middleware('permission:staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('.staff.store')->middleware('permission:staff.create');
    Route::get('/staff/{user}/edit', [StaffController::class, 'edit'])->name('.staff.edit')->middleware('permission:staff.edit');
    Route::put('/staff/{user}', [StaffController::class, 'update'])->name('.staff.update')->middleware('permission:staff.edit');
    Route::patch('/staff/{user}/toggle', [StaffController::class, 'toggleStatus'])->name('.staff.toggle')->middleware('permission:staff.edit');

    // Permissions Management (admin only)
    Route::get('/permissions', [PermissionController::class, 'index'])->name('.permissions.index')->middleware('permission:permissions.manage');
    Route::post('/permissions', [PermissionController::class, 'update'])->name('.permissions.update')->middleware('permission:permissions.manage');
    Route::post('/permissions/roles', [PermissionController::class, 'storeRole'])->name('.permissions.roles.store')->middleware('permission:permissions.manage');
    Route::delete('/permissions/roles/{clinicRole}', [PermissionController::class, 'destroyRole'])->name('.permissions.roles.destroy')->middleware('permission:permissions.manage');

    // Diagnoses
    Route::get('/diagnoses', [DiagnosisController::class, 'index'])->name('.diagnoses.index')->middleware('permission:appointments.view');
    Route::get('/appointments/{appointment}/diagnosis', [DiagnosisController::class, 'create'])->name('.diagnoses.create')->middleware('permission:appointments.view');
    Route::post('/appointments/{appointment}/diagnosis', [DiagnosisController::class, 'store'])->name('.diagnoses.store')->middleware('permission:appointments.edit');
    Route::get('/diagnoses/{diagnosis}', [DiagnosisController::class, 'show'])->name('.diagnoses.show')->middleware('permission:appointments.view');

    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('.invoices.index')->middleware('permission:invoices.view');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('.invoices.show')->middleware('permission:invoices.view');
    Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('.invoices.update')->middleware('permission:invoices.edit');

    // Branches
    Route::get('/branches', [BranchController::class, 'index'])->name('.branches.index');
    Route::get('/branches/create', [BranchController::class, 'create'])->name('.branches.create');
    Route::post('/branches', [BranchController::class, 'store'])->name('.branches.store');
    Route::get('/branches/{branch}/edit', [BranchController::class, 'edit'])->name('.branches.edit');
    Route::put('/branches/{branch}', [BranchController::class, 'update'])->name('.branches.update');
    Route::delete('/branches/{branch}', [BranchController::class, 'destroy'])->name('.branches.destroy');
    Route::post('/branches/{branch}/switch', [BranchController::class, 'switchBranch'])->name('.branches.switch');

    // Drug Search
    Route::get('/drugs/search', [DrugSearchController::class, 'search'])->name('.drugs.search');

    // Pharmacy
    Route::get('/pharmacy', [\App\Http\Controllers\Admin\PharmacyController::class, 'index'])->name('.pharmacy.index');
    Route::get('/pharmacy/api-lookup', [\App\Http\Controllers\Admin\PharmacyController::class, 'apiLookup'])->name('.pharmacy.api-lookup');
    Route::get('/pharmacy/{drug}', [\App\Http\Controllers\Admin\PharmacyController::class, 'show'])->name('.pharmacy.show');

    // Services by Doctor
    Route::get('/doctors/{doctor}/services', [AppointmentController::class, 'servicesByDoctor'])->name('.doctors.services');

    // Offers (view only for clinic admins)
    Route::get('/offers', [AdminOfferController::class, 'index'])->name('.offers.index');

    // Prescriptions
    Route::get('/prescriptions/{prescription}/print', [PrescriptionController::class, 'print'])->name('.prescriptions.print');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('.reports.index');

    // Medical Reports (PDF)
    Route::get('/diagnoses/{diagnosis}/report', [MedicalReportController::class, 'generate'])->name('.diagnoses.report')->middleware('permission:appointments.view');
    Route::get('/invoices/{invoice}/pdf', [MedicalReportController::class, 'generateInvoice'])->name('.invoices.pdf')->middleware('permission:invoices.view');

    // Audit Logs
    Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('.audit-logs.index');

    // Expenses
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('.expenses.index')->middleware('permission:invoices.view');
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('.expenses.create')->middleware('permission:invoices.edit');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('.expenses.store')->middleware('permission:invoices.edit');
    Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('.expenses.edit')->middleware('permission:invoices.edit');
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('.expenses.update')->middleware('permission:invoices.edit');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('.expenses.destroy')->middleware('permission:invoices.edit');
    Route::post('/expenses/categories', [ExpenseController::class, 'storeCategory'])->name('.expenses.categories.store')->middleware('permission:invoices.edit');
    Route::delete('/expenses/categories/{category}', [ExpenseController::class, 'destroyCategory'])->name('.expenses.categories.destroy')->middleware('permission:invoices.edit');

    // Doctor Leaves
    Route::get('/leaves', [DoctorLeaveController::class, 'index'])->name('.leaves.index')->middleware('permission:doctors.view');
    Route::get('/leaves/create', [DoctorLeaveController::class, 'create'])->name('.leaves.create')->middleware('permission:doctors.create');
    Route::post('/leaves', [DoctorLeaveController::class, 'store'])->name('.leaves.store')->middleware('permission:doctors.create');
    Route::patch('/leaves/{leave}/approve', [DoctorLeaveController::class, 'approve'])->name('.leaves.approve')->middleware('permission:doctors.edit');
    Route::patch('/leaves/{leave}/reject', [DoctorLeaveController::class, 'reject'])->name('.leaves.reject')->middleware('permission:doctors.edit');
    Route::delete('/leaves/{leave}', [DoctorLeaveController::class, 'destroy'])->name('.leaves.destroy')->middleware('permission:doctors.edit');

    // Patient Ledger (Debt Tracking)
    Route::get('/ledger', [PatientLedgerController::class, 'index'])->name('.ledger.index')->middleware('permission:invoices.view');
    Route::get('/ledger/{patient}', [PatientLedgerController::class, 'show'])->name('.ledger.show')->middleware('permission:invoices.view');
    Route::post('/ledger/{patient}/payment', [PatientLedgerController::class, 'addPayment'])->name('.ledger.payment')->middleware('permission:invoices.edit');
    Route::post('/ledger/{patient}/debt', [PatientLedgerController::class, 'addDebt'])->name('.ledger.debt')->middleware('permission:invoices.edit');

    // Reviews
    Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('.reviews.index');
    Route::patch('/reviews/{review}/toggle', [\App\Http\Controllers\Admin\ReviewController::class, 'toggleVisibility'])->name('.reviews.toggle');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('.reviews.destroy');

    // Coupons
    Route::get('/coupons', [\App\Http\Controllers\Admin\CouponController::class, 'index'])->name('.coupons.index');
    Route::get('/coupons/create', [\App\Http\Controllers\Admin\CouponController::class, 'create'])->name('.coupons.create');
    Route::post('/coupons', [\App\Http\Controllers\Admin\CouponController::class, 'store'])->name('.coupons.store');
    Route::get('/coupons/{coupon}/edit', [\App\Http\Controllers\Admin\CouponController::class, 'edit'])->name('.coupons.edit');
    Route::put('/coupons/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'update'])->name('.coupons.update');
    Route::patch('/coupons/{coupon}/toggle', [\App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('.coupons.toggle');
    Route::delete('/coupons/{coupon}', [\App\Http\Controllers\Admin\CouponController::class, 'destroy'])->name('.coupons.destroy');
    Route::post('/coupons/validate', [\App\Http\Controllers\Admin\CouponController::class, 'validateCoupon'])->name('.coupons.validate');

    // Queue Management
    Route::get('/queue', [QueueController::class, 'index'])->name('.queue.index')->middleware('permission:appointments.view');
    Route::patch('/appointments/{appointment}/checkin', [QueueController::class, 'checkIn'])->name('.queue.checkin')->middleware('permission:appointments.change_status');
    Route::patch('/appointments/{appointment}/queue-skip', [QueueController::class, 'skip'])->name('.queue.skip')->middleware('permission:appointments.change_status');

    // Clinic Website Settings (admin only)
    Route::get('/website', [ClinicWebsiteController::class, 'edit'])->name('.website.edit');
    Route::put('/website', [ClinicWebsiteController::class, 'update'])->name('.website.update');

    // Recharge
    Route::get('/recharge', [RechargeController::class, 'index'])->name('.recharge.index');
    Route::post('/recharge', [RechargeController::class, 'store'])->name('.recharge.store');

    // Diagnosis Templates
    Route::get('/diagnosis-templates', [DiagnosisTemplateController::class, 'index'])->name('.diagnosis-templates.index');
    Route::post('/diagnosis-templates', [DiagnosisTemplateController::class, 'store'])->name('.diagnosis-templates.store');
    Route::delete('/diagnosis-templates/{template}', [DiagnosisTemplateController::class, 'destroy'])->name('.diagnosis-templates.destroy');
    Route::get('/diagnosis-templates/{template}/load', [DiagnosisTemplateController::class, 'loadTemplate'])->name('.diagnosis-templates.load');

    // Drug Interactions
    Route::get('/drug-interactions', [DrugInteractionController::class, 'index'])->name('.drug-interactions.index');
    Route::post('/drug-interactions', [DrugInteractionController::class, 'store'])->name('.drug-interactions.store');
    Route::delete('/drug-interactions/{interaction}', [DrugInteractionController::class, 'destroy'])->name('.drug-interactions.destroy');
    Route::post('/drug-interactions/check', [DrugInteractionController::class, 'check'])->name('.drug-interactions.check');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('.settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('.settings.update');
});

// ===== Doctor Portal Routes =====
Route::middleware(['auth', 'role:doctor', 'clinic.active'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/', [DoctorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/appointments', [DoctorDashboardController::class, 'appointments'])->name('appointments');
    Route::get('/appointments/{appointment}', [DoctorDashboardController::class, 'showAppointment'])->name('appointment.show');
    Route::patch('/appointments/{appointment}/status', [DoctorDashboardController::class, 'updateStatus'])->name('appointment.status');
    Route::post('/appointments/{appointment}/diagnosis', [DoctorDashboardController::class, 'storeDiagnosis'])->name('diagnosis.store');
    Route::get('/patients/{patient}/history', [DoctorDashboardController::class, 'patientHistory'])->name('patient.history');
    Route::get('/prescriptions/{prescription}/print', [PrescriptionController::class, 'print'])->name('prescription.print');
    Route::get('/queue', [DoctorDashboardController::class, 'queue'])->name('queue');
    Route::patch('/appointments/{appointment}/call', [DoctorDashboardController::class, 'callPatient'])->name('appointment.call');
    Route::patch('/appointments/{appointment}/start-from-queue', [DoctorDashboardController::class, 'startFromQueue'])->name('appointment.start-from-queue');

    // Settings
    Route::get('/settings', [DoctorDashboardController::class, 'settings'])->name('settings');
    Route::put('/settings', [DoctorDashboardController::class, 'updateSettings'])->name('settings.update');
    Route::delete('/services/{service}', [DoctorDashboardController::class, 'destroyService'])->name('services.destroy');

    // Drug Search (shared)
    Route::get('/drugs/search', [DrugSearchController::class, 'search'])->name('drugs.search');

    // Diagnosis Templates
    Route::get('/diagnosis-templates', [DoctorDiagnosisTemplateController::class, 'index'])->name('diagnosis-templates.index');
    Route::post('/diagnosis-templates', [DoctorDiagnosisTemplateController::class, 'store'])->name('diagnosis-templates.store');
    Route::delete('/diagnosis-templates/{template}', [DoctorDiagnosisTemplateController::class, 'destroy'])->name('diagnosis-templates.destroy');
    Route::get('/diagnosis-templates/{template}/load', [DoctorDiagnosisTemplateController::class, 'load'])->name('diagnosis-templates.load');
});

// Auth routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Push Notifications
    Route::post('/push/subscribe', [PushSubscriptionController::class, 'subscribe'])->name('push.subscribe');
    Route::post('/push/unsubscribe', [PushSubscriptionController::class, 'unsubscribe'])->name('push.unsubscribe');

    // Notifications API
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

});

require __DIR__.'/auth.php';
