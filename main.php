<?php
  ob_start();
  session_start();
  $valor = $_COOKIE['nome'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title><?php echo $valor;?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <style>
     table {padding: 5px; width: 100%; text-align: center; }
     th {  color: solid black; padding: 10px; height: 30px; background-color: AliceBlue}
     tr { border: 1px solid black; padding: 5px; }
     td { border: 1px solid black; padding: 10px; color: black; }
     /* Apply custom CSS styles to the navbar */
    .navbar-light .navbar-nav .nav-link {
    color: #FFFFFF; /*mudar cor das labels*/
    }
  </style>
</head>
<body>

<!-- Add custom CSS classes to the navbar -->
<nav class="navbar navbar-expand-lg bg-dark">
  <a class="navbar-brand text-white" href="#"><?php echo $valor;?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
    <ul class="navbar-nav">
    <li class="nav-item active">
        <a class="nav-link text-white" href="?PainelDeControle">PainelDeControle</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link text-white" href="?imoveis">Imóveis</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link text-white" href="?clientes">Clientes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white" href="?LogOut">LogOut</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container-fluid mt-3">
<?php
  if (isset($_GET["LogOut"])) {
    setcookie("nome", "", time() - 3600, "/");
    session_unset();
    session_destroy();         
    header("Location: index.php");
    exit();
  }
  if (isset($_GET["clientes"])) {
    include 'CrudCliente/editar_clientes.php';
  }if (isset($_GET["PainelDeControle"])) {
    include 'PainelDeControle/PainelDeControle.php';
  }if (isset($_GET["imoveis"])) {
    include 'CrudImovel/editar_imovel.php';
  }
?>
</div>



</body>
</html>