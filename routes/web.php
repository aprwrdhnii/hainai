<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\MechanicController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ServicePackageController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExpenseController;

// ===== PUBLIC ROUTES (Homepage untuk Pelanggan) =====
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/spareparts-katalog', [HomeController::class, 'spareparts'])->name('home.spareparts');
Route::get('/estimasi-biaya', [HomeController::class, 'estimasi'])->name('home.estimasi');
Route::get('/cek-service', [HomeController::class, 'cekService'])->name('home.cek-service');
Route::post('/testimoni', [HomeController::class, 'submitTestimonial'])->name('home.testimonial.submit');

// ===== AUTH ROUTES =====
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ===== ADMIN ROUTES (Protected) =====
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // User Management (Admin only)
    Route::resource('users', UserController::class)->except(['show']);
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Notifications
    Route::post('notifications/read-all', function() {
        \App\Models\Notification::where('is_read', false)->update(['is_read' => true]);
        return back();
    })->name('notifications.read-all');

    Route::resource('customers', CustomerController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('mechanics', MechanicController::class);
    Route::resource('spareparts', SparepartController::class);
    Route::post('spareparts/{sparepart}/add-stock', [SparepartController::class, 'addStock'])->name('spareparts.add-stock');
    Route::post('spareparts-quick', [SparepartController::class, 'quickStore'])->name('spareparts.quick-store');
    Route::post('spareparts-import', [SparepartController::class, 'import'])->name('spareparts.import');
    Route::get('spareparts-template', [SparepartController::class, 'downloadTemplate'])->name('spareparts.template');

    Route::resource('services', ServiceController::class);
    Route::post('services/{service}/add-part', [ServiceController::class, 'addPart'])->name('services.add-part');
    Route::delete('services/{service}/remove-part/{detail}', [ServiceController::class, 'removePart'])->name('services.remove-part');
    Route::patch('services/{service}/status', [ServiceController::class, 'updateStatus'])->name('services.update-status');

    Route::resource('expenses', ExpenseController::class)->except(['show']);

    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/create/{service}', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('invoices/{service}', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('invoices/{invoice}/pay', [InvoiceController::class, 'pay'])->name('invoices.pay');
    Route::get('invoices/{service}/print', [InvoiceController::class, 'print'])->name('invoices.print');

    // Homepage Management
    Route::resource('promos', PromoController::class)->except(['show']);
    Route::resource('service-packages', ServicePackageController::class)->except(['show']);
    Route::get('testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
    Route::post('testimonials/{testimonial}/approve', [TestimonialController::class, 'approve'])->name('testimonials.approve');
    Route::post('testimonials/{testimonial}/reject', [TestimonialController::class, 'reject'])->name('testimonials.reject');
    Route::delete('testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/services', [ReportController::class, 'services'])->name('services');
        Route::get('/revenue', [ReportController::class, 'revenue'])->name('revenue');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/mechanics', [ReportController::class, 'mechanics'])->name('mechanics');
        Route::post('/send-whatsapp', [ReportController::class, 'sendWhatsApp'])->name('send-whatsapp');
        Route::get('/preview-whatsapp', [ReportController::class, 'previewWhatsApp'])->name('preview-whatsapp');
    });

    Route::prefix('whatsapp')->name('admin.whatsapp.')->group(function () {
        Route::get('/', [WhatsAppController::class, 'index'])->name('index');
        Route::get('/status', [WhatsAppController::class, 'status'])->name('status');
        Route::post('/logout', [WhatsAppController::class, 'logout'])->name('logout');
        Route::post('/restart', [WhatsAppController::class, 'restart'])->name('restart');
        Route::post('/test', [WhatsAppController::class, 'testSend'])->name('test');
    });
});

// API
Route::get('api/customers/{customer}/vehicles', [ServiceController::class, 'getVehiclesByCustomer'])->name('api.customer.vehicles');

// Cron Job untuk kirim laporan WhatsApp otomatis
Route::get('cron/send-daily-report/{secret}', function($secret) {
    if ($secret !== 'hainai2025secret') {
        return response('Unauthorized', 401);
    }
    
    $whatsapp = new \App\Services\WhatsAppService();
    $phone = config('services.whatsapp.boss_phone');
    
    if ($phone) {
        $result = $whatsapp->sendDailyReport($phone);
        return response()->json(['success' => $result, 'message' => $result ? 'Laporan terkirim' : 'Gagal kirim']);
    }
    
    return response()->json(['success' => false, 'message' => 'No phone configured']);
});
