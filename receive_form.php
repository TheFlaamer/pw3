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

        $user = $_POST['user'];
        $pass = $_POST['password'];

        if(empty($user) || empty($pass)) {
            echo "Campos vazios";
        } else {
            $q = "SELECT * FROM usuarios WHERE idusuario='$user' AND senhausuario='$pass' ";
            $result = $sql->query($q);
            if ($result->num_rows > 0) {
                $result = $result->fetch_assoc();
                $payload = [
                    "iss"=>"localhost",
                    "nome"=> $result['nomeusuario'],
                    "email"=>$result['email']
                ];
                // print_r($payload);
                $jwt = new MyJWT();
                $token = $jwt->criaToken($payload, "token");

                //validação do token
                $jwt = $jwt->validaToken($token);
            } else {
                echo "usuário ou senha incorretos";
            }
        }
    ?>
</body>
</html>
