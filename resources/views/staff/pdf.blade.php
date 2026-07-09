<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <style>

        body{
            font-family: DejaVu Sans;
            font-size:12px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        th,td{
            border:1px solid #000;
            padding:8px;
            text-align:left;
        }

        th{
            background:#eeeeee;
        }

        h2{
            text-align:center;
            margin-bottom:5px;
        }

    </style>

</head>

<body>

<h2>TeratakClean Worker List</h2>

<p>
Generated on {{ now()->format('d M Y h:i A') }}
</p>

<table>

<thead>

<tr>

<th>No</th>

<th>Code</th>

<th>Name</th>

<th>Role</th>

<th>Phone</th>

</tr>

</thead>

<tbody>

@foreach($staff as $member)

<tr>

<td>{{ $loop->iteration }}</td>

<td>{{ strtoupper($member->code) }}</td>

<td>{{ $member->name ?? '-' }}</td>

<td>{{ ucfirst($member->role) }}</td>

<td>{{ $member->phone ?? '-' }}</td>

</tr>

@endforeach

</tbody>

</table>

</body>

</html>