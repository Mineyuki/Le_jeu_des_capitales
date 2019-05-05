<?php
    require_once('identificationBDD.php');

    if(isset($_POST['form-vertical']) and
        trim($_POST['email']) != "" and
        trim($_POST['password']) != "")
    {
        $email = htmlspecialchars($_POST['email']);
        $password = sha1($_POST['password']);

        $request = $bd->prepare("SELECT * FROM member WHERE mail = :mail AND password = :password");
        $request->bindValue(':mail',$email);
        $request->bindValue(':password',$password);
        $request->execute();

        $result = $request->fetch();

        if($result)
        {
            echo '<p>Mauvais adresse mail ou mot de passe!</p>';
        }
        else
        {
            session_start();
            $_SESSION['email'] = $result['mail'];
            header('Location : index.html');
        }
    }
    else
    {
        echo '<p>Veuillez remplir les champs !</p>';
    }
?>