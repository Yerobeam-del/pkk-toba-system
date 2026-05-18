@extends('admin.layouts.app')

@section('title', 'Edit Berita')
@section('page-title', 'Edit Berita')

@section('content')
<div class="card">
    {{-- Gunakan $berita --}}
    <form action="{{ route('admin.berita.update', $berita) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label>Judul Berita *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $berita->title) }}" required>
        </div>
        
        <div class="form-grid">
            <div class="form-group">
                <label>Kategori *</label>
                <input type="text" name="category" class="form-control" value="{{ old('category', $berita->category) }}" required>
            </div>
            <div class="form-group">
                <label>Tanggal Publikasi</label>
                <input type="date" 
                    name="published_at" 
                    class="form-control" 
                    value="{{ old('published_at', $berita->published_at ? $berita->published_at->format('Y-m-d') : '') }}">
            </div>
        </div>
        
        <div class="form-group">
            <label>Ringkasan (Excerpt) *</label>
            <textarea name="excerpt" class="form-control" rows="3" required>{{ old('excerpt', $berita->excerpt) }}</textarea>
        </div>
        
        <div class="form-group full">
            <label>Konten Lengkap *</label>
            <textarea name="content" class="form-control" rows="8" required>{{ old('content', $berita->content) }}</textarea>
        </div>
        
        <div class="form-group">
            <label>Gambar</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            @if($berita->image_path)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $berita->image_path) }}" alt="{{ $berita->title }}" class="h-24 rounded object-cover">
                </div>
            @endif
        </div>
        
        <div class="form-group">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $berita->is_published) ? 'checked' : '' }}>
                <span class="ml-2">Publikasikan</span>
            </label>
        </div>
        
        <div style="margin-top:1.5rem">
            <a href="{{ route('admin.berita.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>
@endsection