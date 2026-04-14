<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class Control extends Controller
{
    /**
     * Dashboard content blueprint for each access level.
     */
    private const DASHBOARD_BY_LEVEL = [
        1 => [
            'role_name' => 'Ketua Kelas',
            'headline' => 'Pantau kondisi ruanganmu dan kirim pengajuan dengan cepat.',
            'summary_cards' => [
                ['label' => 'Ruangan Saya', 'value' => '-', 'tone' => 'soft'],
                ['label' => 'Barang Tercatat', 'value' => '0 Barang', 'tone' => 'solid'],
                ['label' => 'Barang Perlu Dicek', 'value' => '0 Barang', 'tone' => 'warn'],
                ['label' => 'Pengajuan Aktif', 'value' => '0 Permintaan', 'tone' => 'soft'],
            ],
            'quick_actions' => [
                'Lihat inventaris ruangan',
                'Ajukan barang baru',
                'Ajukan perbaikan barang',
                'Lihat riwayat pengajuan',
            ],
            'panels' => [
                [
                    'title' => 'Fokus Hari Ini',
                    'items' => [
                        'Pastikan kondisi meja, kursi, dan papan tulis di kelas tetap terpantau.',
                        'Periksa pengajuan yang masih menunggu persetujuan wali kelas.',
                        'Catat barang rusak agar segera diajukan ke tahap berikutnya.',
                    ],
                ],
                [
                    'title' => 'Pengajuan Terbaru',
                    'items' => [
                        'Perbaikan kipas angin - Menunggu verifikasi admin',
                        'Penambahan kursi siswa - Menunggu persetujuan owner',
                        'Penggantian lampu kelas - Selesai direalisasikan',
                    ],
                ],
            ],
        ],
        2 => [
            'role_name' => 'Admin / Wali Kelas',
            'headline' => 'Verifikasi pengajuan kelas yang kamu pegang dan pantau inventarisnya.',
            'summary_cards' => [
                ['label' => 'Ruangan Tanggung Jawab', 'value' => '4 Ruangan', 'tone' => 'soft'],
                ['label' => 'Pengajuan Masuk', 'value' => '6 Permintaan', 'tone' => 'solid'],
                ['label' => 'Menunggu Review', 'value' => '2 Permintaan', 'tone' => 'warn'],
                ['label' => 'Disetujui Hari Ini', 'value' => '3 Permintaan', 'tone' => 'soft'],
            ],
            'quick_actions' => [
                'Review pengajuan masuk',
                'Lihat inventaris kelas',
                'Beri catatan verifikasi',
                'Lihat riwayat persetujuan',
            ],
            'panels' => [
                [
                    'title' => 'Prioritas Verifikasi',
                    'items' => [
                        'Tinjau pengajuan dari kelas yang membutuhkan tindakan cepat.',
                        'Cek kelengkapan alasan dan jumlah barang yang diajukan user.',
                        'Pastikan pengajuan layak diteruskan ke owner.',
                    ],
                ],
                [
                    'title' => 'Aktivitas Terkini',
                    'items' => [
                        'Kelas 7A mengajukan perbaikan proyektor.',
                        'Kelas 8B mengajukan penambahan meja guru.',
                        'Kelas 9C menunggu tindak lanjut owner.',
                    ],
                ],
            ],
        ],
        3 => [
            'role_name' => 'Owner / Kepala Sekolah',
            'headline' => 'Lihat kondisi inventaris sekolah dan ambil keputusan akhir pengajuan.',
            'summary_cards' => [
                ['label' => 'Total Ruangan', 'value' => '18 Ruangan', 'tone' => 'soft'],
                ['label' => 'Pengajuan Final', 'value' => '5 Permintaan', 'tone' => 'solid'],
                ['label' => 'Menunggu Keputusan', 'value' => '2 Permintaan', 'tone' => 'warn'],
                ['label' => 'Disetujui Bulan Ini', 'value' => '14 Permintaan', 'tone' => 'soft'],
            ],
            'quick_actions' => [
                'Lihat semua pengajuan final',
                'Tinjau inventaris sekolah',
                'Lihat laporan pengajuan',
                'Cek histori persetujuan',
            ],
            'panels' => [
                [
                    'title' => 'Keputusan Strategis',
                    'items' => [
                        'Prioritaskan pengajuan yang berdampak langsung pada kegiatan belajar.',
                        'Pantau tren kebutuhan barang per ruangan atau unit.',
                        'Gunakan ringkasan ini untuk persetujuan akhir yang lebih cepat.',
                    ],
                ],
                [
                    'title' => 'Laporan Singkat',
                    'items' => [
                        'Permintaan perbaikan paling banyak berasal dari ruang kelas aktif.',
                        'Pengajuan penambahan meja dan kursi mendominasi bulan ini.',
                        'Sebagian besar pengajuan lolos verifikasi admin.',
                    ],
                ],
            ],
        ],
        4 => [
            'role_name' => 'Superadmin',
            'headline' => 'Kelola data master, pantau sistem, dan realisasikan pengajuan yang sudah disetujui.',
            'summary_cards' => [
                ['label' => 'Total User', 'value' => '36 Akun', 'tone' => 'soft'],
                ['label' => 'Total Inventaris', 'value' => '420 Item', 'tone' => 'solid'],
                ['label' => 'Menunggu Realisasi', 'value' => '4 Permintaan', 'tone' => 'warn'],
                ['label' => 'Aktivitas Sistem', 'value' => '12 Update', 'tone' => 'soft'],
            ],
            'quick_actions' => [
                'Kelola data user',
                'Kelola data ruangan',
                'Kelola inventaris',
                'Realisasikan pengajuan',
            ],
            'panels' => [
                [
                    'title' => 'Kontrol Sistem',
                    'items' => [
                        'Pastikan data ruangan, barang, dan kategori selalu sinkron.',
                        'Lanjutkan realisasi pengajuan yang telah disetujui owner.',
                        'Jaga histori inventaris tetap rapi untuk kebutuhan audit.',
                    ],
                ],
                [
                    'title' => 'Aktivitas Operasional',
                    'items' => [
                        'Update inventaris ruang laboratorium selesai dilakukan.',
                        'Reset password dua akun user berhasil diproses.',
                        'Realisasi pengajuan kursi kelas 7A sedang berlangsung.',
                    ],
                ],
            ],
        ],
    ];

    /**
     * Display the login page.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        return view('login');
    }

    /**
     * Handle the login process.
     */
    public function processLogin(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'nama' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $user = User::where('nama', $credentials['nama'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withInput($request->only('nama'))
                ->withErrors([
                    'login' => 'Nama atau password salah.',
                ]);
        }

        $request->session()->regenerate();
        session([
            'logged_in' => true,
            'user' => [
                'id_user' => $user->id_user,
                'nis' => $user->nis,
                'nama' => $user->nama,
                'level' => $user->level,
            ],
        ]);

        return redirect()->route('home');
    }

    /**
     * Display the home page after login.
     */
    public function home(): View|RedirectResponse
    {
        if (! session('logged_in')) {
            return redirect()->route('login');
        }

        $user = session('user');
        $level = (int) ($user['level'] ?? 0);
        $dashboard = self::DASHBOARD_BY_LEVEL[$level] ?? [
            'role_name' => 'Pengguna',
            'headline' => 'Dashboard belum tersedia untuk level ini.',
            'summary_cards' => [],
            'quick_actions' => [],
            'panels' => [],
        ];

        if ($level === 1) {
            $dashboard = $this->buildLevelOneDashboard($user, $dashboard);
        }

        return view('home', [
            'user' => $user,
            'dashboard' => $dashboard,
        ]);
    }

    /**
     * Populate level 1 dashboard with real room, inventory, and request data.
     *
     * @param  array<string, mixed>  $user
     * @param  array<string, mixed>  $dashboard
     * @return array<string, mixed>
     */
    private function buildLevelOneDashboard(array $user, array $dashboard): array
    {
        $assignment = DB::table('penugasan_ruangan as pr')
            ->join('ruangan as r', 'r.id_ruangan', '=', 'pr.id_ruangan')
            ->where('pr.id_user', $user['id_user'])
            ->where('pr.status', 'aktif')
            ->orderByDesc('pr.id_penugasan_ruangan')
            ->select('pr.id_ruangan', 'pr.peran_ruangan', 'r.nama_ruangan', 'r.kode_ruangan', 'r.jenis_ruangan')
            ->first();

        if (! $assignment) {
            $dashboard['headline'] = 'Akunmu belum terhubung ke ruangan. Hubungi superadmin untuk menambahkan penugasan ruangan.';
            $dashboard['panels'][0]['items'] = [
                'Akun ketua kelas membutuhkan data penugasan ruangan aktif.',
                'Setelah ruangan ditentukan, dashboard akan menampilkan inventaris dan pengajuan secara otomatis.',
                'Hubungi superadmin untuk menambahkan relasi di tabel penugasan ruangan.',
            ];
            $dashboard['panels'][1]['items'] = [
                'Belum ada data ruangan yang dapat ditampilkan.',
            ];

            return $dashboard;
        }

        $inventoryTotals = DB::table('inventaris_ruangan')
            ->where('id_ruangan', $assignment->id_ruangan)
            ->selectRaw('COALESCE(SUM(jumlah_baik + jumlah_rusak), 0) as total_barang')
            ->selectRaw('COALESCE(SUM(jumlah_rusak), 0) as barang_perlu_dicek')
            ->first();

        $activeRequestCount = DB::table('permintaan')
            ->where('id_ruangan', $assignment->id_ruangan)
            ->where('id_user_peminta', $user['id_user'])
            ->whereNotIn('status_permintaan', ['selesai', 'ditolak_admin', 'ditolak_owner', 'ditolak'])
            ->count();

        $latestRequests = DB::table('permintaan')
            ->where('id_ruangan', $assignment->id_ruangan)
            ->where('id_user_peminta', $user['id_user'])
            ->orderByDesc('tanggal_permintaan')
            ->orderByDesc('id_permintaan')
            ->limit(3)
            ->get(['jenis_permintaan', 'status_permintaan', 'tanggal_permintaan'])
            ->map(function ($request) {
                return sprintf(
                    '%s - %s (%s)',
                    ucfirst($request->jenis_permintaan),
                    str_replace('_', ' ', ucfirst($request->status_permintaan)),
                    $request->tanggal_permintaan
                );
            })
            ->all();

        $dashboard['headline'] = sprintf(
            'Kamu terhubung ke %s. Pantau kondisi inventaris kelas dan lanjutkan pengajuan bila ada kebutuhan baru.',
            $assignment->nama_ruangan
        );

        $dashboard['summary_cards'] = [
            ['label' => 'Ruangan Saya', 'value' => $assignment->nama_ruangan, 'tone' => 'soft'],
            ['label' => 'Barang Tercatat', 'value' => number_format((int) ($inventoryTotals->total_barang ?? 0)).' Barang', 'tone' => 'solid'],
            ['label' => 'Barang Perlu Dicek', 'value' => number_format((int) ($inventoryTotals->barang_perlu_dicek ?? 0)).' Barang', 'tone' => 'warn'],
            ['label' => 'Pengajuan Aktif', 'value' => number_format($activeRequestCount).' Permintaan', 'tone' => 'soft'],
        ];

        $dashboard['panels'][0]['items'] = [
            'Ruangan aktif: '.$assignment->nama_ruangan.' ('.$assignment->kode_ruangan.')',
            'Jenis ruangan: '.$assignment->jenis_ruangan,
            'Peran penugasan: '.str_replace('_', ' ', ucfirst($assignment->peran_ruangan)),
        ];

        $dashboard['panels'][1]['items'] = $latestRequests !== []
            ? $latestRequests
            : ['Belum ada pengajuan yang tercatat untuk ruangan ini.'];

        return $dashboard;
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        session()->forget(['logged_in', 'user']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar.');
    }
}
