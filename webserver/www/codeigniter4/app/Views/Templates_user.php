<?php 
    include('templates/header.php');
    include('templates/nav_user.php');
    echo $this->renderSection('content');
    include('templates/footer.php');
    include('templates/end.php');
?>