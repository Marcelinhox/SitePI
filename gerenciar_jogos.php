<?php
session_start();
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
$conexao = new mysqli("localhost", "root", "", "loja_jogos");

// Verifica se houve erro na conexão
if ($conexao->connect_error) {
    die("Conexão falhou: " . $conexao->connect_error);
}

// Consulta para buscar todos os jogos
$sql = "SELECT * FROM jogos";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Jogos - Loja de Jogos</title>
    <link rel="stylesheet" href="css/gerenciar_jogos.css"> <!-- CSS específico para esta página -->
</head>
<body>
    <header>
        <h1>Gerenciar Jogos</h1>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="adicionar_jogo.php">Adicionar Novo Jogo</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <div class="game-management-container">
            <h2>Jogos Cadastrados</h2>
            <table>
                <tr>
                    <th>Título</th>
                    <th>Faixa Etária</th>
                    <th>Valor</th>
                    <th>Ações</th>
                </tr>
                <?php
                if ($result->num_rows > 0) {
                    while ($jogo = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $jogo['titulo'] . "</td>";
                        echo "<td>" . $jogo['faixa_etaria'] . "</td>";
                        echo "<td>R$ " . $jogo['valor'] . "</td>";
                        echo "<td>";
                        echo "<a href='editar_jogo.php?id=" . $jogo['id'] . "'>Editar</a> | ";
                        echo "<a href='deletar_jogo.php?id=" . $jogo['id'] . "' onclick='return confirm(\"Deseja realmente excluir este jogo?\");'>Excluir</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Nenhum jogo cadastrado.</td></tr>";
                }
                ?>
            </table>
        </div>
    </main>
    
    <footer>
        <p>&copy; 2024 Loja de Jogos</p>
    </footer>
</body>
</html>

<?php
$conexao->close();
?>
