<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Login</h1>
        <form id="loginForm">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" required />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" required />
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

    <script>
        $('#loginForm').submit(function(e) {
            e.preventDefault();
            const email = $('#email').val();
            const password = $('#password').val();
            
            $.ajax({
                url: '/api/login',
                type: 'POST',
                data: JSON.stringify({ email, password }),
                contentType: 'application/json',
                success: function(response) {
                    localStorage.setItem('jwtToken', response.token);
                    alert('Login successful!');
                    window.location.href = '/tasks';
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.error || 'Login failed. Please try again.');
                }
            });
        });
    </script>
</body>
</html>
