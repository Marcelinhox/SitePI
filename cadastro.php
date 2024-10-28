<?php
session_start();

// Conexão com o banco de dados
$conexao = new mysqli("localhost", "root", "", "loja_jogos");

if ($conexao->connect_error) {
    die("Conexão falhou: " . $conexao->connect_error);
}

// Inclua a biblioteca de alertas
include 'alert.php';

// Inicializa os dados do cadastro na sessão
$_SESSION['cadastro_data'] = [
    'nome' => '',
    'usuario' => '',
    'email' => '',
    'telefone' => '',
];

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados do formulário
    $nome = trim($_POST['nome']);
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);
    $confirmar_senha = trim($_POST['confirmar_senha']);
    $email = trim($_POST['email']);
    $confirmar_email = trim($_POST['confirmar_email']);
    $telefone = trim($_POST['telefone']);

    // Armazena dados para exibição em caso de erro
    $dadosPreenchidos = [
        'nome' => $nome,
        'usuario' => $usuario,
        'email' => $email,
        'telefone' => $telefone,
    ];

    // Validações
    $hasError = false;

    // Verifica se os campos estão preenchidos
    if (empty($usuario) || empty($senha) || empty($confirmar_senha) || empty($email) || empty($confirmar_email)) {
        $_SESSION['error'] = 'Por favor, preencha todos os campos obrigatórios.';
        $hasError = true;
    }

    // Verifica se as senhas são iguais
    if ($senha !== $confirmar_senha) {
        $_SESSION['error'] = 'As senhas não coincidem.';
        $hasError = true;
    }

    // Verifica se os emails são iguais
    if ($email !== $confirmar_email) {
        $_SESSION['error'] = 'Os emails não coincidem.';
        $hasError = true;
    }

    // Verifica se o usuário já existe
    $stmtCheck = $conexao->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = ?");
    $stmtCheck->bind_param("s", $usuario);
    $stmtCheck->execute();
    $stmtCheck->bind_result($count);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($count > 0) {
        $_SESSION['error'] = 'Este usuário já está cadastrado.';
        $hasError = true;
    }

    // Verifica se o email já existe
    $stmtCheckEmail = $conexao->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
    $stmtCheckEmail->bind_param("s", $email);
    $stmtCheckEmail->execute();
    $stmtCheckEmail->bind_result($countEmail);
    $stmtCheckEmail->fetch();
    $stmtCheckEmail->close();

    if ($countEmail > 0) {
        $_SESSION['error'] = 'Este email já está cadastrado.';
        $hasError = true;
    }

    // Se houver erro, armazena os dados e redireciona
    if ($hasError) {
        $_SESSION['cadastro_data'] = $dadosPreenchidos; // Armazena os dados preenchidos
        header("Location: index.php");
        exit();
    }

    // Se não houver erro, insere o novo usuário
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = $conexao->prepare("INSERT INTO usuarios (nome, usuario, senha, email, telefone) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nome, $usuario, $senhaHash, $email, $telefone);

    if ($stmt->execute()) {
        // Inicia a sessão e define a variável de sessão
        $_SESSION['nome'] = $nome;
        $_SESSION['tipo_usuario'] = 0; // Por exemplo, 0 para usuários comuns

        // Limpa os dados do cadastro
        unset($_SESSION['cadastro_data']);

        // Redireciona para o index.php
        header("Location: index.php");
        exit();
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
}

$conexao->close();
?>
