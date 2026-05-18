@extends('admin.layouts.app')
@section('title', 'Edit Template')
@section('page-title', 'Edit Template PKK')

@section('content')
<div class="card">
    <form action="{{ route('admin.template.update', $template) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        
        <div class="form-group">
            <label>Nama Template *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $template->name) }}" required>
        </div>

        <div class="form-group">
            <label>File Template</label>
            <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt">
            <small style="color:var(--text-muted);display:block;margin-top:0.5rem">
                Kosongkan jika tidak ingin mengubah file • Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, TXT • Max: 10 MB
            </small>
            
            {{-- Current file info --}}
            <div style="margin-top:0.75rem;padding:0.75rem 1rem;background:#f8fafc;border-radius:8px;border:1px solid #e2e8f0">
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.5rem">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;color:var(--primary)">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    <span style="font-weight:500">{{ $template->file_name }}</span>
                    <span style="margin-left:auto;font-size:0.85rem;color:var(--text-muted)">{{ $template->file_size }}</span>
                </div>
                <a href="{{ $template->file_url }}" target="_blank" style="color:var(--primary);font-size:0.85rem;text-decoration:none">🔗 Lihat file saat ini</a>
            </div>
            
            {{-- Preview new file --}}
            <div id="filePreview" style="margin-top:0.75rem;display:none">
                <div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1rem;background:#f0fdf4;border-radius:8px;border:1px solid #bbf7d0">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;color:#16a34a">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    <span id="fileName" style="font-weight:500"></span>
                    <span id="fileSize" style="margin-left:auto;font-size:0.85rem;color:var(--text-muted)"></span>
                </div>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label>Tanggal Upload *</label>
                <input type="date" name="upload_date" class="form-control" value="{{ old('upload_date', $template->upload_date?->format('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label>Urutan Tampil</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $template->sort_order) }}" min="0">
            </div>
        </div>

        <div class="form-group">
            <label>Status *</label>
            <div style="display:flex;gap:1.5rem;margin-top:0.5rem">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                    <input type="radio" name="status" value="published" {{ old('status', $template->status)==='published'?'checked':'' }} style="width:18px;height:18px">
                    <span style="font-weight:500">🟢 Published</span>
                </label>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                    <input type="radio" name="status" value="draft" {{ old('status', $template->status)==='draft'?'checked':'' }} style="width:18px;height:18px">
                    <span style="font-weight:500">🟡 Draft</span>
                </label>
            </div>
        </div>

        <div class="modal-footer" style="padding:0;margin-top:2rem">
            <a href="{{ route('admin.template.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">💾 Update Template</button>
        </div>
    </form>
</div>

@if($errors->any())
<div style="margin-top:1rem;padding:1rem;background:#fff5f5;border-left:4px solid var(--danger);border-radius:8px;color:#c53030">
    <strong style="display:block;margin-bottom:0.5rem">Ada kesalahan input:</strong>
    <ul style="padding-left:1.25rem;margin:0">
        @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
    </ul>
</div>
@endif

<script>
// Preview new file name & size
document.querySelector('input[name="file"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        document.getElementById('fileName').textContent = file.name;
        
        const units = ['B', 'KB', 'MB', 'GB'];
        let size = file.size;
        let unitIndex = 0;
        while (size >= 1024 && unitIndex < units.length - 1) {
            size /= 1024;
            unitIndex++;
        }
        document.getElementById('fileSize').textContent = size.toFixed(2) + ' ' + units[unitIndex];
        
        document.getElementById('filePreview').style.display = 'block';
    }
});
</script>
@endsection