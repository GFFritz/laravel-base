{{-- resources/views/emails/create_password.blade.php --}}

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Criação de Senha</title>
</head>

<body>
    <h1>Clique no link abaixo para criar sua senha:</h1>
    <p>
        <a href="{{ $link }}">Criar Senha</a> <!-- Use o link passado pelo mailable -->
    </p>
</body>

</html>
