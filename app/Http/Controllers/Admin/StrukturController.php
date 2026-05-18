<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pokja;
use App\Models\StrukturMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StrukturController extends Controller
{
    // Auto-sort mapping (urutan tampil di tree)
    protected $sortMap = [
        'Ketua Pembina' => 1, 'Ketua TP PKK' => 2, 'Staf Ahli' => 3,
        'Sekretaris' => 4, 'Bendahara' => 5,
        'Ketua I' => 6, 'Ketua II' => 7, 'Ketua III' => 8, 'Ketua IV' => 9,
        'Ketua' => 10, 'Wakil Ketua' => 11, 'Sekretaris Pokja' => 12, 'Anggota' => 13
    ];

    public function index()
    {
        $pengurusInti = StrukturMember::whereNull('pokja_id')
            ->orderByRaw("
                CASE position 
                    WHEN 'Ketua Pembina' THEN 1
                    WHEN 'Ketua TP PKK' THEN 2
                    WHEN 'Staf Ahli' THEN 3
                    WHEN 'Sekretaris' THEN 4
                    WHEN 'Bendahara' THEN 5
                    WHEN 'Ketua I' THEN 6
                    WHEN 'Ketua II' THEN 7
                    WHEN 'Ketua III' THEN 8
                    WHEN 'Ketua IV' THEN 9
                    ELSE 99 
                END
            ")
            ->get();
            
        $pokjaList = Pokja::withCount('members')->orderBy('id')->get();
        
        return view('admin.struktur.index', compact('pengurusInti', 'pokjaList'));
    }

    public function create()
    {
        $pokjaList = Pokja::orderBy('id')->get();
        return view('admin.struktur.create', compact('pokjaList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group' => 'required|in:pengurus,pokja1,pokja2,pokja3,pokja4',
            'position' => 'required|string|max:50',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Map group to pokja_id
        $validated['pokja_id'] = $validated['group'] === 'pengurus' ? null : (int)str_replace('pokja', '', $validated['group']);
        
        // Auto sort_order berdasarkan jabatan
        $validated['sort_order'] = $this->sortMap[$validated['position']] ?? 99;
        
        // Normalisasi nama jabatan agar konsisten di DB
        if ($validated['group'] !== 'pengurus' && $validated['position'] === 'Sekretaris') {
            $validated['position'] = 'Sekretaris Pokja'; // Bedakan dengan Sekretaris Inti
        }

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('struktur', 'public');
        }

        StrukturMember::create($validated);
        return redirect()->route('admin.struktur.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(StrukturMember $struktur)
    {
        $pokjaList = Pokja::orderBy('id')->get();
        return view('admin.struktur.edit', compact('struktur', 'pokjaList'));
    }

    public function update(Request $request, StrukturMember $struktur)
    {
        $validated = $request->validate([
            'group' => 'required|in:pengurus,pokja1,pokja2,pokja3,pokja4',
            'position' => 'required|string|max:50',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'photo' => 'nullable|image|max:2048',
        ]);

        $validated['pokja_id'] = $validated['group'] === 'pengurus' ? null : (int)str_replace('pokja', '', $validated['group']);
        $validated['sort_order'] = $this->sortMap[$validated['position']] ?? 99;
        
        if ($validated['group'] !== 'pengurus' && $validated['position'] === 'Sekretaris') {
            $validated['position'] = 'Sekretaris Pokja';
        }

        if ($request->hasFile('photo')) {
            if ($struktur->photo_path) Storage::disk('public')->delete($struktur->photo_path);
            $validated['photo_path'] = $request->file('photo')->store('struktur', 'public');
        }

        $struktur->update($validated);
        return redirect()->route('admin.struktur.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(StrukturMember $struktur)
    {
        if ($struktur->photo_path) Storage::disk('public')->delete($struktur->photo_path);
        $struktur->delete();
        return redirect()->route('admin.struktur.index')->with('success', 'Data berhasil dihapus.');
    }
}