<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card my-5">
                    <div class="card-body">
                        <h1 class="card-title text-center">Login</h1>
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger">Invalid credentials</div>
                        <?php endif; ?>
                        <form action="/login" method="post">
                            <div class="mb-3">
                                <label for="email_or_cpf" class="form-label">Email or CPF:</label>
                                <input type="text" class="form-control" id="email_or_cpf" name="email_or_cpf">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
