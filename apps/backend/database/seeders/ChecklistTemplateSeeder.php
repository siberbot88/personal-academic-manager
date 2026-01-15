<?php

namespace Database\Seeders;

use App\Models\PhaseTemplate;
use App\Models\TaskTypeTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChecklistTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $checklists = [
            // Makalah
            'Makalah' => [
                'Riset & Referensi' => [
                    'Tentukan topik dan ruang lingkup',
                    'Cari minimal 5-8 jurnal/buku referensi',
                    'Buat outline struktur makalah',
                ],
                'Penulisan Draft' => [
                    'Tulis pendahuluan dan latar belakang',
                    'Kembangkan isi (3-4 bab utama)',
                    'Tulis kesimpulan dan saran',
                ],
                'Revisi & Perbaikan' => [
                    'Review struktur dan flow konten',
                    'Perbaiki typo dan grammar',
                    'Cek sitasi dan daftar pustaka',
                ],
                'Finalisasi' => [
                    'Format sesuai template (margin, font, spacing)',
                    'Tambahkan cover, abstract, daftar isi',
                    'Final proofread',
                ],
                'Submit & Serahkan' => [
                    'Export ke PDF final',
                    'Submit ke LMS/email dosen',
                ],
            ],

            // Laporan Akhir
            'Laporan Akhir' => [
                'Riset & Referensi' => [
                    'Survey dan data collection',
                    'Analisis data preliminary',
                    'Buat kerangka bab 1-5',
                ],
                'Penulisan Draft' => [
                    'Tulis BAB I (Pendahuluan)',
                    'Tulis BAB II-III (Tinjauan & Metode)',
                    'Tulis BAB IV-V (Hasil & Kesimpulan)',
                ],
                'Revisi & Perbaikan' => [
                    'Review dari dosen pembimbing',
                    'Perbaiki sesuai feedback',
                    'Cross-check semua tabel/gambar',
                ],
                'Finalisasi' => [
                    'Format akhir (cover, halaman pengesahan)',
                    'Cetak draft untuk review terakhir',
                    'Persiapan presentasi slides',
                ],
                'Submit & Serahkan' => [
                    'Cetak hardcopy (3 exemplar)',
                    'Jilid dan upload ke repository',
                    'Daftar sidang',
                ],
            ],

            // Project (Software/Engineering)
            'Project' => [
                'Riset & Referensi' => [
                    'Analisis kebutuhan dan scope project',
                    'Tentukan tech stack dan tools',
                    'Buat timeline dan breakdown task',
                ],
                'Penulisan Draft' => [
                    'Setup repository dan environment',
                    'Implementasi fitur core (backend/logic)',
                    'Implementasi UI/frontend',
                    'Integrasi dan testing parsial',
                ],
                'Revisi & Perbaikan' => [
                    'Unit testing dan bug fixing',
                    'Manual testing (feature walkthrough)',
                    'Code review dan refactoring',
                ],
                'Finalisasi' => [
                    'Dokumentasi (README, API docs)',
                    'Cleanup code dan optimize performance',
                    'Persiapan demo/screenshot',
                ],
                'Submit & Serahkan' => [
                    'Deploy ke server/hosting',
                    'Submit link demo dan source code',
                ],
            ],

            // Presentasi
            'Presentasi' => [
                'Riset & Referensi' => [
                    'Research materi dan data yang akan dipresentasikan',
                    'Tentukan key messages dan struktur',
                    'Kumpulkan visual/grafik pendukung',
                ],
                'Penulisan Draft' => [
                    'Buat outline slide (intro, isi, closing)',
                    'Design slides dengan template konsisten',
                    'Tambahkan visual, chart, dan diagram',
                ],
                'Revisi & Perbaikan' => [
                    'Practice run (latihan presentasi)',
                    'Refine script dan timing',
                    'Minta feedback dari teman/dosen',
                ],
                'Finalisasi' => [
                    'Final review slides',
                    'Persiapan backup (USB, cloud)',
                    'Cek equipment (laptop, projector, pointer)',
                ],
                'Submit & Serahkan' => [
                    'Deliver presentasi',
                    'Q&A session',
                ],
            ],
        ];

        foreach ($checklists as $taskTypeName => $phaseChecklists) {
            $taskType = TaskTypeTemplate::where('name', $taskTypeName)->first();

            if (!$taskType) {
                $this->command->warn("TaskTypeTemplate '{$taskTypeName}' not found, skipping...");
                continue;
            }

            foreach ($phaseChecklists as $phaseName => $items) {
                $phase = PhaseTemplate::where('name', $phaseName)->first();

                if (!$phase) {
                    $this->command->warn("PhaseTemplate '{$phaseName}' not found, skipping...");
                    continue;
                }

                // Delete existing checklists for idempotency
                DB::table('checklist_templates')
                    ->where('task_type_template_id', $taskType->id)
                    ->where('phase_template_id', $phase->id)
                    ->delete();

                // Insert checklist items
                foreach ($items as $index => $title) {
                    DB::table('checklist_templates')->insert([
                        'task_type_template_id' => $taskType->id,
                        'phase_template_id' => $phase->id,
                        'title' => $title,
                        'sort_order' => $index + 1,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            $this->command->info("✓ ChecklistTemplate seeded for {$taskTypeName}");
        }
    }
}
