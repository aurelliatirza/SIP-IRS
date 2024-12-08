<?php

namespace App\Http\Controllers;
use App\Models\Matakuliah;
use Illuminate\Support\Facades\DB;
use App\Models\JadwalKuliah;
use App\Models\Dosen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class KaprodiControler extends Controller
{
    public function DashboardKaprodi()
    {
        $kaprodi = DB::table('dosen')
                        ->join('users', 'dosen.id_user', '=', 'users.id')
                        ->join('program_studi', 'dosen.prodi_id', '=', 'program_studi.id_prodi')
                        ->crossJoin('periode_akademik')
                        ->where('users.roles1', '=', 'dosen') // Pastikan ini sesuai dengan peran yang tepat
                        ->where('users.roles2', '=', 'kaprodi') // Pastikan ini juga sesuai
                        ->where('dosen.id_user', '=', Auth::id())
                        ->orderBy('periode_akademik.created_at', 'desc') // Mengurutkan berdasarkan timestamp terbaru
                        ->select(
                            'dosen.nip',
                            'dosen.nama as dosen_nama',
                            'program_studi.nama as prodi_nama',
                            'dosen.prodi_id',
                            'users.username',
                            'periode_akademik.nama_periode'
                        )
                        ->first();
        return view('dashboardKaprodi', compact('kaprodi'));
    }

    public function JadwalKuliah()
    {
        $kaprodi = DB::table('dosen')
            ->join('users', 'dosen.id_user', '=', 'users.id')
            ->join('program_studi', 'dosen.prodi_id', '=', 'program_studi.id_prodi')
            ->crossJoin('periode_akademik')
            ->where('users.roles1', '=', 'dosen') // Pastikan ini sesuai dengan peran yang tepat
            ->where('users.roles2', '=', 'kaprodi') // Pastikan ini juga sesuai
            ->where('dosen.id_user', '=', Auth::id())
            ->orderBy('periode_akademik.created_at', 'desc') // Mengurutkan berdasarkan timestamp terbaru
            ->select(
                'dosen.nip',
                'dosen.nama as dosen_nama',
                'program_studi.nama as prodi_nama',
                'dosen.prodi_id',
                'users.username',
                'periode_akademik.nama_periode'
            )
            ->first();
    
        // Ambil semua mata kuliah
        $namaMK = Matakuliah::all();
    
        // Ambil data ruangan yang sesuai dengan prodi kaprodi
        $ruangan = DB::table('alokasi_ruangan')
        ->join('ruangan', 'alokasi_ruangan.id_ruang', '=', 'ruangan.id_ruang')
        ->where('alokasi_ruangan.id_prodi', $kaprodi->prodi_id)
        ->select(
            'alokasi_ruangan.id_ruang',
            'ruangan.nama as nama_ruang',
        )
        ->get();
    
        // Ambil jadwal kuliah
        $jadwal = DB::table('jadwal_kuliah')
        ->join('matakuliah', 'jadwal_kuliah.kode_matkul', '=', 'matakuliah.kode_matkul')
        ->join('ruangan', 'jadwal_kuliah.id_ruang', '=', 'ruangan.id_ruang')
        ->select(
            'jadwal_kuliah.id_jadwal',
            'jadwal_kuliah.kode_matkul',
            'matakuliah.nama_matkul',
            'jadwal_kuliah.kelas',
            'matakuliah.semester',
            'jadwal_kuliah.hari',
            'ruangan.nama as nama_ruang',
            'jadwal_kuliah.jam_mulai',
            'jadwal_kuliah.jam_selesai'
        )
        ->get();

        // Ambil nama dosen
        $dosen = Dosen::where('prodi_id', $kaprodi->prodi_id)->get(); // Ambil dosen berdasarkan prodi_id kaprodi

        // Kirimkan data ke view
        return view('kaprodi_JadwalKuliah', compact('kaprodi', 'namaMK', 'ruangan', 'jadwal', 'dosen'));
    }

    public function StatusMahasiswa()
    {
        $kaprodi = DB::table('dosen')
                        ->join('users', 'dosen.id_user', '=', 'users.id')
                        ->join('program_studi', 'dosen.prodi_id', '=', 'program_studi.id_prodi')
                        ->crossJoin('periode_akademik')
                        ->where('users.roles1', '=', 'dosen') // Pastikan ini sesuai dengan peran yang tepat
                        ->where('users.roles2', '=', 'kaprodi') // Pastikan ini juga sesuai
                        ->where('dosen.id_user', '=', Auth::id())
                        ->orderBy('periode_akademik.created_at', 'desc') // Mengurutkan berdasarkan timestamp terbaru
                        ->select(
                            'dosen.nip',
                            'dosen.nama as dosen_nama',
                            'program_studi.nama as prodi_nama',
                            'dosen.prodi_id',
                            'users.username',
                            'periode_akademik.nama_periode'
                        )
                        ->first();
        return view('kaprodi_StatusMahasiswa', compact('kaprodi'));
    }

    // Fungsi untuk menambah jadwal kuliah
    

    public function setMatkul()
    {
        $kaprodi = DB::table('dosen')
                            ->join('users', 'dosen.id_user', '=', 'users.id')
                            ->join('program_studi', 'dosen.prodi_id', '=', 'program_studi.id_prodi')
                            ->crossJoin('periode_akademik')
                            ->where('users.roles1', '=', 'dosen') // Pastikan ini sesuai dengan peran yang tepat
                            ->where('users.roles2', '=', 'kaprodi') // Pastikan ini juga sesuai
                            ->where('dosen.id_user', '=', Auth::id())
                            ->orderBy('periode_akademik.created_at', 'desc') // Mengurutkan berdasarkan timestamp terbaru
                            ->select(
                                'dosen.nip',
                                'dosen.nama as dosen_nama',
                                'program_studi.nama as prodi_nama',
                                'dosen.prodi_id',
                                'users.username',
                                'periode_akademik.nama_periode'
                            )
                            ->first();
    
        // Fetch mataKuliah data
        $mataKuliah = DB::table('matakuliah')
                        ->select('id_matkul', 'kode_matkul', 'nama_matkul', 'sks', 'semester')
                        ->get();
    
        // Add the 'hasConstraint' to each mataKuliah record
        foreach ($mataKuliah as $data) {
            // Check if there are any constraints on this mataKuliah
            $data->hasConstraint = $this->checkConstraints($data); // Add the constraint logic here
        }
        
        return view('kaprodi_SetMatkul', compact('kaprodi', 'mataKuliah'));
    }

    // Define the checkConstraints function at the class level
    private function checkConstraints($data)
    {
        // Example: Check if there are any constraints related to the 'matakuliah'
        // You might need to adjust this query to check the correct table or condition
        $hasConstraint = DB::table('matakuliah') 
                            ->where('id_matkul', $data->id_matkul) // Adjust field names as per your database
                            ->exists(); // Check if any records exist
    
        return $hasConstraint; // Return true if there are constraints, false otherwise
    }

    public function updateMatakuliah(Request $request)
    {
        // dd($request->all());
        try {
            $request->validate([
                'id_matkul' => 'required|exists:matakuliah,id_matkul',  // Validasi ID
                'kode_matkul' => 'required|string|max:255',  // Validasi kode mata kuliah
                'nama_matkul' => 'required|string|max:255',  // Validasi nama mata kuliah
                'sks' => 'required|integer|min:1',  // Validasi SKS
                'semester' => 'required|integer|min:1',  // Validasi semester
            ]);

            // $matakuliah = Matakuliah::find($request->id_matkul);
            // dd($matakuliah);

            // Update data di tabel 'matakuliah' menggunakan model
            DB::table('matakuliah')->where('id_matkul', $request->id_matkul)
                ->update([
                    'kode_matkul' => $request->kode_matkul,
                    'nama_matkul' => $request->nama_matkul,
                    'sks' => $request->sks,
                    'semester' => $request->semester,
                    'created_at' => now(),  // Update timestamp
                ]);
            
            // Redirect ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('sweetAlert', [
                'title' => 'Berhasil!',
                'text' => 'Matkul berhasil diperbarui.',
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            // Jika terjadi error, redirect dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui mata kuliah.');
        }
    }

    public function deleteMatakuliah(Request $request)
    {
        // dd($request->all());
        try {
            $request->validate([
                'id_matkul' => 'required|exists:matakuliah,id_matkul',  // Validasi ID
            ]);

            // Hapus data di tabel 'matakuliah' menggunakan model
            DB::table('matakuliah')->where('id_matkul', $request->id_matkul)->delete();
            
            // Redirect ke halaman sebelumnya dengan pesan sukses
            return redirect()->back()->with('sweetAlert', [
                'title' => 'Berhasil!',
                'text' => 'Matkul berhasil dihapus.',
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            // Jika terjadi error, redirect dengan pesan error
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus mata kuliah.');
        }
    }

        public function UpdateDeleteMatkul()
    {
        $kaprodi = DB::table('dosen')
                        ->join('users', 'dosen.id_user', '=', 'users.id')
                        ->join('program_studi', 'dosen.prodi_id', '=', 'program_studi.id_prodi')
                        ->crossJoin('periode_akademik')
                        ->where('users.roles1', '=', 'dosen') // Pastikan ini sesuai dengan peran yang tepat
                        ->where('users.roles2', '=', 'kaprodi') // Pastikan ini juga sesuai
                        ->where('dosen.id_user', '=', Auth::id())
                        ->orderBy('periode_akademik.created_at', 'desc') // Mengurutkan berdasarkan timestamp terbaru
                        ->select(
                            'dosen.nip',
                            'dosen.nama as dosen_nama',
                            'program_studi.nama as prodi_nama',
                            'dosen.prodi_id',
                            'users.username',
                            'periode_akademik.nama_periode'
                        )
                        ->first();
        
        $mataKuliah = DB::table('matakuliah')
                        -> select(
                            'id_matkul',
                            'kode_matkul',
                            'nama_matkul',
                            'sks',
                            'semester'
                        )
                        -> get()
                        ->map(function($mk) {
                            // Cek constraint untuk setiap ruangan
                            $hasConstraint = DB::table('jadwal_kuliah')
                                ->where('id_ruang', $mk->id_matkul)
                                ->exists();
                            
                            $mk->hasConstraint = $hasConstraint;
                            return $mk;
                        });

        // dd($mataKuliah);
        return view('kaprodi_UpdateDeleteMatkul', compact('kaprodi', 'mataKuliah'));
    }

    public function indexCreateMatkul()
    {
        $kaprodi = DB::table('matakuliah')
                        ->join('users', 'dosen.id_user', '=', 'users.id')
                        ->join('program_studi', 'dosen.prodi_id', '=', 'program_studi.id_prodi')
                        ->crossJoin('periode_akademik')
                        ->where('users.roles1', '=', 'dosen') // Pastikan ini sesuai dengan peran yang tepat
                        ->where('users.roles2', '=', 'kaprodi') // Pastikan ini juga sesuai
                        ->where('dosen.id_user', '=', Auth::id())
                        ->orderBy('periode_akademik.created_at', 'desc') // Mengurutkan berdasarkan timestamp terbaru
                        ->select(
                            'dosen.nip',
                            'dosen.nama as dosen_nama',
                            'program_studi.nama as prodi_nama',
                            'dosen.prodi_id',
                            'users.username',
                            'periode_akademik.nama_periode'
                        )
                        ->first();
        return view('kaprodi_CreateMatkul', compact('kaprodi'));
    }

    public function createMatkul(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'kode_matkul' => 'required|string|max:50',
            'nama_matkul' => 'required|string|max:50',
            'sks' => 'required|integer|min:1|max:8',
            'semester' => 'required|integer|min:1|max:7',
        ]);
    
        $cekMatkul = DB::table('matakuliah')->where('kode_matkul', $request->kode_matkul)->first();
    
        if ($cekMatkul) {
            return redirect()->back()->with('error', 'Kode mata kuliah sudah ada.');
        }

        $lastMatkul = DB::table('matakuliah')
                ->orderBy('id_matkul', 'desc')
                ->first();
    
            $id_ruang = $lastMatkul ? $lastMatkul->id_matkul + 1 : 1;
    
        DB::table('matakuliah')->insert([
            'kode_matkul' => $request->kode_matkul,
            'nama_matkul' => $request->nama_matkul,
            'sks' => $request->sks,
            'semester' => $request->semester,
            'created_at' => now(),
        ]);
    
        // dd($request->all());
        return redirect()->back()->with('sweetAlert', [
            'title' => 'Berhasil!',
            'text' => 'Matkul berhasil dibuat.',
            'icon' => 'success'
        ]);
    }    

    public function batalkanJadwal(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_jadwal' => 'required|integer|exists:kaprodi_JadwalKuliah,id_jadwal', // Validasi id_jadwal pada tabel kaprodi_JadwalKuliah
        ]);
    
        // Hapus jadwal berdasarkan id_jadwal
        JadwalKuliah::where('id_jadwal', $request->id_jadwal)->delete();
    
        // Redirect atau respon sukses
        return redirect()->back()->with('success', 'Jadwal kuliah berhasil dibatalkan.');
    }   

    public function indexCreateJadwal()
    {
        $kaprodi = DB::table('dosen')
            ->join('users', 'dosen.id_user', '=', 'users.id')
            ->join('program_studi', 'dosen.prodi_id', '=', 'program_studi.id_prodi')
            ->crossJoin('periode_akademik')
            ->where('users.roles1', '=', 'dosen') // Pastikan ini sesuai dengan peran yang tepat
            ->where('users.roles2', '=', 'kaprodi') // Pastikan ini juga sesuai
            ->where('dosen.id_user', '=', Auth::id())
            ->orderBy('periode_akademik.created_at', 'desc') // Mengurutkan berdasarkan timestamp terbaru
            ->select('dosen.id_dosen',
                'dosen.nip',
                'dosen.nama as dosen_nama',
                'program_studi.nama as prodi_nama',
                'dosen.prodi_id',
                'users.username',
                'periode_akademik.nama_periode'
            )
            ->first();
    
        // Ambil semua mata kuliah
        $namaMK = Matakuliah::all();
    
        // Ambil data ruangan yang sesuai dengan prodi kaprodi
        $ruangan = DB::table('alokasi_ruangan')
        ->join('ruangan', 'alokasi_ruangan.id_ruang', '=', 'ruangan.id_ruang')
        ->where('alokasi_ruangan.id_prodi', $kaprodi->prodi_id)
        ->select(
            'alokasi_ruangan.id_ruang',
            'ruangan.nama as nama_ruang',
        )
        ->get();
    
        // Ambil jadwal kuliah
        $jadwal = DB::table('jadwal_kuliah')
        ->join('matakuliah', 'jadwal_kuliah.kode_matkul', '=', 'matakuliah.kode_matkul')
        ->join('ruangan', 'jadwal_kuliah.id_ruang', '=', 'ruangan.id_ruang')
        ->select(
            'jadwal_kuliah.id_jadwal',
            'jadwal_kuliah.kode_matkul',
            'matakuliah.nama_matkul',
            'jadwal_kuliah.kelas',
            'matakuliah.semester',
            'jadwal_kuliah.hari',
            'ruangan.nama as nama_ruang',
            'jadwal_kuliah.jam_mulai',
            'jadwal_kuliah.jam_selesai'
        )
        ->get();

        // Ambil nama dosen
        $dosen = Dosen::where('prodi_id', $kaprodi->prodi_id)->get(); // Ambil dosen berdasarkan prodi_id kaprodi

        // Kirimkan data ke view
        return view('kaprodi_CreateJadwal', compact('kaprodi', 'namaMK', 'ruangan', 'jadwal', 'dosen'));
    }

    
    public function createJadwal(Request $request)
    {
        $validated = $request->validate([
            'kode_matkul' => 'required|exists:matakuliah,kode_matkul', // Validasi kode_matkul
            'id_dosen' => 'required|exists:dosen,id_dosen', // Validasi dosen pengampu
            'id_ruang' => 'required|exists:ruangan,id_ruang', // Validasi ruangan
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat', // Validasi hari
            'jam_mulai' => 'required|date_format:H:i', // Validasi jam mulai
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai', // Jam selesai harus setelah jam mulai
            'kelas' => 'required|in:A,B,C,D,E', // Validasi kelas
        ]);

        
    try {
        // Ambil data mata kuliah
        $matakuliah = Matakuliah::where('kode_matkul', $validated['kode_matkul'])->firstOrFail();
        $semester = $matakuliah->semester;

        // Tentukan id_periode
        $id_periode = $semester % 2 === 1 ? 24251 : 24252;

        // Ambil data ruangan
        $ruangan = DB::table('ruangan')->where('id_ruang', $validated['id_ruang'])->firstOrFail();

        // Insert ke tabel jadwal_kuliah
        DB::table('jadwal_kuliah')->insert([
            'kode_matkul' => $validated['kode_matkul'],
            'kuota' => $ruangan->kuota,
            'id_dosen' => $validated['id_dosen'],
            'id_ruang' => $validated['id_ruang'],
            'hari' => $validated['hari'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'kelas' => $validated['kelas'],
            'semester' => $semester,
            'id_periode' => $id_periode,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

            return redirect()->back()->with('success', 'Jadwal berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }


} 