<?php
    session_start();

    require_once('head.php');
    require_once('identificationBDD.php');

    if($_SESSION['connected'])
    {
?>
    <div class="container">
        <div class="well well-lg">
            <p class="text-center">Score</p>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <td>Score</td>
                    <td>Date</td>
                </tr>
            </thead>
            <tbody>
            <?php
                $limit = 5;
                /*How may adjacent page links should be shown on each side of the current page link.*/
                $adjacents = 2;
                $request = $bd->prepare("SELECT COUNT(*) 'total_rows' FROM score WHERE id_member = :id");
                $request->bindValue(':id', $_SESSION['id_member']);
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

                $query = $bd->prepare("SELECT * FROM score WHERE id_member := id ORDER BY point DESC LIMIT $offset, $limit");
                $request->bindValue(':id', $_SESSION['id_member']);
                $query->execute();
                while($row = $query->fetch(PDO::FETCH_ASSOC))
                {
                    echo '<tr>';
                    echo '<td>'.$row['point'].'</td>';
                    echo '<td>'.$row['score_date'].'</td>';
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
                        <a class='page-link' href='score.html?page=1'><<</a>
                    </li>
                    <li class='page-item <?php ($page <= 1 ? print 'disabled' : '')?>'>
                        <a class='page-link' href='score.html?page=<?php ($page>1 ? print($page-1) : print 1)?>'><</a>
                    </li>
                    <!-- Links of the pages with page number -->
                    <?php for($i=$start; $i<=$end; $i++) { ?>
                        <li class='page-item <?php ($i == $page ? print 'active' : '')?>'>
                            <a class='page-link' href='score.html?page=<?php echo $i;?>'><?php echo $i;?></a>
                        </li>
                    <?php } ?>
                    <!-- Link of the next page -->
                    <li class='page-item <?php ($page >= $total_pages ? print 'disabled' : '')?>'>
                        <a class='page-link' href='score.html?page=<?php ($page < $total_pages ? print($page+1) : print $total_pages)?>'>></a>
                    </li>
                    <!-- Link of the last page -->
                    <li class='page-item <?php ($page >= $total_pages ? print 'disabled' : '')?>'>
                        <a class='page-link' href='score.html?page=<?php echo $total_pages;?>'>>>
                        </a>
                    </li>
                </ul>
            <?php } ?>
            <div class="col-md-3 col-lg-3"></div>
        </div>
    </div>
    <script>document.title = 'Score';</script>

<?php
    }
    else
    {
        header('Location: index.html');
        exit();
    }
    require_once('footer.php');
?>
