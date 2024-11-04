{{-- resources/views/emails/reset_password.blade.php --}}

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reset de Senha</title>
</head>

<body>
    <h1>Reset de Senha</h1>
    <p>Clique no link abaixo para resetar sua senha:</p>
    <a href="{{ url('password/reset', $token) }}">Resetar Senha</a>
</body>

</html>
