<?php
    // Demande utilisation identificationBDD.php
    require_once('identificationBDD.php');

    if(isset($_POST['signUpForm']) and
        trim($_POST['emailSignUp']) != "" and
        trim($_POST['password1']) != "" and
        trim($_POST['password2']) != "")
    {
        $email = htmlspecialchars($_POST['emailSignUp']);
        $password1 = sha1($_POST['password1']);
        $password2 = sha1($_POST['password2']);

        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $request = $bd->prepare("SELECT * FROM member WHERE mail = :mail");
            $request->bindValue(':mail',$email);
            $request->execute();
            if(($request->rowCount()) == 0)
            {
                if($password1 == $password2)
                {
                    $insertUser = $bd->prepare("INSERT INTO member (mail, password) VALUES (:mail, :password)");
                    $insertUser->bindValue(':mail',$email);
                    $insertUser->bindValue(':password',$password1);
                    $insertUser->execute();

                    $getUser = $bd->prepare("SELECT id_member FROM member WHERE mail = :mail");
                    $getUser->bindValue(':mail',$email);
                    $getUser->execute();
                    $resultUser = $getUser->fetch(PDO::FETCH_ASSOC);

                    $getRole = $bd->prepare("SELECT id_role FROM role WHERE nom='utilisateur'");
                    $getRole->execute();
                    $resultRole = $getRole->fetch(PDO::FETCH_ASSOC);

                    $giveRole = $bd->prepare("INSERT INTO possede_role (id_member, id_role) VALUES (:member, :role)");
                    $giveRole->bindValue(':member',$resultUser['id_member']);
                    $giveRole->bindValue(':role',$resultRole['id_role']);
                    $giveRole->execute();

                    $messageSignUp = 'Creation compte réussie';
                    header('Location: index.html');
                }
                else
                {
                    $messageSignUp = 'Mot de passe différent !';
                }
            }
            else
            {
                $messageSignUp = 'Compte existant !';
            }
        }
        else
        {
            $messageSignUp = 'Adresse mail non valide !';
        }
    }
    else
    {
        $messageSignUp = 'Veuillez renseigner les champs !';
    }
?>