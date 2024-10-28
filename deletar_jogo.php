<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] != 1) {
    header("Location: login.php");
    exit();
}

$conexao = new mysqli("localhost", "root", "", "loja_jogos");

if ($conexao->connect_error) {
    die("Conexão falhou: " . $conexao->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para deletar o jogo
    $sql = "DELETE FROM jogos WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<p>Jogo deletado com sucesso!</p>";
        header("Location: listar_jogos.php"); // Redireciona para a lista de jogos
        exit();
    } else {
        echo "<p>Erro ao deletar jogo: " . $stmt->error . "</p>";
    }

    $stmt->close();
} else {
    echo "ID do jogo não fornecido!";
    exit();
}

$conexao->close();
?>
