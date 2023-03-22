<?php
include('configDBlogin.php');

$imob = $_COOKIE['nome'];
$sql = "SELECT login_imob, max_alertas, max_clientes, alertas_usados, clientes_usados FROM cadastros WHERE login_imob = '$imob'";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    echo "<table>
    <tr>        
        <th>Imobiliária</th>
        <th>Máximo de Alertas/Mês</th>
        <th>Máximo de Clientes</th>
        <th>Qnt. Alertas Feitos</th>
        <th>Qnt. de clientes cadastrados</th>
    </tr>";
    // Saída de dados de cada linha
    $i = 1;
    while($row = $result->fetch_assoc()) {        
        echo "
        <tr>
            <td>".$row["login_imob"]."</td>
            <td>".$row["max_alertas"]."</td>
            <td>".$row["max_clientes"]."</td>
            <td>".$row["alertas_usados"]."</td>
            <td>".$row["clientes_usados"]."</td>
        </tr>";        
    }
    echo "</table>";
}
$mysqli->close();
?>