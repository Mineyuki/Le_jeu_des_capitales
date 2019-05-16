<?php
    session_start();
    require_once('identificationBDD.php');

    if($_SESSION['connected'] and
        isset($_POST['point']) and
        isset($_POST['game']))
    {
        $game = securisation($_POST['game']);
        $point = securisation($_POST['point']);

        $query = $bd->prepare("SELECT * FROM game WHERE name_game = :game");
        $query->bindValue(':game',$game);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        $request = $bd->prepare("INSERT INTO score (id_member, point) VALUES (:id, :point)");
        $request->bindValue(':id',$_SESSION['id_member']);
        $request->bindValue(':point',$point);
        $request->execute();

        $request = $bd->prepare("SELECT id_score FROM score WHERE id_member = :id AND score_date IN (SELECT max(score_date) FROM score WHERE id_member = :id)");
        $request->bindValue(':id',$_SESSION['id_member']);
        $request->execute();
        $res = $request->fetch(PDO::FETCH_ASSOC);

        $req = $bd->prepare("INSERT INTO from_game (id_score, id_game) VALUES (:score, :game)");
        $req->bindValue(':score',$res['id_score']);
        $req->bindValue(':game',$result['id_game']);
        $req->execute();
    }

    header('Location: index.html');
    exit();
?>