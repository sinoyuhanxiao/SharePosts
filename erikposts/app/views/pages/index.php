<?php require APPROOT . '/views/inc/header.php'; ?>
    <div class="jumbotron jumbotron-fluid text-center">
        <div class="container">
            <div class="display-3"><h1><?php echo $data['title']; ?></h1></div>
            <p class="lead"><?php echo $data['description']; ?></p>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>