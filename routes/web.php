<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

// ================= API ROUTES (Untuk Dynamic Content) =================

// Health check endpoint
Route::get('/api/v1/health', function () {
    return response()->json(['status' => 'ok']);
});

// ================= API: NEWS =================
Route::get('/api/v1/news', function () {
    try {
        $news = \App\Models\News::published()
            ->orderBy('published_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'slug' => $item->slug,
                    'title' => $item->title,
                    'category' => $item->category,
                    'excerpt' => $item->excerpt,
                    'content' => $item->content,
                    'image_path' => $item->image_path,
                    'image' => $item->image_path ? asset('storage/' . $item->image_path) : null,
                    'published_at' => $item->published_at,
                    'created_at' => $item->created_at,
                    'date' => $item->published_at?->format('d M Y') ?? $item->created_at->format('d M Y'),
                ];
            });
        
        return response()->json(['success' => true, 'data' => $news]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
});

Route::get('/api/v1/news/{slug}', function ($slug) {
    try {
        $news = \App\Models\News::published()->where('slug', $slug)->firstOrFail();
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $news->id, 'slug' => $news->slug, 'title' => $news->title,
                'category' => $news->category, 'excerpt' => $news->excerpt,
                'content' => $news->content, 'image_path' => $news->image_path,
                'image' => $news->image_path ? asset('storage/' . $news->image_path) : null,
                'published_at' => $news->published_at,
                'date' => $news->published_at?->format('d M Y') ?? $news->created_at->format('d M Y'),
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Berita tidak ditemukan'], 404);
    }
});

// ================= API: STRUKTUR (BARU) =================
Route::get('/api/v1/struktur', function () {
    try {
        // Pengurus Inti (yang tidak punya pokja_id)
        $pengurusInti = \App\Models\StrukturMember::whereNull('pokja_id')
            ->active()->orderBy('sort_order')->get()
            ->map(fn($m) => [
                'id' => $m->id,
                'name' => $m->name,
                'position' => $m->position,
                'photo' => $m->photo_path ? asset('storage/' . $m->photo_path) : null,
                'description' => $m->description,
            ]);
        
        // Pokja beserta anggotanya
        $pokja = \App\Models\Pokja::active()->with(['members' => fn($q) => $q->active()])
            ->orderBy('sort_order')->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'description' => $p->description,
                'members' => $p->members->map(fn($m) => [
                    'id' => $m->id,
                    'name' => $m->name,
                    'position' => $m->position,
                    'photo' => $m->photo_path ? asset('storage/' . $m->photo_path) : null,
                    'description' => $m->description,
                ]),
            ]);
        
        return response()->json([
            'success' => true,
            'data' => ['pengurus_inti' => $pengurusInti, 'pokja' => $pokja]
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
});

// ================= API: APPLICATIONS =================
Route::get('/api/v1/applications', function () {
    try {
        // Aplikasi Aktif
        $activeApps = \App\Models\Application::aplikasi()
            ->active()
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'short_name', 'description', 'url', 'icon', 'category', 'status', 'features', 'sort_order', 'is_active']);
        
        // Aplikasi Dalam Pengembangan
        $developmentApps = \App\Models\Application::aplikasi()
            ->where('status', 'development')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'short_name', 'description', 'url', 'icon', 'category', 'status', 'features', 'sort_order', 'is_active']);
        
        // Aplikasi Maintenance
        $maintenanceApps = \App\Models\Application::aplikasi()
            ->where('status', 'maintenance')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'short_name', 'description', 'url', 'icon', 'category', 'status', 'features', 'sort_order', 'is_active']);
        
        return response()->json([
            'success' => true,
            'data' => [
                'active' => $activeApps,
                'development' => $developmentApps,
                'maintenance' => $maintenanceApps,
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
});

// ================= API: KECAMATAN (From wilayah.id) =================
Route::get('/api/v1/kecamatans', function (App\Services\WilayahIndonesiaService $service) {
    try {
        // Auto-sync jika database masih kosong
        if (\App\Models\Kecamatan::count() === 0) {
            $service->syncKecamatansToba();
        }
        
        $kecamatans = \App\Models\Kecamatan::select('id', 'name', 'kode_wilayah')
            ->orderBy('name')
            ->get()
            ->map(fn($k) => [
                'id' => $k->id,
                'name' => $k->name,
                'code' => $k->kode_wilayah  // Konsisten dengan format API
            ]);
        
        return response()->json([
            'success' => true,
            'data' => $kecamatans
        ]);
    } catch (\Exception $e) {
        \Log::error('API Kecamatan Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Gagal memuat data kecamatan'
        ], 500);
    }
});

// ================= API: DESA =================
Route::get('/api/v1/desas', function () {
    try {
        $kecamatans = \App\Models\Kecamatan::with(['activeDesas' => function($q) {
            $q->select('id', 'kecamatan_id', 'name', 'kode_wilayah', 'description', 'image', 'population', 'households', 'sort_order', 'is_active')
              ->orderBy('sort_order');
        }])
        ->orderBy('name')
        ->get()
        ->map(function($kec) {
            return [
                'id' => $kec->id,
                'name' => $kec->name,
                'desas' => $kec->activeDesas->map(function($desa) {
                    return [
                        'id' => $desa->id,
                        'name' => $desa->name,
                        'kode_wilayah' => $desa->kode_wilayah,
                        'description' => $desa->description,
                        'image' => $desa->image ? asset('storage/' . $desa->image) : null,
                        'population' => $desa->population,
                        'households' => $desa->households,
                        'sort_order' => $desa->sort_order,
                        'is_active' => $desa->is_active,
                    ];
                })
            ];
        });
        
        return response()->json(['success' => true, 'data' => $kecamatans]);
    } catch (\Exception $e) {
        \Log::error('API Desa Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Gagal memuat data desa: ' . $e->getMessage()
        ], 500);
    }
});

// ================= API: PROXY WILAYAH.ID =================
Route::get('/api/v1/wilayah/proxy/desa/{kecamatanCode}', function ($kecamatanCode) {
    try {
        // Fetch dari API wilayah.id via server (bukan browser)
        $response = Http::timeout(30)->get("https://wilayah.id/api/villages/{$kecamatanCode}.json");
        
        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari API wilayah.id'
            ], $response->status());
        }
        
        return response()->json([
            'success' => true,
            'data' => $response->json()['data'] ?? []
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Proxy Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage()
        ], 500);
    }
});

// ================= API: SK & DOKUMEN =================
Route::get('/api/v1/dokumens', function () {
    try {
        \Log::info('API /api/v1/dokumens accessed');
        
        $dokumens = \App\Models\Dokumen::published()
            ->orderBy('document_date', 'desc')
            ->orderBy('sort_order')
            ->get()
            ->map(function($doc) {
                return [
                    'id' => $doc->id,
                    'name' => $doc->name,
                    'file_name' => $doc->file_name,
                    'file_size' => $doc->file_size,
                    'file_url' => $doc->file_url,
                    'file_type' => $doc->file_type,
                    'document_date' => $doc->document_date?->format('Y-m-d'),
                    'formatted_date' => $doc->document_date?->format('d M Y'),
                    'status' => $doc->status, // Penting untuk filter client-side
                ];
            });
        
        \Log::info('API returning ' . count($dokumens) . ' documents');
        
        return response()->json(['success' => true, 'data' => $dokumens]);
    } catch (\Exception $e) {
        \Log::error('API Dokumen Error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
});

// API Template untuk Landing Page
Route::get('/api/v1/templates', function () {
    try {
        \Log::info('API /api/v1/templates accessed');
        
        $templates = \App\Models\Template::published()
            ->orderBy('upload_date', 'desc')
            ->orderBy('sort_order')
            ->get()
            ->map(function($t) {
                return [
                    'id' => $t->id,
                    'name' => $t->name,
                    'file_name' => $t->file_name,
                    'file_size' => $t->file_size,
                    'file_url' => $t->file_url,
                    'file_type' => $t->file_type,
                    'upload_date' => $t->upload_date?->format('Y-m-d'),
                    'formatted_date' => $t->upload_date?->format('d M Y'),
                    'status' => $t->status,
                    'description' => $t->description ?? null,
                ];
            });
        
        \Log::info('API returning ' . count($templates) . ' templates');
        
        return response()->json(['success' => true, 'data' => $templates]);
    } catch (\Exception $e) {
        \Log::error('API Templates Error: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return response()->json([
            'success' => false, 
            'message' => $e->getMessage()
        ], 500);
    }
});

// API untuk landing page
Route::get('/api/v1/tentang', function () {
    $tentang = \App\Models\TentangKami::getFirst();
    return response()->json([
        'success' => true,
        'data' => $tentang
    ]);
});

// API untuk landing page
Route::get('/api/v1/hero-slider', function () {
    $sliders = \App\Models\HeroSlider::active()->get()->map(fn($s) => [
        'id' => $s->id,
        'image_url' => $s->image_url,
        'display_duration' => $s->display_duration ?? 5
    ]);
    
    $settings = file_exists(storage_path('app/hero_slider_settings.json')) 
        ? json_decode(file_get_contents(storage_path('app/hero_slider_settings.json')), true)
        : ['auto_play' => true, 'transition_duration' => 500, 'show_arrows' => false, 'show_dots' => true];
    
    return response()->json(['success' => true, 'data' => $sliders, 'settings' => $settings]);
});

// ================= END API ROUTES =================


// ================= LANDING PAGE =================
Route::get('/', function () {
    return view('modules.landing.home');
})->name('landing.home');

Route::get('/berita', function () {
    return view('modules.landing.home');
})->name('landing.berita');


// ================= ADMIN PANEL =================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Hero Sliders (Beranda)
    Route::prefix('hero-sliders')->name('hero-sliders.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\HeroSliderController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Admin\HeroSliderController::class, 'store'])->name('store');
        Route::put('/{heroSlider}', [App\Http\Controllers\Admin\HeroSliderController::class, 'update'])->name('update');
        Route::delete('/{heroSlider}', [App\Http\Controllers\Admin\HeroSliderController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [App\Http\Controllers\Admin\HeroSliderController::class, 'updateOrder'])->name('reorder');
        Route::post('/settings', [App\Http\Controllers\Admin\HeroSliderController::class, 'updateSettings'])->name('settings');
    });

    // STRUKTUR
    Route::resource('struktur', App\Http\Controllers\Admin\StrukturController::class);

    // Aplikasi
    Route::resource('aplikasi', App\Http\Controllers\Admin\ApplicationController::class);

    // Berita (Resource CRUD)
    Route::resource('berita', App\Http\Controllers\Admin\BeritaController::class);

    // Desa
    Route::prefix('desa')->name('desa.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DesaController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\DesaController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\DesaController::class, 'store'])->name('store');
        Route::get('/{desa}/edit', [App\Http\Controllers\Admin\DesaController::class, 'edit'])->name('edit');
        Route::put('/{desa}', [App\Http\Controllers\Admin\DesaController::class, 'update'])->name('update');
        Route::delete('/{desa}', [App\Http\Controllers\Admin\DesaController::class, 'destroy'])->name('destroy');
        
        Route::post('/kecamatan', [App\Http\Controllers\Admin\DesaController::class, 'storeKecamatan'])->name('kecamatan.store');
        Route::put('/kecamatan/{kecamatan}', [App\Http\Controllers\Admin\DesaController::class, 'updateKecamatan'])->name('kecamatan.update');
        Route::delete('/kecamatan/{kecamatan}', [App\Http\Controllers\Admin\DesaController::class, 'destroyKecamatan'])->name('kecamatan.destroy');
    });

    // SK & Dokumen
    Route::resource('sk', App\Http\Controllers\Admin\DokumenController::class)->parameters([
        'sk' => 'dokumen'
    ]);

    // Template
    Route::resource('template', App\Http\Controllers\Admin\TemplateController::class);

    // ✅ TENTANG KAMI - Perbaiki: HAPUS prefix /admin/ karena sudah di dalam group
    Route::get('/tentang', [App\Http\Controllers\Admin\TentangKamiController::class, 'index'])
        ->name('tentang.index');
    Route::post('/tentang/update', [App\Http\Controllers\Admin\TentangKamiController::class, 'update'])
        ->name('tentang.update');

}); // ← ✅ PENTING: Tutup admin group di sini!

// ================= AUTH ROUTES (Wajib di paling bawah) =================
require __DIR__.'/auth.php';