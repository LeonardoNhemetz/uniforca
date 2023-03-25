<?php
// Importa o arquivo de configuração do banco de dados
require_once 'configDBlogin.php';

$imob = $_COOKIE['nome'];

// Utiliza prepared statements para evitar injeção de SQL
$stmt = $mysqli->prepare("SELECT login_imob, max_alertas, max_clientes, alertas_usados, clientes_usados FROM cadastros WHERE login_imob = ?");
$stmt->bind_param('s', $imob);
$stmt->execute();
$result = $stmt->get_result();

// Verifica se há resultados da consulta
if ($result->num_rows > 0) {
    // Exibe a tabela com as informações
    echo "<table>
    <tr>        
        <th>Imobiliária</th>
        <th>Máximo de Alertas/Mês</th>
        <th>Máximo de Clientes</th>
        <th>Qnt. Alertas Feitos</th>
        <th>Qnt. de clientes cadastrados</th>
    </tr>";
    
    // Exibe os dados de cada linha da tabela
    while ($row = $result->fetch_assoc()) {        
        echo "<tr>
            <td>" . htmlspecialchars($row["login_imob"]) . "</td>
            <td>" . htmlspecialchars($row["max_alertas"]) . "</td>
            <td>" . htmlspecialchars($row["max_clientes"]) . "</td>
            <td>" . htmlspecialchars($row["alertas_usados"]) . "</td>
            <td>" . htmlspecialchars($row["clientes_usados"]) . "</td>
        </tr>";        
    }
    echo "</table>";
} else {
    // Exibe uma mensagem caso não haja resultados
    echo 'Nenhum registro encontrado.';
}

// Fecha a conexão com o banco de dados e o statement
$stmt->close();
$mysqli->close();
?>