<?php
require "config.php";

$rota = $_GET["rota"] ?? ($_SERVER["REQUEST_METHOD"] === "POST" ? "adicionarclientes" : "teste");


function listarClientes($con) {
    $stmt = $con->query("SELECT * FROM clientes");
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

function adicionarcliente($con) {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $endereco = $_POST["endereco"];

    try {
        $stmt = $con->prepare(
            "INSERT INTO clientes (nome, email, telefone, endereco)
            VALUES (?, ?, ?, ?)"
        );

        $stmt->execute([
            $nome,
            $email,
            $telefone,
            $endereco
        ]);

        header("Location: ../front/index.html");
        exit;

    } catch (PDOException $e) {
        header("Location: ../front/erro.html");
        exit;
    }
}

switch ($rota) {
    case "clientes":
        listarClientes($con);
        break;

    case "adicionarclientes":
        adicionarcliente($con);
        break;

    case "teste":
        echo json_encode(["mensagem" => "API funcionando"]);
        break;

    default:
        echo json_encode(["erro" => "Rota não encontrada $rota"]);
        break;
}

