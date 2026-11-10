<?php
//Cabeçalho com algumas configurações
// Define a resposta da API, nesse caso em JSON
header("Content-Type:application/json;charset=utf-8");
// Permite o acesso à API de todas as origens
header("Acess-Control-Allow-Origin: *");
//Permite os métodos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

require "config.php";

$rota = $_GET["rota"] ?? "";
$metodo = $_SERVER["REQUEST_METHOD"];

$corpo = json_decode(file_get_contents("php://input"), true) ?? [];

function teste(){
    echo json_encode(["mensagem" => "Back-end do instagram respondendo!"]);
    exit;
}

// CRUD - Usuário
//CREATE
function createUsuarios($con, $dados){
    $stmt = $con->prepare(
        "INSERT INTO USUARIOS (NOME, USERNAME, EMAIL, SENHA, DATA_CADASTRO)
        VALUES (?,?,?,?,?)"
    );
    $stmt->execute([$dados["nome"], $dados["username"], $dados["email"], $dados["senha"], date("Y-m-d H:i:s")]);
    http_response_code(201);
    echo json_encode(["mensagem" => "Usuário criado com sucesso", "id" => $con->lastInsertId()]
    );
}

//READ
function readUsuarios($con) {
    $stmt = $con->query("SELECT * FROM USUARIOS");
    echo json_encode($stmt->fetchALL(PDO::FETCH_ASSOC));
}
//UPDATE
function updateUsuarios($con, $id, $dados){
    $stmt = $con->prepare(
        "UPDATE USUARIOS SET nome = ?, username = ?, email = ?, senha = ? 
        WHERE id_usuario = ?"
    );
    $stmt->execute([$dados["nome"], $dados["username"], $dados["email"], $dados["senha"], $id]);
    echo json_encode(["mensagem" => "Usuário atualizado com sucesso!"]);
}
//DELETE
function deleteUsuarios($con, $id){
    $stmt = $con->prepare("DELETE FROM USUARIOS WHERE id_usuario = ?");
    $stmt->execute([$id]);
    echo json_encode(["mensagem" => "Usuário removido com sucesso!"]);
}



// CRUD - Publicações
//CREATE
function createPublicacao($con, $dados){
    $stmt = $con->prepare(
        "INSERT INTO PUBLICACAO (DESCRICAO, IMAGEM, DATA_PUBLICACAO)
        VALUES (?,?,?)"
    );
    $stmt->execute([$dados["legenda"], $dados["imagem_url"], $dados["data_publicacao"]]);
    http_response_code(201);
    echo json_encode(["mensagem" => "Publicação criada com sucesso", "id" => $con->lastInsertId()]
    );
}

//READ
function readPublicacao($con) {
    $stmt = $con->query("SELECT * FROM Publicacao");
    echo json_encode($stmt->fetchALL(PDO::FETCH_ASSOC));
}
//UPDATE
function updatePublicacao($con, $id, $dados){
    $stmt = $con->prepare(
        "UPDATE Publicacao SET legenda = ?, imagem_url = ?, data_publicacao = ? 
        WHERE idPublicacao = ?"
    );
    $stmt->execute([$dados["legenda"], $dados["imagem_url"], $dados["data_publicacao"], $id]);
    echo json_encode(["mensagem" => "Publicação atualizada com sucesso!"]);
}
//DELETE
function deletePublicacao($con, $id){
    $stmt = $con->prepare("DELETE FROM Publicacao WHERE idPublicacao = ?");
    $stmt->execute([$id]);
    echo json_encode(["mensagem" => "Publicação removida com sucesso!"]);
}


// CRUD - Curtidas
//CREATE
function createCurtida($con, $dados){
    $stmt = $con->prepare(
        "INSERT INTO CURTIDA (id_usuario, id_publicacao, data_curtida)
        VALUES (?,?,?)"
    );

    $stmt->execute([$dados["id_usuario"], $dados["id_publicacao"], $dados["data_curtida"]]);
    http_response_code(201);
    echo json_encode(["mensagem" => "Curtida criada com sucesso", "id" => $con->lastInsertId()]
    );
}

//READ
function readCurtida($con) {
    $stmt = $con->query("SELECT * FROM Curtida");
    echo json_encode($stmt->fetchALL(PDO::FETCH_ASSOC));
}
//UPDATE
function updateCurtida($con, $id, $dados){
    $stmt = $con->prepare(
        "UPDATE Curtida SET id_usuario = ?, id_publicacao = ?, data_curtida = ? 
        WHERE idCurtida = ?"
    );
    $stmt->execute([$dados["id_usuario"], $dados["id_publicacao"], $dados["data_curtida"], $id]);
    echo json_encode(["mensagem" => "Curtida atualizada com sucesso!"]);
}
//DELETE
function deleteCurtida($con, $id){
    $stmt = $con->prepare("DELETE FROM Curtida WHERE idCurtida = ?");
    $stmt->execute([$id]);
    echo json_encode(["mensagem" => "Curtida removida com sucesso!"]);
}

//ROTAS
if ($rota === "usuario"){
    switch($metodo){
        case "GET":
            readUsuarios($con);
            break;
        case "POST":
            createUsuarios ($con, $corpo);
            break;
        case "PUT":
            updateUsuarios($con, $_GET["id"], $corpo);
            break;
        case "DELETE":
            deleteUsuarios($con, $_GET["id"]);
            break;
        default:
        teste();
    }

}else if ($rota === "publicacao"){
            switch($metodo){
                case "GET":
                    readPublicacao($con);
                    break;
                case "POST":
                    createPublicacao($con, $corpo);
                    break;
                case "PUT":
                    updatePublicacao($con, $_GET["id"], $corpo);
                    break;
                case "DELETE":
                    deletePublicacao($con, $_GET["id"]);
                    break;
                default:
                teste();
    }
}
        else if ($rota === "curtida"){
            switch($metodo){
                case "GET":
                    readCurtida($con);
                    break;
                case "POST":
                    createCurtida($con, $corpo);
                    break;
                case "PUT":
                    updateCurtida($con, $_GET["id"], $corpo);
                    break;
                case "DELETE":
                    deleteCurtida($con, $_GET["id"]);
                    break;
                default:
                teste();
    }
        }