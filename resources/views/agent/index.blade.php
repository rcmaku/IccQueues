<!-- resources/views/alumnos/index.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Users List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        h1 {
            color: #343a40;
            text-align: center;
            margin-bottom: 40px;
        }
        .table-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .table tbody tr:hover {
            background-color: #e9ecef;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 50px;
            padding: 10px 20px;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .alert {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="container mt-5">
<h1>Agent List</h1>

<!-- Alert for Success Messages -->
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<!-- Button to Add New Item -->
<div class="d-flex justify-content-end mb-4">
    <a href="{{ route('agent.create') }}" class="btn btn-custom">New Agent</a>
</div>

<div class="table-container">
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>LastName</th>
            <th>Email</th>
            <th>Password</th>
            <th>Creation Date</th>
            <th>Modified Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($agents as $agent)
            <tr>
                <td>{{ $agent->id }}</td>
                <td>{{ $agent->first_name }}</td>
                <td>{{ $agent->last_name }}</td>
                <td>{{ $agent->email }}</td>
                <td>{{ $agent->password }}</td>
                <td>{{ $agent->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $agent->updated_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('agent.show', $agent->id) }}" class="btn btn-info">View</a>
                    <a href="{{ route('agent.edit', $agent->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('agent.destroy', $agent->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this item?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

</body>
</html>
