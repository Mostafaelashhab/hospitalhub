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
use App\Http\Controllers\Admin\ClinicWebsiteController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\SuperAdmin\ClinicManagementController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperDashboardController;
use Illuminate\Support\Facades\Route;

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
});

// ===== Clinic Dashboard Routes =====
// Dashboard accessible even when pending (admin + staff)
Route::middleware(['auth', 'role:admin,doctor,accountant,secretary'])->prefix('dashboard')->name('dashboard')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index']);
});

// All clinic routes require active clinic (admin + staff with permissions)
Route::middleware(['auth', 'role:admin,doctor,accountant,secretary', 'clinic.active'])->prefix('dashboard')->name('dashboard')->group(function () {
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

    // Patients
    Route::get('/patients', [PatientController::class, 'index'])->name('.patients.index')->middleware('permission:patients.view');
    Route::get('/patients/create', [PatientController::class, 'create'])->name('.patients.create')->middleware('permission:patients.create');
    Route::post('/patients', [PatientController::class, 'store'])->name('.patients.store')->middleware('permission:patients.create');
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('.patients.show')->middleware('permission:patients.view');
    Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('.patients.edit')->middleware('permission:patients.edit');
    Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('.patients.update')->middleware('permission:patients.edit');
    Route::get('/patients/{patient}/timeline', [PatientController::class, 'timeline'])->name('.patients.timeline')->middleware('permission:patients.view');

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

    // Clinic Website Settings (admin only)
    Route::get('/website', [ClinicWebsiteController::class, 'edit'])->name('.website.edit');
    Route::put('/website', [ClinicWebsiteController::class, 'update'])->name('.website.update');

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

    // Settings
    Route::get('/settings', [DoctorDashboardController::class, 'settings'])->name('settings');
    Route::put('/settings', [DoctorDashboardController::class, 'updateSettings'])->name('settings.update');

    // Drug Search (shared)
    Route::get('/drugs/search', [DrugSearchController::class, 'search'])->name('drugs.search');
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
