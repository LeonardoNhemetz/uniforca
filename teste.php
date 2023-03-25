<?php
include('configDB.php');

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verifica se a conexão foi estabelecida com sucesso
if (!$conn) {
  die("Falha na conexão: " . mysqli_connect_error());
}

$sql = "SELECT COUNT(*) AS count FROM clientes";
$result = $conn->query($sql);

// Verificar se a consulta foi bem sucedida e obter o número de clientes cadastrados na tabela
if ($result !== false && $resultado->num_rows > 0) {
    $linha = $result->fetch_assoc();
    $quantidade_de_clientes = $linha['count'];
    
} else {
    echo "Não foi possível contar o número de clientes cadastrados na tabela";
}