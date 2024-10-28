<?php
// Conexão com o banco de dados
$conexao = new mysqli("localhost", "root", "", "loja_jogos");

if ($conexao->connect_error) {
    die("Conexão falhou: " . $conexao->connect_error);
}

// Verifica se o formulário foi submetido usando o método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se os campos estão definidos antes de atribuí-los
    $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : '';
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';
    $desenvolvedora = isset($_POST['desenvolvedora']) ? $_POST['desenvolvedora'] : '';
    $faixa_etaria = isset($_POST['faixa_etaria']) ? $_POST['faixa_etaria'] : '';
    $valor = isset($_POST['valor']) ? $_POST['valor'] : '';
    $data_lancamento = isset($_POST['data_lancamento']) ? $_POST['data_lancamento'] : '';
    $plataformas_compra = isset($_POST['plataformas_compra']) ? $_POST['plataformas_compra'] : '';
    $desenvolvedores = isset($_POST['desenvolvedores']) ? $_POST['desenvolvedores'] : '';
    $requisitos_minimos = isset($_POST['requisitos_minimos']) ? $_POST['requisitos_minimos'] : '';
    $sinopse_jogabilidade = isset($_POST['sinopse_jogabilidade']) ? $_POST['sinopse_jogabilidade'] : '';
    $generos = isset($_POST['generos']) ? $_POST['generos'] : '';

    // Prepara a query de inserção
    $sql = $conexao->prepare("INSERT INTO jogos (titulo, descricao, desenvolvedora, faixa_etaria, valor, data_lancamento, plataformas_compra, desenvolvedores, requisitos_minimos, sinopse_jogabilidade, generos) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $sql->bind_param("sssssssssss", $titulo, $descricao, $desenvolvedora, $faixa_etaria, $valor, $data_lancamento, $plataformas_compra, $desenvolvedores, $requisitos_minimos, $sinopse_jogabilidade, $generos);

    if ($sql->execute()) {
        echo "Jogo adicionado com sucesso!";
    } else {
        echo "Erro ao adicionar jogo: " . $sql->error;
    }
}

// Fecha a conexão após o processo
$conexao->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Jogo - Loja de Jogos</title>
    <link rel="stylesheet" href="css/adicionar_jogo.css"> <!-- CSS específico para esta página -->
</head>
<body>
    <header>
        <h1>Adicionar Jogo</h1>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="gerenciar_jogos.php">Gerenciar Jogos</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    
    <main>
        <form method="POST" action="adicionar_jogo.php">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="descricao">Descrição:</label>
            <input type="text" id="descricao" name="descricao" required>

            <label for="desenvolvedora">Desenvolvedora:</label>
            <input type="text" id="desenvolvedora" name="desenvolvedora" required>

            <label for="faixa_etaria">Faixa Etária:</label>
            <input type="text" id="faixa_etaria" name="faixa_etaria" required>

            <label for="valor">Valor:</label>
            <input type="number" id="valor" name="valor" required>

            <label for="data_lancamento">Data de Lançamento:</label>
            <input type="date" id="data_lancamento" name="data_lancamento">

            <label for="plataformas_compra">Plataformas de Compra (links):</label>
            <input type="text" id="plataformas_compra" name="plataformas_compra">

            <label for="desenvolvedores">Desenvolvedores:</label>
            <input type="text" id="desenvolvedores" name="desenvolvedores">

            <label for="requisitos_minimos">Requisitos Mínimos:</label>
            <textarea id="requisitos_minimos" name="requisitos_minimos"></textarea>

            <label for="sinopse_jogabilidade">Sinopse e Jogabilidade:</label>
            <textarea id="sinopse_jogabilidade" name="sinopse_jogabilidade"></textarea>

            <label for="generos">Gêneros:</label>
            <input type="text" id="generos" name="generos">

            <button type="submit">Adicionar Jogo</button>
        </form>

        <?php
        // Mensagem de sucesso ou erro
        if (isset($_GET['status'])) {
            if ($_GET['status'] == 'success') {
                echo '<div class="message">Jogo adicionado com sucesso!</div>';
            } elseif ($_GET['status'] == 'error') {
                echo '<div class="message">Erro ao adicionar jogo!</div>';
            }
        }
        ?>
    </main>
    
    <footer>
        <p>&copy; 2024 Loja de Jogos</p>
    </footer>
</body>
</html>

