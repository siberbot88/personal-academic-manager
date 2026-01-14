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
            background: #d32f2f;
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px;
        }

        .content {
            padding: 30px;
            background: #ffebee;
            border: 3px solid #d32f2f;
            border-radius: 8px;
            margin-top: 20px;
        }

        .task-info {
            background: white;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .action {
            background: #fff3cd;
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
            <h1 style="margin: 0;">STATUS BAHAYA</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Perlu Tindakan Segera</p>
        </div>

        <div class="content">
            <p><strong>Tugas ini masuk zona bahaya:</strong></p>

            <div class="task-info">
                <div style="font-size: 12px; text-transform: uppercase; color: #999;">
                    {{ $task->primaryCourse->name ?? 'No Course' }}
                </div>
                <div style="font-size: 18px; font-weight: bold; margin: 8px 0;">
                    {{ $task->title }}
                </div>
                <div style="font-size: 14px; color: #666; margin: 8px 0;">
                    @if($task->effective_due)
                        Deadline: {{ $task->effective_due->format('d M Y') }}
                        @if($task->effective_due->isPast())
                            <span class="badge badge-danger">OVERDUE</span>
                        @endif
                    @endif
                </div>
                <div style="font-size: 14px;">
                    Progress: <strong>{{ $task->progress_pct }}%</strong><br>
                    Health Score: <strong>{{ $task->health_score }}</strong>
                </div>
            </div>

            <p style="color: #d32f2f; font-weight: bold; font-size: 16px;">
                Phase overdue atau deadline dekat dengan progres rendah.
            </p>

            <div class="action">
                <strong>Langkah 25 menit:</strong><br>
                Kerjakan item checklist paling kecil. Break it down jika perlu.
            </div>
        </div>

        <div class="footer">
            <p>Personal Academic Manager</p>
            @if(config('app.url'))
                <p><a href="{{ config('app.url') }}/admin/tasks/{{ $task->id }}/edit" style="color: #d32f2f;">Ambil Tindakan
                        Sekarang</a></p>
            @endif
        </div>
    </div>
</body>

</html>