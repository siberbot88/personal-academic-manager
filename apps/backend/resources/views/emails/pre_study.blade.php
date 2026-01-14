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
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px;
        }

        .content {
            padding: 30px;
            background: #f9f9f9;
            border-radius: 8px;
            margin-top: 20px;
        }

        .task-card {
            background: white;
            padding: 20px;
            border-left: 4px solid #667eea;
            border-radius: 4px;
        }

        .course {
            font-size: 12px;
            text-transform: uppercase;
            color: #999;
            margin-bottom: 8px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 12px;
        }

        .meta {
            font-size: 14px;
            color: #666;
            margin: 8px 0;
        }

        .action-box {
            background: #fff3cd;
            border: 2px dashed #ffc107;
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 24px;">Sesi Belajar Malam Ini</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">20:00 – 22:00</p>
        </div>

        <div class="content">
            @if($task)
                <p><strong>Fokus 1 tugas untuk sesi ini:</strong></p>

                <div class="task-card">
                    <div class="course">{{ $task->primaryCourse->name ?? 'No Course' }}</div>
                    <div class="title">{{ $task->title }}</div>
                    <div class="meta">
                        Deadline: {{ $task->effective_due ? $task->effective_due->format('d M Y') : 'No Due Date' }}
                        @if($task->nearest_phase_due)
                            <span style="color: #999;">(dari fase terdekat)</span>
                        @endif
                    </div>
                    <div class="meta">
                        Progress saat ini: <strong>{{ $task->progress_pct }}%</strong>
                    </div>
                </div>

                <div class="action-box">
                    <strong>Aksi 25 menit pertama:</strong><br>
                    Selesaikan 1 checklist di fase terdekat. Jangan overthink, mulai yang termudah dulu.
                </div>
            @else
                <p style="text-align: center; color: #999; padding: 40px;">
                    Semua tugas on track!<br>
                    <small>Gunakan waktu ini untuk review atau istirahat.</small>
                </p>
            @endif
        </div>

        <div class="footer">
            <p>Personal Academic Manager • {{ now()->format('H:i') }}</p>
            @if(config('app.url') && $task)
                <p><a href="{{ config('app.url') }}/admin/tasks/{{ $task->id }}/edit" style="color: #667eea;">Buka Task</a>
                </p>
            @endif
        </div>
    </div>
</body>

</html>