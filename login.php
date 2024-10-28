<?php
session_start();

// Conexão com o banco de dados
$conexao = new mysqli("localhost", "root", "", "loja_jogos");

if ($conexao->connect_error) {
    die("Conexão falhou: " . $conexao->connect_error);
}

// Inclua a biblioteca de alertas
include 'alert.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário
    $usuario = $_POST['usuario'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Verifica se os campos estão preenchidos
    if (!empty($usuario) && !empty($senha)) {
        // Prepara a consulta para buscar o usuário
        $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE nome = ?");
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica se o usuário existe
        if ($result->num_rows > 0) {
            $usuario_db = $result->fetch_assoc();
            // Verifica a senha
            if (password_verify($senha, $usuario_db['senha'])) {
                // Login bem-sucedido
                $_SESSION['nome'] = $usuario_db['nome'];
                $_SESSION['tipo_usuario'] = $usuario_db['tipo_usuario'];
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['error'] = 'Senha incorreta.';
            }
        } else {
            $_SESSION['error'] = 'Usuário não encontrado.';
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = 'Por favor, preencha todos os campos.';
    }

    // Redireciona para o index.php
    header("Location: index.php");
    exit();
}

$conexao->close();
?>
