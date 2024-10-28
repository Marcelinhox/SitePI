<?php
session_start();

// Verifica se o usuário está logado e se é administrador
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

// Verifica se o ID do jogo foi passado
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Busca os dados do jogo
    $sql = "SELECT * FROM jogos WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $jogo = $result->fetch_assoc();

    if (!$jogo) {
        echo "Jogo não encontrado!";
        exit();
    }

    // Atualiza os dados do jogo se o formulário for enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $desenvolvedora = $_POST['desenvolvedora'];
        $faixa_etaria = $_POST['faixa_etaria'];
        $valor = $_POST['valor'];
        $data_lancamento = $_POST['data_lancamento'];
        $plataformas_compra = $_POST['plataformas_compra'];
        $desenvolvedores = $_POST['desenvolvedores'];
        $requisitos_minimos = $_POST['requisitos_minimos'];
        $sinopse_jogabilidade = $_POST['sinopse_jogabilidade'];
        $generos = $_POST['generos'];

        // Atualiza o jogo no banco de dados
        $sql = "UPDATE jogos SET titulo = ?, descricao = ?, desenvolvedora = ?, faixa_etaria = ?, valor = ?, data_lancamento = ?, plataformas_compra = ?, desenvolvedores = ?, requisitos_minimos = ?, sinopse_jogabilidade = ?, generos = ? WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sssssssssssi", $titulo, $descricao, $desenvolvedora, $faixa_etaria, $valor, $data_lancamento, $plataformas_compra, $desenvolvedores, $requisitos_minimos, $sinopse_jogabilidade, $generos, $id);

        if ($stmt->execute()) {
            header("Location: gerenciar_jogos.php?status=success"); // Redireciona para a lista de jogos
            exit();
        } else {
            echo "<p>Erro ao atualizar jogo: " . $stmt->error . "</p>";
        }
    }

    $stmt->close();
} else {
    echo "ID do jogo não fornecido!";
    exit();
}

$conexao->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Jogo - Loja de Jogos</title>
    <link rel="stylesheet" href="css/editar_jogo.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap">
</head>
<body>
    <header>
        <h1>Editar Jogo</h1>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="gerenciar_jogos.php">Gerenciar Jogos</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <form method="POST" action="">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($jogo['titulo']); ?>" required>

            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" name="descricao" value="<?php echo htmlspecialchars($jogo['descricao']); ?>" required>

            <label for="desenvolvedora">Desenvolvedora:</label>
            <input type="text" id="desenvolvedora" name="desenvolvedora" value="<?php echo htmlspecialchars($jogo['desenvolvedora']); ?>" required>

            <label for="faixa_etaria">Faixa Etária:</label>
            <input type="text" id="faixa_etaria" name="faixa_etaria" value="<?php echo htmlspecialchars($jogo['faixa_etaria']); ?>" required>

            <label for="valor">Valor:</label>
            <input type="number" id="valor" name="valor" value="<?php echo htmlspecialchars($jogo['valor']); ?>" required>

            <label for="data_lancamento">Data de Lançamento:</label>
            <input type="date" id="data_lancamento" name="data_lancamento" value="<?php echo htmlspecialchars($jogo['data_lancamento']); ?>">

            <label for="plataformas_compra">Plataformas de Compra (links):</label>
            <input type="text" id="plataformas_compra" name="plataformas_compra" value="<?php echo htmlspecialchars($jogo['plataformas_compra']); ?>">

            <label for="desenvolvedores">Desenvolvedores:</label>
            <input type="text" id="desenvolvedores" name="desenvolvedores" value="<?php echo htmlspecialchars($jogo['desenvolvedores']); ?>">

            <label for="requisitos_minimos">Requisitos Mínimos:</label>
            <textarea id="requisitos_minimos" name="requisitos_minimos"><?php echo htmlspecialchars($jogo['requisitos_minimos']); ?></textarea>

            <label for="sinopse_jogabilidade">Sinopse e Jogabilidade:</label>
            <textarea id="sinopse_jogabilidade" name="sinopse_jogabilidade"><?php echo htmlspecialchars($jogo['sinopse_jogabilidade']); ?></textarea>

            <label for="generos">Gêneros:</label>
            <input type="text" id="generos" name="generos" value="<?php echo htmlspecialchars($jogo['generos']); ?>">

            <button type="submit">Atualizar Jogo</button>
        </form>
    </main>
    
    <footer>
        <p>&copy; 2024 Loja de Jogos</p>
    </footer>
</body>
</html>
