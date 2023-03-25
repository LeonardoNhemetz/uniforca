<?php
session_start();

$valor = $_COOKIE['nome'];

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
  
  //adicionar código de país no telefone
  $telefone = "+55".$telefone;

  //Checa a quantidade de clientes antes de adicionar
  $sql = "SELECT COUNT(*) as count FROM clientes";
  $result = $conn->query($sql);

  //Checa a quantidade de entidades que existem na tabela cliente
  if ($result !== false && $result->num_rows > 0) {
    $linha = $result->fetch_assoc();
    $quantidade_de_clientes = $linha['count'];

    //Se tiver menor ou igual a 15, verifique se há número igual
    if(intval($quantidade_de_clientes) <= 15){
      $sql_verifica_telefone = "SELECT * FROM clientes WHERE telefone_cliente='$telefone'";
      $result = mysqli_query($conn, $sql_verifica_telefone);

      if (mysqli_num_rows($result) > 0) {
        echo "<h1>Já existe um cadastro com este telefone.<h1>";
      } else {
        // Insere os dados no banco de dados
        $sql = "INSERT INTO clientes (nome_cliente, telefone_cliente, email_cliente, c_a, tipo_imovel, m2, cidade, preco_inicial, preco_final)
        VALUES ('$nome', '$telefone', '$email', '$c_a', '$tipo_imovel', '$m2', '$cidade', '$preco_inicial', '$preco_final')";

        if (mysqli_query($conn, $sql)) {
          // Debite o usuario do BD das imobiliarias
          $conn->close();
          include('../configDBlogin.php');
          $quant = intval($quantidade_de_clientes);
          $quant++;
          $sql = "UPDATE cadastros SET clientes_usados = $quant WHERE login_imob = '$valor' ";
          if ($mysqli->query($sql) === TRUE) {
            mysqli_close($mysqli);
            header("Location: ../main.php?clientes");
          } else {
            echo "Erro ao cadastrar: " . mysqli_error($conn);	
          } 
        }
      }  
    }     
  }
}
?>