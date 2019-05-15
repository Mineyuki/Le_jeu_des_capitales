<?php
    /*
     * Documentation :
     * http://www.mitrajit.com/bootstrap-pagination-in-php-and-mysql/
     */
    require_once('head.php');
    if($_SESSION['role']=='administrateur')
    {
?>

    <div class="container">
        <div class="well well-lg">
            <p class="text-center">Utilisateurs</p>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <td>Email</td>
                    <td>Date de création</td>
                    <td>Privilège</td>
                    <td>Modifier</td>
                    <td>Supprimer</td>
                </tr>
            </thead>
            <tbody>
            <?php
                require_once('identificationBDD.php');
                $limit = 5;
                /*How may adjacent page links should be shown on each side of the current page link.*/
                $adjacents = 2;
                $request = $bd->prepare("SELECT COUNT(*) 'total_rows' FROM member");
                $request->execute();
                $total_rows = $request->fetch(PDO::FETCH_ASSOC);
                $total_pages = ceil($total_rows['total_rows']/$limit);

                if(isset($_GET['page']) && trim($_GET['page']) != "")
                {
                    $page = $_GET['page'];
                    $offset = $limit * ($page -1);
                }
                else
                {
                    $page = 1;
                    $offset = 0;
                }

                $query = $bd->prepare("SELECT * FROM member JOIN have_role USING (id_member) JOIN role USING (id_role) LIMIT $offset, $limit");
                $query->execute();
                while($row = $query->fetch(PDO::FETCH_ASSOC))
                {
                    echo '<tr>';
                    echo '<td>'.$row['mail'].'</td>';
                    echo '<td>'.$row['sign_in_date'].'</td>';
                    echo '<td>'.$row['nom'].'</td>';
                    echo '<td><a href="profil.html?id='.$row['id_member'].'"><span class="glyphicon glyphicon-cog"></span></a></td>';
                    echo '<td><a href="tableUser.html?id='.$row['id_member'].'"><span class="glyphicon glyphicon-remove"></span></a></td>';
                    echo '</tr>';
                }

                //Here we generates the range of the page numbers which will display.
                if($total_pages <= (1+($adjacents * 2)))
                {
                    $start = 1;
                    $end   = $total_pages;
                }
                else
                {
                    if(($page - $adjacents) > 1) {
                        if(($page + $adjacents) < $total_pages)
                        {
                            $start = ($page - $adjacents);
                            $end   = ($page + $adjacents);
                        }
                        else
                        {
                            $start = ($total_pages - (1+($adjacents*2)));
                            $end   = $total_pages;
                        }
                    }
                    else
                    {
                        $start = 1;
                        $end   = (1+($adjacents * 2));
                    }
                }
            ?>
            </tbody>
        </table>

        <div class="row">
            <div class="col-md-3 col-lg-3"></div>
            <?php if($total_pages > 1) { ?>
            <ul class="col-xs-12 col-sm-12 col-md-6 col-lg-6 pagination pagination-lg">
                <li class='page-item <?php ($page <= 1 ? print 'disabled' : '')?>'>
                    <a class='page-link' href='tableUser.html?page=1'><<</a>
                </li>
                <li class='page-item <?php ($page <= 1 ? print 'disabled' : '')?>'>
                    <a class='page-link' href='tableUser.html?page=<?php ($page>1 ? print($page-1) : print 1)?>'><</a>
                </li>
                <!-- Links of the pages with page number -->
                <?php for($i=$start; $i<=$end; $i++) { ?>
                    <li class='page-item <?php ($i == $page ? print 'active' : '')?>'>
                        <a class='page-link' href='tableUser.html?page=<?php echo $i;?>'><?php echo $i;?></a>
                    </li>
                <?php } ?>
                <!-- Link of the next page -->
                <li class='page-item <?php ($page >= $total_pages ? print 'disabled' : '')?>'>
                    <a class='page-link' href='tableUser.html?page=<?php ($page < $total_pages ? print($page+1) : print $total_pages)?>'>></a>
                </li>
                <!-- Link of the last page -->
                <li class='page-item <?php ($page >= $total_pages ? print 'disabled' : '')?>'>
                    <a class='page-link' href='tableUser.html?page=<?php echo $total_pages;?>'>>>
                    </a>
                </li>
            </ul>
            <?php } ?>
            <div class="col-md-3 col-lg-3"></div>
        </div>
    </div>

<?php
        if(isset($_GET['id']) && trim($_GET['id']) != "")
        {
            $request = $bd->prepare("DELETE FROM have_role WHERE id_member = :id_member");
            $request->bindValue(':id_member',$_GET['id']);
            $request->execute();

            $request = $bd->prepare("DELETE FROM score WHERE id_member = :id_member");
            $request->bindValue(':id_member',$_GET['id']);
            $request->execute();

            $request = $bd->prepare("DELETE FROM member WHERE id_member = :id_member");
            $request->bindValue(':id_member',$_GET['id']);
            $request->execute();

            header('Location: header.html?url=tableUser.html');
            exit();
        }
    }
    else
    {
        header('Location: index.html');
        exit();
    }
?>

    <script>document.title = 'Paramètre utilisateurs';</script>

<?php
    require_once('footer.php');
?>