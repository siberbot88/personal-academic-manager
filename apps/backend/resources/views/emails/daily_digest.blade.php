<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #003366;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .slot {
            margin: 20px 0;
            padding: 20px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
        }

        .slot-1 {
            border-color: #003366;
            background: #f0f4f8;
        }

        .slot-2 {
            border-color: #999;
            background: #f9f9f9;
        }

        .slot-3 {
            border-color: #d32f2f;
            background: #fff5f5;
        }

        .reason {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 8px;
        }

        .task-title {
            font-size: 18px;
            font-weight: bold;
            margin: 8px 0;
        }

        .course {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }

        .meta {
            font-size: 14px;
            color: #555;
            margin: 8px 0;
        }

        .action {
            background: #fffbea;
            border-left: 4px solid #f59e0b;
            padding: 12px;
            margin-top: 12px;
            font-style: italic;
        }

        .empty {
            text-align: center;
            color: #999;
            padding: 40px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0;">Fokus Utama Hari Ini</h1>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">Top 3 Tugas Yang Perlu Perhatian</p>
        </div>

        @foreach(['slot1' => 1, 'slot2' => 2, 'slot3' => 3] as $slotKey => $slotNum)
            @php
                $task = $top3[$slotKey]['task'] ?? null;
                $reason = $top3[$slotKey]['reason'] ?? '';

                $actionSuggestion = match ($slotKey) {
                    'slot1' => 'Kerjakan 1 checklist di fase terdekat.',
                    'slot2' => 'Sentuh 1 checklist pertama untuk memulai.',
                    'slot3' => 'Ambil 10 menit untuk memecah/memperbaiki bagian tersulit.',
                };
            @endphp

            <div class="slot slot-{{ $slotNum }}">
                @if($task)
                    <div class="reason">{{ $reason }}</div>
                    <div class="course">{{ $task->primaryCourse->name ?? 'No Course' }}</div>
                    <div class="task-title">{{ $task->title }}</div>
                    <div class="meta">
                        Deadline: {{ $task->effective_due ? $task->effective_due->format('d M Y') : 'No Due Date' }}
                        @if($task->nearest_phase_due)
                            <span style="color: #999;">(Phase)</span>
                        @endif
                        •
                        <span
                            style="background: {{ $task->health_status === 'aman' ? '#d4edda' : ($task->health_status === 'rawan' ? '#fff3cd' : '#f8d7da') }}; padding: 2px 8px; border-radius: 4px; font-size: 12px;">
                            {{ $task->progress_pct }}%
                        </span>
                    </div>
                    <div class="action">
                        <strong>Aksi 1 Langkah:</strong> {{ $actionSuggestion }}
                    </div>
                @else
                    <div class="empty">
                        Slot {{ $slotNum }} Kosong<br>
                        <small>Tidak ada tugas yang prioritas</small>
                    </div>
                @endif
            </div>
        @endforeach

        <div class="footer">
            <p>Personal Academic Manager • {{ now()->format('l, d F Y') }}</p>
            @if(config('app.url'))
                <p><a href="{{ config('app.url') }}/admin" style="color: #003366;">Buka Dashboard</a></p>
            @endif
        </div>
    </div>
</body>

</html>