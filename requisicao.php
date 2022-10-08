<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
        require_once "connection.php";
        include 'jwtclass.php';
        $conn = new Conn();
        $sql = $conn->connect();
        $jwt = new MyJWT();

        $token = $_POST['token'];

        if($token) {
            $valida = $jwt->validaToken($token, false);
            if($valida == true) {
                $payload = $jwt->extractToken($token, 1);
                $validaTempo = $jwt->validaToken($token, true);
                if($validaTempo == true) {
                    echo "<p>continue navegando!</p>";
                    echo "<a href='./index.html'>voltar</a>";
                } else {
                    echo "<h2>Token expirado</h2>";
                    $insertBlacklist = $jwt->insertBlackList($token, $payload['id']);
                    $newExp = time() + 3000;
                    $payload['exp'] = $newExp;
                    $newToken = $jwt->criaToken($payload);
                    echo "<h2>Novo token</h2>";
                    echo "<p>$newToken</p>";
                }
            } else {
                echo "<h2>Este não é um token válido!</h2>";
            }
        } else {
            echo "<h1>Token não enviado</h1>";
        }
    ?>
</body>
</html>
