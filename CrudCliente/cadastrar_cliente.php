<?php
// Configurações do banco de dados
include('../configDB.php');
// Conexão com o banco de dados
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verifica se a conexão foi estabelecida com sucesso
if (!$conn) {
  die("Falha na conexão: " . mysqli_connect_error());
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Recebe os dados do formulário
  $nome = $_POST["nome"];
  $telefone = $_POST["telefone"];
  $email = $_POST["email"];
  $c_a = $_POST["c_a"];
  $tipo_imovel = $_POST["tipo-imovel"];
  $m2 = $_POST["m2"];
  $cidade = $_POST["cidade"];
  $preco_inicial = $_POST["preco-inicial"];
  $preco_final = $_POST["preco-final"];
  


  //adicionar código de páis no telefone
  $telefone = "+55".$telefone;

  $sql_verifica_telefone = "SELECT * FROM clientes WHERE telefone_cliente='$telefone'";
  $result = mysqli_query($conn, $sql_verifica_telefone);
  if (mysqli_num_rows($result) > 0) {
    echo "<h1>Já existe um cadastro com este telefone.<h1>";
  }else{
      // Insere os dados no banco de dados
      $sql = "INSERT INTO clientes (nome_cliente, telefone_cliente, email_cliente, c_a, tipo_imovel, m2, cidade, preco_inicial, preco_final)
      VALUES ('$nome', '$telefone', '$email', '$c_a', '$tipo_imovel', '$m2', '$cidade', '$preco_inicial', '$preco_final')";

      if (mysqli_query($conn, $sql)) {
      echo "Cadastro realizado com sucesso!";
      header("Location: ../main.php?clientes");
      } else {
         echo "Erro ao cadastrar: " . mysqli_error($conn);	
      }

    // Fecha a conexão com o banco de dados
    mysqli_close($conn);
  }
}

  
?>