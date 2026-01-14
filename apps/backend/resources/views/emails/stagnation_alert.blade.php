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
            background: #ff6b6b;
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px;
        }

        .content {
            padding: 30px;
            background: #fff5f5;
            border: 2px solid #ff6b6b;
            border-radius: 8px;
            margin-top: 20px;
        }

        .task-info {
            background: white;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
        }

        .action {
            background: #fffbea;
            padding: 15px;
            margin-top: 20px;
            border-left: 4px solid #ffc107;
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
            <h1 style="margin: 0;">PERINGATAN STAGNASI</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Tidak ada progres 3 hari</p>
        </div>

        <div class="content">
            <p><strong>Tugas ini terlalu lama tidak disentuh:</strong></p>

            <div class="task-info">
                <div style="font-size: 12px; text-transform: uppercase; color: #999;">
                    {{ $task->primaryCourse->name ?? 'No Course' }}
                </div>
                <div style="font-size: 18px; font-weight: bold; margin: 8px 0;">
                    {{ $task->title }}
                </div>
                <div style="font-size: 14px; color: #666;">
                    Progress: {{ $task->progress_pct }}%<br>
                    Stagnasi: {{ $task->stagnation_days }} hari
                </div>
            </div>

            <p style="color: #d32f2f; font-weight: bold;">
                Risiko telat naik signifikan jika tidak segera ditangani.
            </p>

            <div class="action">
                <strong>Langkah 10 menit:</strong><br>
                Centang 1 checklist di fase terdekat. Fokus mulai, bukan selesai.
            </div>
        </div>

        <div class="footer">
            <p>Personal Academic Manager</p>
            @if(config('app.url'))
                <p><a href="{{ config('app.url') }}/admin/tasks/{{ $task->id }}/edit" style="color: #ff6b6b;">Buka Task
                        Sekarang</a></p>
            @endif
        </div>
    </div>
</body>

</html>