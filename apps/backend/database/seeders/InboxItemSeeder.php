<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\InboxItem;
use Illuminate\Database\Seeder;

class InboxItemSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();

        if ($courses->isEmpty()) {
            $this->command->warn('No courses found. Please seed courses first.');
            return;
        }

        $courseA = $courses->first();
        $courseB = $courses->skip(1)->first() ?? $courseA;

        // Sample inbox items
        $items = [
            [
                'course_id' => $courseA->id,
                'title' => 'Tutorial Implementasi Algoritma Greedy',
                'url' => 'https://www.youtube.com/watch?v=example1',
                'note' => 'Video penjelasan greedy algorithm untuk tugas praktikum minggu depan',
                'source' => 'WA',
                'tags' => ['praktikum', 'algoritma'],
            ],
            [
                'course_id' => $courseA->id,
                'title' => 'Slide Materi Week 12 - Dynamic Programming',
                'url' => 'https://drive.google.com/file/d/example',
                'note' => 'Materi DP dari dosen, perlu dipelajari sebelum UTS',
                'source' => 'Drive',
                'tags' => ['materi', 'uts'],
            ],
            [
                'course_id' => $courseB->id,
                'title' => 'Contoh Proposal Skripsi yang Baik',
                'url' => 'https://docs.google.com/document/d/example',
                'note' => 'Referensi format proposal dari kakak tingkat',
                'source' => 'WA',
                'tags' => ['skripsi', 'referensi'],
            ],
            [
                'course_id' => $courseB->id,
                'title' => 'RPS Mata Kuliah Semester Ini',
                'url' => 'https://lms.university.ac.id/rps/example',
                'note' => 'RPS lengkap dengan jadwal dan bobot penilaian',
                'source' => 'LMS',
                'tags' => ['rps', 'administrasi'],
            ],
            [
                'course_id' => $courseB->id,
                'title' => 'Paper: Machine Learning Best Practices 2024',
                'url' => 'https://arxiv.org/abs/example',
                'note' => 'Paper untuk bahan kajian tugas akhir',
                'source' => 'Other',
                'tags' => ['paper', 'referensi', 'ml'],
            ],
        ];

        foreach ($items as $itemData) {
            $tags = $itemData['tags'];
            unset($itemData['tags']);

            $item = InboxItem::create($itemData);
            $item->attachTags($tags);
        }

        $this->command->info('Created 5 sample inbox items with tags.');
    }
}
