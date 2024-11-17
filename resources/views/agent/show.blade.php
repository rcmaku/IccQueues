<!DOCTYPE html>
<html>
<head>
    <title>Agent Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
<h1>Agent Details</h1>
<p><strong>ID:</strong> {{ $agent->id }}</p>
<p><strong>First Name:</strong> {{ $agent->first_name }}</p>
<p><strong>Last Name:</strong> {{ $agent->last_name }}</p>
<p><strong>Email:</strong> {{ $agent->email }}</p>

<a href="{{ route('agent.index') }}" class="btn btn-secondary">Menu</a>
<a href="{{ route('agent.edit', $agent->id) }}" class="btn btn-warning">Edit</a>

</body>
</html>
