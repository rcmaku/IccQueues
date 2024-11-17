<!-- resources/views/items/alumnos.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Add Agent</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
<h1>Register Agent</h1>
<!-- Alert for Errors Messages -->
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('agent.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="first_name" class="form-label">Firstname</label>
        <input type="text" name="first_name" class="form-control" id="first_name" value="{{old('first_name')}}">
    </div>
    <div class="mb-3">
        <label for="last_name" class="form-label">Lastname</label>
        <input type="text" name="last_name" class="form-control" id="last_name" value="{{old('last_name')}}">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Agent Email</label>
        <input type="text" name="email" class="form-control" id="email" value="{{old('email')}}">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password: </label>
        <input type="text" name="password" class="form-control" id="password">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    <a href="{{ route('agent.index') }}" class="btn btn-secondary">Return</a>
</form>
</body>
</html>
