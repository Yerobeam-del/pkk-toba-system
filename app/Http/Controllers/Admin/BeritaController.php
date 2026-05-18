<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index()
    {
        $berita = News::latest()->paginate(10);
        return view('admin.berita.index', compact('berita'));
    }

    public function create()
    {
        return view('admin.berita.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->boolean('is_published');

        if ($validated['is_published'] && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('news', 'public');
        }

        News::create($validated);
        
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(News $beritum)
    {
        return view('admin.berita.edit', ['berita' => $beritum]);
    }

    public function update(Request $request, News $beritum)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'published_at' => 'nullable|date',
            'image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->boolean('is_published');

        if ($validated['is_published'] && empty($validated['published_at'])) {
        $validated['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            if ($beritum->image_path) {
                Storage::disk('public')->delete($beritum->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('news', 'public');
        }

        $beritum->update($validated);
        
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $beritum)
    {
        if ($beritum->image_path) {
            Storage::disk('public')->delete($beritum->image_path);
        }
        $beritum->delete();
        
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }
}