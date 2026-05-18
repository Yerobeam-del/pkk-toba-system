<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::orderBy('category')->orderBy('sort_order')->get();
        return view('admin.aplikasi.index', compact('applications'));
    }

    public function create()
    {
        return view('admin.aplikasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'short_name' => 'required|string|max:20|unique:applications',
            'description' => 'nullable|string|max:500',
            'url' => 'nullable|url|max:255',
            'category' => 'required|in:layanan,aplikasi',
            'status' => 'required|in:active,maintenance,development',
            'features' => 'nullable|array|min:2|max:5',
            'features.*' => 'required|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'icon' => 'nullable|image|max:2048',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        // ✅ KODE UPLOAD GAMBAR (STORE)
        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('applications', 'public');
        }

        Application::create($validated);
        
        return redirect()->route('admin.aplikasi.index')->with('success', 'Aplikasi berhasil ditambahkan.');
    }

    public function edit(Application $aplikasi)
    {
        return view('admin.aplikasi.edit', compact('aplikasi'));
    }

    public function update(Request $request, Application $aplikasi)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'short_name' => 'required|string|max:20|unique:applications,short_name,' . $aplikasi->id,
            'description' => 'nullable|string|max:500',
            'url' => 'nullable|url|max:255',
            'category' => 'required|in:layanan,aplikasi',
            'status' => 'required|in:active,maintenance,development',
            'features' => 'nullable|array|min:2|max:5',
            'features.*' => 'required|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'icon' => 'nullable|image|max:2048',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        // ✅ KODE UPLOAD GAMBAR (UPDATE)
        if ($request->hasFile('icon')) {
            // Hapus icon lama jika ada
            if ($aplikasi->icon) {
                Storage::disk('public')->delete($aplikasi->icon);
            }
            // Upload icon baru
            $validated['icon'] = $request->file('icon')->store('applications', 'public');
        }

        $aplikasi->update($validated);
        
        return redirect()->route('admin.aplikasi.index')->with('success', 'Aplikasi berhasil diperbarui.');
    }

    public function destroy(Application $aplikasi)
    {
        // Hapus icon jika ada
        if ($aplikasi->icon) {
            Storage::disk('public')->delete($aplikasi->icon);
        }
        
        $aplikasi->delete();
        
        return redirect()->route('admin.aplikasi.index')->with('success', 'Aplikasi berhasil dihapus.');
    }
}