<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php require('partials/head.php'); ?>
    <title>Alterar senha</title>
</head>
<body>
    <?php
        require('controller/loginChecker.php');
        require('partials/navbar.php');

    ?>
    <div class="container">
        <p>
            <h2>Alterar senha</h2>
        </p>
        <?php 
            $formCurrentPassword = isset($_POST['formCurrentPassword'])?$_POST['formCurrentPassword']:"";
            $formNewPassword = isset($_POST['formNewPassword'])?$_POST['formNewPassword']:"";
            $formNewPassword2 = isset($_POST['formNewPassword2'])?$_POST['formNewPassword2']:"";
            $loggedUserId = $_SESSION['loggedUserId'];
            $screenMessage = "";

            if(isset($formCurrentPassword) && $formCurrentPassword !== ""){
                require('config/connection.php');
                $sql = "select * from `users` where `id` = \"$loggedUserId\"";
                $result = mysqli_query($conn, $sql);

                if($result->num_rows > 0) {
                    foreach($result as $row){
                        $dbCurrentPassword = $row['password'];
                    }

                    if(password_verify($formCurrentPassword, $dbCurrentPassword)){

                        if($formNewPassword !== "" && $formNewPassword2 !== ""){

                            if($formNewPassword === $formNewPassword2){

                                $passwordHash = password_hash($formNewPassword2, PASSWORD_DEFAULT);
                                
                                $sql = "update `users` set `password`=\"$passwordHash\" where `id` = \"$loggedUserId\"";
                                $result = mysqli_query($conn, $sql);
                                
                                if($result) {
                                    $screenMessage = "<div class=\"alert alert-success\">Senha atualizada com sucesso</div>";
                                } else {
                                    $screenMessage = "<div class=\"alert alert-success\">Erro na atualização. Contate o administrador do sistema</div>";
                                }

                            } else {
                                $screenMessage = "<div class=\"alert alert-danger\">As novas senhas não conferem. Senha não atualizada</div>";
                                $newPasswordFormStyle = "is-invalid";
                            }

                        } else {
                            $screenMessage = "<div class=\"alert alert-danger\">Favor preencher as novas senhas corretamente</div>";
                            $newPasswordFormStyle = "is-invalid";
                        }
                        
                    } else {
                        $screenMessage = "<div class=\"alert alert-danger\">A senha atual não está correta. Por favor tente novamente</div>";    
                        $currentPasswordFormStyle = "is-invalid";
                    }
                    
                } else {
                    $screenMessage = "<div class=\"alert alert-danger\">Seu usuário não foi encontrado no banco de dados. Por favor contate o administrador do sistema</div>";
                }

                // $screenMessage = "<div class=\"alert alert-success\">Senha preenchida. User id: $loggedUserId</div>";
                
                $conn->close();
                
            }
        ?>

        <?= $screenMessage; ?>
        
        <form action="" method="post">
            <div class="input-group mb-3">
                <span class="input-group-text col-sm-3 col-lg-2 col-3 is-invalid">Senha atual</span>
                <input type="password" class="form-control <?= $currentPasswordFormStyle ?>" placeholder="Digite a senha atual" name="formCurrentPassword" required>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text col-sm-3 col-lg-2 col-3">Nova Senha</span>
                <input type="password" class="form-control <?= $newPasswordFormStyle ?>" placeholder="Digite a nova senha" name="formNewPassword" required>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text col-sm-3 col-lg-2 col-3">Confirmar Senha</span>
                <input type="password" class="form-control <?= $newPasswordFormStyle ?>" placeholder="Digite a nova senha novamente para confirmar" name="formNewPassword2" required>
            </div>
            <button type="submit" class="btn btn-primary mb-3">Alterar</button>
        </form>

    </div>
</body>
</html>