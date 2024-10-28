<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
$conexao = new mysqli("localhost", "root", "", "loja_jogos");

if ($conexao->connect_error) {
    die("Conexão falhou: " . $conexao->connect_error);
}

// Consulta para listar jogos
$sql = "SELECT * FROM jogos";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Jogos - Loja de Jogos</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <h2>Lista de Jogos</h2>
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Descrição</th>
                <th>Desenvolvedora</th>
                <th>Faixa Etária</th>
                <th>Valor</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['titulo']; ?></td>
                        <td><?php echo $row['descricao']; ?></td>
                        <td><?php echo $row['desenvolvedora']; ?></td>
                        <td><?php echo $row['faixa_etaria']; ?></td>
                        <td><?php echo $row['valor']; ?></td>
                        <td>
                            <a href="editar_jogo.php?id=<?php echo $row['id']; ?>">Editar</a> | 
                            <a href="deletar_jogo.php?id=<?php echo $row['id']; ?>">Deletar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Nenhum jogo encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="adicionar_jogo.php">Adicionar Novo Jogo</a>
</body>
</html>

<?php $conexao->close(); ?>
