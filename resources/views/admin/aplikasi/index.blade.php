@extends('admin.layouts.app')
@section('title', 'Manajemen Aplikasi')
@section('page-title', 'Manajemen Aplikasi')

@section('content')
<div style="margin-bottom:2rem">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
        <div>
            <h1 style="font-size:1.75rem;font-weight:700;color:var(--primary);margin-bottom:0.25rem">Aplikasi & Sistem</h1>
            <p style="color:var(--text-muted);margin:0">Kelola aplikasi dan sistem informasi PKK Kabupaten Toba</p>
        </div>
        <a href="{{ route('admin.aplikasi.create') }}" class="btn btn-primary">+ Tambah Aplikasi</a>
    </div>

    @if(session('success'))
    <div style="background:#f0fff4;border-left:4px solid var(--success);padding:1rem;margin-bottom:1.5rem;border-radius:8px;color:#276749">
        {{ session('success') }}
    </div>
    @endif

    {{-- Stats Cards --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:2rem">
        <div style="background:linear-gradient(135deg,#3182ce,#2b6cb0);color:#fff;padding:1.25rem;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08)">
            <div style="display:flex;justify-content:space-between;align-items:center">
                <div>
                    <p style="font-size:0.85rem;opacity:0.9;margin-bottom:0.25rem">Total Aplikasi</p>
                    <p style="font-size:2rem;font-weight:700;margin:0">{{ $applications->count() }}</p>
                </div>
                <div style="font-size:2.5rem;opacity:0.3">📱</div>
            </div>
        </div>
        <div style="background:linear-gradient(135deg,#38a169,#2f855a);color:#fff;padding:1.25rem;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08)">
            <div style="display:flex;justify-content:space-between;align-items:center">
                <div>
                    <p style="font-size:0.85rem;opacity:0.9;margin-bottom:0.25rem">Aplikasi Aktif</p>
                    <p style="font-size:2rem;font-weight:700;margin:0">{{ $applications->where('status','active')->where('is_active',true)->count() }}</p>
                </div>
                <div style="font-size:2.5rem;opacity:0.3">✅</div>
            </div>
        </div>
        <div style="background:linear-gradient(135deg,#dd6b20,#c05621);color:#fff;padding:1.25rem;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08)">
            <div style="display:flex;justify-content:space-between;align-items:center">
                <div>
                    <p style="font-size:0.85rem;opacity:0.9;margin-bottom:0.25rem">Maintenance</p>
                    <p style="font-size:2rem;font-weight:700;margin:0">{{ $applications->where('status','maintenance')->count() }}</p>
                </div>
                <div style="font-size:2.5rem;opacity:0.3">🔧</div>
            </div>
        </div>
        <div style="background:linear-gradient(135deg,#805ad5,#6b46c1);color:#fff;padding:1.25rem;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.08)">
            <div style="display:flex;justify-content:space-between;align-items:center">
                <div>
                    <p style="font-size:0.85rem;opacity:0.9;margin-bottom:0.25rem">Dalam Pengembangan</p>
                    <p style="font-size:2rem;font-weight:700;margin:0">{{ $applications->where('status','development')->count() }}</p>
                </div>
                <div style="font-size:2.5rem;opacity:0.3">🚧</div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="tab-nav-struktur">
        <button class="tab-btn active" onclick="switchTab('all', this)">Semua Aplikasi</button>
        <button class="tab-btn" onclick="switchTab('active', this)">Aktif ({{ $applications->where('status','active')->where('is_active',true)->count() }})</button>
        <button class="tab-btn" onclick="switchTab('maintenance', this)">Maintenance ({{ $applications->where('status','maintenance')->count() }})</button>
        <button class="tab-btn" onclick="switchTab('development', this)">Pengembangan ({{ $applications->where('status','development')->count() }})</button>
    </div>

    <div class="card">
        {{-- All Applications --}}
        <div id="tab-all" class="tab-content-struktur active">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Nama Aplikasi</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>URL</th>
                            <th>Urutan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $app)
                        <tr>
                            <td>
                                @if($app->icon)
                                <img src="{{ asset('storage/'.$app->icon) }}" style="width:40px;height:40px;border-radius:8px;object-fit:cover">
                                @else
                                <div style="width:40px;height:40px;border-radius:8px;background:linear-gradient(135deg,var(--primary),var(--primary-light));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.8rem">
                                    {{ substr($app->short_name, 0, 2) }}
                                </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight:600">{{ $app->name }}</div>
                                <small style="color:var(--text-muted)">{{ $app->short_name }}</small>
                            </td>
                            <td>
                                <span class="tag {{ $app->category == 'aplikasi' ? 'tag-role' : 'tag-active' }}">
                                    {{ ucfirst($app->category) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusConfig = [
                                        'active' => ['bg'=>'#f0fff4','color'=>'#276749','icon'=>'✅','label'=>'Aktif'],
                                        'maintenance' => ['bg'=>'#fffaf0','color'=>'#dd6b20','icon'=>'🔧','label'=>'Maintenance'],
                                        'development' => ['bg'=>'#f0f4ff','color'=>'#434190','icon'=>'🚧','label'=>'Pengembangan']
                                    ];
                                    $config = $statusConfig[$app->status] ?? $statusConfig['active'];
                                @endphp
                                <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;background:{{ $config['bg'] }};color:{{ $config['color'] }}">
                                    {{ $config['icon'] }} {{ $config['label'] }}
                                </span>
                            </td>
                            <td>
                                @if($app->url && $app->url !== '#')
                                <a href="{{ $app->url }}" target="_blank" style="color:var(--primary);text-decoration:underline;font-size:0.85rem">
                                    {{ Str::limit($app->url, 25) }}
                                </a>
                                @else
                                <span style="color:var(--text-muted);font-size:0.85rem">-</span>
                                @endif
                            </td>
                            <td>{{ $app->sort_order }}</td>
                            <td class="actions">
                                <a href="{{ route('admin.aplikasi.edit', $app) }}" class="btn-edit" title="Edit">✏️</a>
                                <form action="{{ route('admin.aplikasi.destroy', $app) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus aplikasi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-del" title="Hapus">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:2rem;color:var(--text-muted)">
                                Belum ada aplikasi. Silakan tambah aplikasi pertama Anda.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Active Apps --}}
        <div id="tab-active" class="tab-content-struktur">
            <div class="table-container">
                <table>
                    <thead>
                        <tr><th>Logo</th><th>Nama</th><th>URL</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($applications->where('status','active')->where('is_active',true) as $app)
                        <tr>
                            <td>
                                @if($app->icon)
                                <img src="{{ asset('storage/'.$app->icon) }}" style="width:40px;height:40px;border-radius:8px;object-fit:cover">
                                @else
                                <div style="width:40px;height:40px;border-radius:8px;background:linear-gradient(135deg,#38a169,#2f855a);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700">
                                    {{ substr($app->short_name, 0, 2) }}
                                </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight:600">{{ $app->name }}</div>
                                <small style="color:var(--text-muted)">{{ $app->short_name }}</small>
                            </td>
                            <td>
                                @if($app->url)
                                <a href="{{ $app->url }}" target="_blank" style="color:var(--primary);text-decoration:underline">{{ Str::limit($app->url, 30) }}</a>
                                @else
                                <span style="color:var(--text-muted)">-</span>
                                @endif
                            </td>
                            <td class="actions">
                                <a href="{{ route('admin.aplikasi.edit', $app) }}" class="btn-edit">✏️</a>
                                <form action="{{ route('admin.aplikasi.destroy', $app) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-del">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;padding:2rem;color:var(--text-muted)">Belum ada aplikasi aktif</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Maintenance Apps --}}
        <div id="tab-maintenance" class="tab-content-struktur">
            <div class="table-container">
                <table>
                    <thead>
                        <tr><th>Logo</th><th>Nama</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($applications->where('status','maintenance') as $app)
                        <tr>
                            <td>
                                @if($app->icon)
                                <img src="{{ asset('storage/'.$app->icon) }}" style="width:40px;height:40px;border-radius:8px;object-fit:cover">
                                @else
                                <div style="width:40px;height:40px;border-radius:8px;background:linear-gradient(135deg,#dd6b20,#c05621);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700">
                                    {{ substr($app->short_name, 0, 2) }}
                                </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight:600">{{ $app->name }}</div>
                                <small style="color:var(--text-muted)">{{ $app->short_name }}</small>
                            </td>
                            <td>
                                <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;background:#fffaf0;color:#dd6b20">
                                    🔧 Dalam Maintenance
                                </span>
                            </td>
                            <td class="actions">
                                <a href="{{ route('admin.aplikasi.edit', $app) }}" class="btn-edit">✏️</a>
                                <form action="{{ route('admin.aplikasi.destroy', $app) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-del">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;padding:2rem;color:var(--text-muted)">Tidak ada aplikasi dalam maintenance</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Development Apps --}}
        <div id="tab-development" class="tab-content-struktur">
            <div class="table-container">
                <table>
                    <thead>
                        <tr><th>Logo</th><th>Nama</th><th>Status</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($applications->where('status','development') as $app)
                        <tr>
                            <td>
                                @if($app->icon)
                                    <img src="{{ asset('storage/'.$app->icon) }}" 
                                        style="width:40px;height:40px;border-radius:8px;object-fit:cover;box-shadow:0 2px 4px rgba(0,0,0,0.1)">
                                @else
                                    {{-- ✅ Placeholder yang lebih bagus --}}
                                    <div style="width:40px;height:40px;border-radius:8px;
                                                background:linear-gradient(135deg, #2b6cb0, #3182ce);
                                                display:flex;align-items:center;justify-content:center;
                                                color:#fff;font-weight:700;font-size:1rem;
                                                box-shadow:0 2px 4px rgba(0,0,0,0.1);
                                                text-transform:uppercase;">
                                        {{ substr($app->short_name, 0, 1) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight:600">{{ $app->name }}</div>
                                <small style="color:var(--text-muted)">{{ $app->short_name }}</small>
                            </td>
                            <td>
                                <span style="display:inline-flex;align-items:center;gap:4px;padding:4px 10px;border-radius:20px;font-size:0.75rem;font-weight:600;background:#f0f4ff;color:#434190">
                                    🚧 Dalam Pengembangan
                                </span>
                            </td>
                            <td class="actions">
                                <a href="{{ route('admin.aplikasi.edit', $app) }}" class="btn-edit">✏️</a>
                                <form action="{{ route('admin.aplikasi.destroy', $app) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-del">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;padding:2rem;color:var(--text-muted)">Belum ada aplikasi dalam pengembangan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function switchTab(tabId, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content-struktur').forEach(c => c.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('tab-' + tabId).classList.add('active');
}
</script>
@endsection