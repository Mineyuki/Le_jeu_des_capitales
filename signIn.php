<?php
    require_once('identificationBDD.php');

    if(isset($_POST['signInForm']) and
        trim($_POST['emailSignIn']) != "" and
        trim($_POST['password']) != "")
    {
        $email = htmlspecialchars($_POST['emailSignIn']);
        $password = sha1($_POST['password']);

        $request = $bd->prepare("SELECT * FROM member WHERE mail = :mail AND password = :password");
        $request->bindValue(':mail',$email);
        $request->bindValue(':password',$password);
        $request->execute();

        $result = $request->fetch(PDO::FETCH_ASSOC);

        if($result)
        {
            session_start();
            $_SESSION['connected'] = true;
            header('Location: index.html');
        }
        else
        {
            $messageSignIn = 'Mauvais adresse mail ou mot de passe!';
        }
    }
    else
    {
        $messageSignIn = 'Veuillez remplir les champs !';
    }
?>