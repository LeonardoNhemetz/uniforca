<?php
session_start();

// Dados para conexão com o banco de dados
include('configDBlogin.php');
if(isset($_COOKIE['login_coord'])) {
	// Se o usuário já estiver logado, redireciona para a página de boas-vindas
	header("Location: main.php");
}

// Verifica se o formulário de login foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($mysqli, $_POST['username']);
    $password = mysqli_real_escape_string($mysqli, $_POST['password']);

    // Consulta o banco de dados para verificar se o usuário existe
    $query = "SELECT login_coord, senha_coord FROM coordenadores WHERE login_coord='$username' AND senha_coord='$password'";
    $resultado = mysqli_query($mysqli, $query);

    // Verifica se a consulta foi bem sucedida e se há apenas um registro
    if (mysqli_num_rows($resultado) == 1) {
        // Define uma variável de sessão com o ID do usuário
        
        $_SESSION['login_coord'] = mysqli_fetch_assoc($resultado)['login_coord'];

        // Define um cookie para lembrar do login
        setcookie('login_coord', $_SESSION['login_coord'], time()+3600*24*30);
        $user = (isset($_COOKIE['login_coord'])) ? $_COOKIE['login_coord'] : '';
            
        // Redireciona para a página de perfil do usuário
        header("Location: main.php");
        exit();
    } else {
        $erro = "Usuário ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
    <style type="text/css">
        body {
            background-color: #E6E6FA;
        }
        .container {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container text-center">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Entre com suas credenciais</h3>
                    </div>
                    <div class="panel-body">
                        <form accept-charset="UTF-8" role="form" method="post" action="">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Usuário" name="username" type="text" value="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Senha" name="password" type="password" value="">
                                </div>
                                <input class="btn btn-lg btn-success btn-block" type="submit" value="Entrar">
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>