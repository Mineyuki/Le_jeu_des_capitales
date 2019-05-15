<?php
    require_once('header.php');
    if($_SESSION['role']=='administrateur')
    {
?>

    <div class="container" id="">
        <table class="table">
            <thead>
                <tr>
                    <td>Email</td>
                    <td>Date de création</td>
                    <td></td>
                </tr>
            </thead>
            <tbody id="tableUser">
            <?php
                require_once('identificationBDD.php');
                $request = $bd->prepare("SELECT * FROM member JOIN have_role USING (id_member)");
                $request->execute();

                while($row = $request->fetch(PDO::FETCH_ASSOC))
                {
                    echo '<tr>
                            <td>'.$row['mail'].'</td>
                            <td>'.$row['sign_in_date'].'</td>
                            <td><button type="button" class="btn btn-danger">Supprimer</button></td>
                        </tr>';
                }
            ?>
            </tbody>
        </table>
    </div>

<?php
    }
    else
    {
        header('Location: index.html');
    }

    require_once('footer.php');
?>