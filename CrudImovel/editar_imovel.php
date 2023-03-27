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
$sql = "SELECT * FROM imoveis";
$result = $conn->query($sql);

// Função para atualizar os dados de um cliente
if(isset($_POST['atualizar'])) {
    $atualizacao = $_POST['atualizacao'];
    $coluna = $_POST['coluna'];
    $id_imovel = $_POST['id_imovel'];
    $sql = "UPDATE imoveis SET ".$coluna."='".$atualizacao."' WHERE id_imovel=".$id_imovel;
    if ($conn->query($sql) === TRUE) {
        echo "Dados atualizados com sucesso";
        header('Refresh:0');
    } else {
        echo "Erro ao atualizar dados: " . $conn->error;
    }
}

// Função para excluir um cliente
if(isset($_POST['excluir'])) {
    $id_imovel = $_POST['id_imovel'];
    $sql = "DELETE FROM imoveis WHERE id_imovel=".$id_imovel;
    if ($conn->query($sql) === TRUE) {
        header('Refresh:0');
        
    } else {
        echo "Erro ao excluir imovel: " . $conn->error;
    }
}

echo '<button class="btn btn-primary" onclick="window.location.href=\'CrudImovel/cadastrar_imovel.html\'">Cadastrar Novo Imóvel</button>';

// Exibir tabela com todos os clientes e opções de atualizar e excluir
if ($result->num_rows > 0) {
    // monta o cabeçalho da tabela
    echo "<table>
    <tr>
        <th>Numeral</th>
        <th>Tipo do Imóvel</th>
        <th>C/A</th>
        <th>M²</th>
        <th>Cidade</th>
        <th>Corpo da Mensagem</th>
        <th>Preço</th>
        <th>Link</th>
        <th>Ações</th> <!-- adicionei uma nova coluna para as ações -->
    </tr>";
    // Saída de dados de cada linha
    $i = 1;
    while($row = $result->fetch_assoc()) {        
        echo "
        <tr>
            <td>".$i."</td>
            <td>".$row["tipo_imovel"]."</td>
            <td>".$row["c_a_imovel"]."</td>
            <td>".$row["m2_imovel"]."</td>
            <td>".$row["cidade_imovel"]."</td>
            <td>".$row["corpo_msg_imovel"]."</td>
            <td>".$row["preco_imovel"]."</td>
            <td><a href='".$row["link_imovel"]."'>".$row["link_imovel"]."</a></td>
            <td>
            <form method='post' action=''>
                <input type='text' name='atualizacao' placeholder='Digite a atualização'>
                <select name='coluna'>
                    <option value='tipo_imovel'>Tipo do Imóvel</option>
                    <option value='c_a_imovel'>C/A</option>
                    <option value='m2_imovel'>M²</option>
                    <option value='cidade_imovel'>Cidade</option>
                    <option value='corpo_msg_imovel'>Corpo da Mensagem</option>
                    <option value='preco_imovel'>Preço</option>
                    <option value='link_imovel'>Link</option>
                </select>
                <input type='hidden' name='id_imovel' value='".$row["id_imovel"]."'>
                <button type='submit' value='atualizar' name='atualizar' class='btn btn-secondary btn-sm'>Atualizar</button>
                <button type='submit' value='excluir' name='excluir' class='btn btn-danger btn-sm'>Excluir</button>
                <button type='submit' value='atualizar' name='atualizar' class='btn btn-secondary btn-sm'>Enviar Alerta</button>
            </form>
            </td>
        </tr>";
        $i++;
    }
    // fecha a tabela
    echo "</table>";
} else {
    echo "0 resultados";
}



$conn->close();
?>