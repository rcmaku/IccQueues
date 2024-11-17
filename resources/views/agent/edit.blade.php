<!-- resources/views/alumnos/edit.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Edit Agent</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
<h1>Edit Agent</h1>

<form action="{{ route('agent.update', $agent->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="first_name" class="form-label">First Name:</label>
        <input type="text" name="first_name" class="form-control" id="name" value="{{ old('first_name') }}" required>
    </div>
    <div class="mb-3">
        <label for="last_name" class="form-label">Last Name:</label>
        <input type="text" name="last_name" class="form-control" id="last_name" value="{{old('last_name')}}">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email Address:</label>
        <input type="text" name="email" class="form-control" id="email" value="{{old('email')}}">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="text" name="password" class="form-control" id="password" value="{{old('password')}}">
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="{{ route('agent.index') }}" class="btn btn-secondary">Return</a>
</form>
</body>
</html>
