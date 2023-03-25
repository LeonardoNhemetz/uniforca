<?php

// faz a conexão com o banco de dados
include('configDB.php');

// Criar conexão com o MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão com o banco de dados falhou: " . $conn->connect_error);
}

// Selecionar todos os clientes da tabela "clientes"
$sql = "SELECT * FROM clientes";
$result = $conn->query($sql);

// Função para atualizar os dados de um cliente
if(isset($_POST['atualizar'])) {
    $atualizacao = $_POST['atualizacao'];
    $coluna = $_POST['coluna'];
    $id_cliente = $_POST['id_cliente'];
    $sql = "UPDATE clientes SET ".$coluna."='".$atualizacao."' WHERE id_cliente=".$id_cliente;
    if ($conn->query($sql) === TRUE) {
        echo "Dados atualizados com sucesso";
        header('Refresh:0');
    } else {
        echo "Erro ao atualizar dados: " . $conn->error;
    }
}

// Função para excluir um cliente
if(isset($_POST['excluir'])) {
    $id_cliente = $_POST['id_cliente'];
    $sql = "DELETE FROM clientes WHERE id_cliente=".$id_cliente;
    if ($conn->query($sql) === TRUE) {
        $sql = "SELECT COUNT(*) AS count FROM clientes";
        $result = $conn->query($sql);
        // Verificar se a consulta foi bem sucedida e obter o número de clientes cadastrados na tabela
        if ($result !== false && $result->num_rows > 0) {
            $linha = $result->fetch_assoc();
            $quantidade_de_clientes = $linha['count'];
            include('configDBlogin.php');
            $quant = intval($quantidade_de_clientes);
            $sql = "UPDATE cadastros SET clientes_usados = $quant WHERE login_imob = '$valor' ";

            if ($mysqli->query($sql) === TRUE) {
                mysqli_close($mysqli);
                header('Refresh:0');
            }

            
        }
        
    } else {
        echo "Erro ao excluir cliente: " . $conn->error;
    }
}

echo '<button class="btn btn-primary" onclick="window.location.href=\'CrudCliente/cadastrar_cliente.html\'">Cadastrar Novo Cliente</button>';


// Exibir tabela com todos os clientes e opções de atualizar e excluir
if ($result->num_rows > 0) {
    echo "<table>
    <tr>
        <th>Numeral</th>
        <th>Nome</th>
        <th>Telefone</th>
        <th>Email</th>
        <th>C/A</th>
        <th>Tipo do Imóvel</th>
        <th>M²</th>
        <th>Cidade Desejada</th>
        <th>Preço Inicial</th>
        <th>Preço Final</th>
        <th>Ações</th>
    </tr>";
    // Saída de dados de cada linha
    $i = 1;
    while($row = $result->fetch_assoc()) {        
        echo "
        <tr>
            <td>".$i."</td>
            <td>".$row["nome_cliente"]."</td>
            <td>".$row["telefone_cliente"]."</td>
            <td>".$row["email_cliente"]."</td>
            <td>".$row["c_a"]."</td>
            <td>".$row["tipo_imovel"]."</td>
            <td>".$row["m2"]."</td>
            <td>".$row["cidade"]."</td>
            <td>R$".$row["preco_inicial"]."</td>
            <td>R$".$row["preco_final"]."</td><td>
        <form method='post' action=''>
        <input type='text' name='atualizacao' placeholder='Digite a atualização'>
        <select name='coluna'><option value='nome_cliente'>Nome</option>
        <option value='telefone_cliente'>Telefone</option>
        <option value='email_cliente'>Email</option>
        <option value='c_a'>Comprar/Alugar</option>
        <option value='tipo_imovel'>Tipo do imóvel</option>
        <option value='preco_incial'>Preço Inicial</option>
        <option value='preco_final'>Preço Final</option></select>
        <input type='hidden' name='id_cliente' value='".$row["id_cliente"]."'>
        <button type='submit' value='atualizar' name='atualizar' class='btn btn-secondary btn-sm'>Atualizar</button>
        <button type='submit' value='excluir' name='excluir' class='btn btn-danger btn-sm'>Excluir</button>
        </form></td></tr>";
        $i++;
    }
    echo "</table>";
} else {
    echo "0 resultados";
}



$conn->close();
?>
