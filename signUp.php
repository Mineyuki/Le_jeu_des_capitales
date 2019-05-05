<?php
    // Demande utilisation identificationBDD.php
    require_once('identificationBDD.php');

    if(isset($_POST['signUp']) and
        trim($_POST['email']) != "" and
        trim($_POST['password1']) != "" and
        trim($_POST['password2']) != "")
    {
        $email = htmlspecialchars($_POST['email']);
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

                    $message = 'Creation compte réussie';
                    header('Location: signIn.php');
                }
                else
                {
                    $message = 'Mot de passe différent !';
                }
            }
            else
            {
                $message = 'Compte existant !';
            }
        }
        else
        {
            $message = 'Adresse mail non valide !';
        }
    }
    else
    {
        $message = 'Veuillez renseigner les champs';
    }

    echo '<p>'.$message.'</p>';
?>