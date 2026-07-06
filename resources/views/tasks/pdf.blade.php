<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pending Tasks Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #1f2937;
            margin-bottom: 10px;
        }
        h2 {
            color: #374151;
            margin-top: 25px;
            margin-bottom: 12px;
            font-size: 16px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 8px;
        }
        .info {
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        thead {
            background-color: #f3f4f6;
        }
        th {
            border: 1px solid #d1d5db;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            font-size: 12px;
        }
        td {
            border: 1px solid #d1d5db;
            padding: 9px;
            font-size: 12px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: 600;
            font-size: 10px;
        }
        .cleaning {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .maintenance {
            background-color: #fce7f3;
            color: #be185d;
        }
        .summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin: 20px 0;
        }
        .summary-card {
            background-color: #f3f4f6;
            padding: 12px;
            border-radius: 6px;
            border-left: 4px solid #6b7280;
        }
        .summary-card h3 {
            margin: 0 0 6px 0;
            font-size: 12px;
            color: #6b7280;
            font-weight: 600;
        }
        .summary-card .number {
            font-size: 24px;
            font-weight: bold;
            color: #6b7280;
            margin: 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #d1d5db;
            font-size: 11px;
            color: #6b7280;
            text-align: right;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <h1>📋 Pending Tasks Report</h1>
    <div class="info">
        <p>Generated on {{ now()->format('d M Y H:i') }} | TeratakClean System</p>
    </div>

    <!-- Summary Cards -->
    <div class="summary">
        <div class="summary-card">
            <h3>Total Tasks</h3>
            <p class="number">{{ $pendingTasks->count() }}</p>
        </div>
        <div class="summary-card" style="border-left-color: #0ea5e9;">
            <h3>Cleaning Tasks</h3>
            <p class="number" style="color: #0ea5e9;">{{ $housekeepingTasks->count() }}</p>
        </div>
        <div class="summary-card" style="border-left-color: #f97316;">
            <h3>Maintenance Tasks</h3>
            <p class="number" style="color: #f97316;">{{ $maintenanceTasks->count() }}</p>
        </div>
    </div>

    <!-- Housekeeping Tasks -->
    @if($housekeepingTasks->count() > 0)
        <h2>🧹 Cleaning Tasks ({{ $housekeepingTasks->count() }})</h2>
        <table>
            <thead>
                <tr>
                    <th>Unit</th>
                    <th>Task Name</th>
                    <th>Assigned To</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($housekeepingTasks as $task)
                    <tr>
                        <td><strong>{{ $task->unit->unit_name }}</strong></td>
                        <td>{{ $task->cleaningTask->task_name }}</td>
                        <td>{{ $task->assignedStaff->code ?? 'Unassigned' }}</td>
                        <td>{{ \Carbon\Carbon::parse($task->guest_checkout_date)->format('d M Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Maintenance Tasks -->
    @if($maintenanceTasks->count() > 0)
        <h2>🔧 Maintenance Tasks ({{ $maintenanceTasks->count() }})</h2>
        <table>
            <thead>
                <tr>
                    <th>Unit</th>
                    <th>Task Name</th>
                    <th>Assigned To</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($maintenanceTasks as $task)
                    <tr>
                        <td><strong>{{ $task->unit->unit_name }}</strong></td>
                        <td>{{ $task->task_name }}</td>
                        <td>{{ $task->assignedStaff->code ?? 'Unassigned' }}</td>
                        <td>{{ $task->created_at->format('d M Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>TeratakClean Housekeeping Management System</p>
    </div>
</body>
</html>