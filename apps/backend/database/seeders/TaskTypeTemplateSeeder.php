<?php

namespace Database\Seeders;

use App\Models\TaskTypeTemplate;
use App\Models\PhaseTemplate;
use App\Models\ChecklistTemplate;
use Illuminate\Database\Seeder;

class TaskTypeTemplateSeeder extends Seeder
{
    public function run()
    {
        // 1. Get Phases
        $riset = PhaseTemplate::where('name', 'Riset')->first();
        $draft = PhaseTemplate::where('name', 'Draft')->first();
        $struktur = PhaseTemplate::where('name', 'Struktur')->first();
        $revisi = PhaseTemplate::where('name', 'Revisi & Penyempurnaan')->first();
        $final = PhaseTemplate::where('name', 'Finalisasi')->first();

        // 2. Create "Laporan Akhir" Template
        $laporan = TaskTypeTemplate::firstOrCreate(
            ['name' => 'Laporan Akhir'],
            [
                'description' => 'Template standar untuk tugas besar berupa laporan akhir atau makalah panjang.',
                'big_threshold_days' => 14,
                'is_active' => true,
            ]
        );

        // Attach Phases with Weights
        // Riset 20, Draft 30, Struktur 10, Revisi 25, Finalisasi 15
        $laporan->phases()->syncWithoutDetaching([
            $riset->id => ['weight_percent' => 20, 'sort_order' => 1],
            $draft->id => ['weight_percent' => 30, 'sort_order' => 2],
            $struktur->id => ['weight_percent' => 10, 'sort_order' => 3],
            $revisi->id => ['weight_percent' => 25, 'sort_order' => 4],
            $final->id => ['weight_percent' => 15, 'sort_order' => 5],
        ]);

        // Create Checklists for Laporan Akhir
        $this->createChecklists($laporan, $riset, [
            'Kumpulkan referensi utama (jurnal/buku)',
            'Buat ringkasan 1 paragraf per referensi',
            'Catat sitasi penting untuk daftar pustaka',
        ]);

        $this->createChecklists($laporan, $draft, [
            'Tulis draft Pendahuluan (Latar Belakang)',
            'Tulis draft Isi/Pembahasan Utama',
            'Sisipkan gambar/tabel pendukung',
        ]);

        $this->createChecklists($laporan, $struktur, [
            'Buat outline final sesuai pedoman',
            'Sesuaikan heading dan subheading',
            'Pastikan alur paragraf logis',
        ]);

        $this->createChecklists($laporan, $revisi, [
            'Perbaiki kalimat yang ambigu atau lemah',
            'Periksa format font, margin, dan spasi',
            'Cek konsistensi istilah teknis',
        ]);

        $this->createChecklists($laporan, $final, [
            'Cek kelengkapan lampiran',
            'Export dokumen ke PDF',
            'Siapkan file submission/pengumpulan',
        ]);

        // 3. Create "Makalah" Template
        $makalah = TaskTypeTemplate::firstOrCreate(
            ['name' => 'Makalah'],
            [
                'description' => 'Template untuk tugas makalah standar.',
                'big_threshold_days' => 10,
                'is_active' => true,
            ]
        );

        // Attach Phases (Same phases, slight weight diff)
        $makalah->phases()->syncWithoutDetaching([
            $riset->id => ['weight_percent' => 25, 'sort_order' => 1],
            $struktur->id => ['weight_percent' => 15, 'sort_order' => 2],
            $draft->id => ['weight_percent' => 35, 'sort_order' => 3], // Draft lebih besar
            $revisi->id => ['weight_percent' => 15, 'sort_order' => 4],
            $final->id => ['weight_percent' => 10, 'sort_order' => 5],
        ]);

        // Checklists for Makalah (Simplified)
        $this->createChecklists($makalah, $riset, ['Cari 5 referensi relevan', 'Baca dan tandai poin penting']);
        $this->createChecklists($makalah, $struktur, ['Buat kerangka karangan']);
        $this->createChecklists($makalah, $draft, ['Tulis isi makalah', 'Tulis kesimpulan']);
        $this->createChecklists($makalah, $revisi, ['Self-editing typo dan ejaan']);
        $this->createChecklists($makalah, $final, ['Convert to PDF', 'Submit']);
    }

    private function createChecklists($template, $phase, $items)
    {
        foreach ($items as $index => $title) {
            ChecklistTemplate::firstOrCreate([
                'task_type_template_id' => $template->id,
                'phase_template_id' => $phase->id,
                'title' => $title,
            ], [
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }
}
