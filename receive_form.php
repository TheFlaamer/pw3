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

        //inclue os arquivos
        require_once "connection.php";
        include 'jwtclass.php';

        //define os objetos
        $conn = new Conn();
        $sql = $conn->connect();
        $jwt = new MyJWT();

        //recebe os dados
        $user = $_POST['user'];
        $pass = $_POST['password'];

        //valida se existem os dados
        if(empty($user) || empty($pass)) {
            echo "<h1>Campos vazios</h1>";
        } else {

            $q = "SELECT * FROM usuarios WHERE idusuario='$user' AND senhausuario='$pass' ";
            $result = $sql->query($q);
            if ($result->num_rows > 0) {
                $result = $result->fetch_assoc();
                print_r($result);
                $exp = time() + 60;
                $access_payload = [
                    "iss"=>"localhost",
                    "token"=>"access_token",
                    "id"=>$result['codusuario'],
                    "nome"=> $result['nomeusuario'],
                    "email"=>$result['email'],
                    "exp"=>$exp
                ];

                $access_token = $jwt->criaToken($access_payload);

                $exp = time() + 120;
                $refresh_payload = [
                    "iss"=>"localhost",
                    "token"=>"refresh_token",
                    "id"=>$result['codusuario'],
                    "nome"=> $result['nomeusuario'],
                    "email"=>$result['email'],
                    "exp"=>$exp
                ];

                $refresh_token = $jwt->criaToken($refresh_payload);

                echo "<h2>Tokens:</h2>";

                echo "<p>Access_token: $access_token</p>";
                echo "<p>Refresh_token: $refresh_token</p><br>";

                echo "<h2>Validade do token</h2><ul><li>se <strong>1</strong> = válido<br></li><li>se <strong>0</strong> = expirado ou inválido</li></ul>";

                echo "<p>Validade access: <strong>".$jwt->validaToken($access_token,true)."</strong></p>";
                echo "<p>Validade refresh: <strong>".$jwt->validaToken($refresh_token, true)."</strong></p>";

                //$refresh_signature=$jwt->extractToken($refresh_token, 2);
                // $q = "UPDATE usuarios SET token='$refresh_signature' WHERE idusuario='$user'";
                // print_r($q);
            } else {
                echo "usuário ou senha incorretos";
            }
        }
    ?>
</body>
</html>
