@extends('admin.layouts.app')

@section('title', 'Tambah Berita')
@section('page-title', 'Tambah Berita Baru')

@section('content')
<div class="card">
    <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="form-group">
            <label>Judul Berita *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
        </div>
        
        <div class="form-grid">
            <div class="form-group">
                <label>Kategori *</label>
                <input type="text" name="category" class="form-control" value="{{ old('category') }}" required>
            </div>
            <div class="form-group">
                <label>Tanggal Publikasi</label>
                <input type="date" name="published_at" class="form-control" value="{{ old('published_at', date('Y-m-d')) }}">
            </div>
        </div>
        
        <div class="form-group">
            <label>Ringkasan (Excerpt) *</label>
            <textarea name="excerpt" class="form-control" rows="3" required>{{ old('excerpt') }}</textarea>
        </div>
        
        <div class="form-group full">
            <label>Konten Lengkap *</label>
            <textarea name="content" class="form-control" rows="8" required>{{ old('content') }}</textarea>
        </div>
        
        <div class="form-group">
            <label>Gambar</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <small class="text-muted">Format: JPG, PNG. Maksimal 2MB.</small>
        </div>
        
        <div class="form-group">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} class="rounded border-gray-300 text-[#0f6b63]">
                <span class="ml-2">Publikasikan sekarang</span>
            </label>
        </div>
        
        <div class="modal-footer" style="padding:0;margin-top:1.5rem">
            <a href="{{ route('admin.berita.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
    
    @if($errors->any())
    <div class="mt-4 p-3 bg-red-50 text-red-700 rounded-lg">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
        </ul>
    </div>
    @endif
</div>
@endsection