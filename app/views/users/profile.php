<?php require APPROOT . '/views/inc/header.php'; ?>
<?php flash('user_message'); ?>
<a href="<?php echo URLROOT; ?>/posts" class="btn btn-light">
        <i class="fa fa-backward"> Back</i>
        <a href="<?php echo URLROOT . '/users/editProfilePage' ?>" class="btn btn-primary pull-right">
                    <i class="fa fa-pencil"></i>
                    Edit
                    </a>
        </a>

    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card card-body bg-light mt-5">
                <div class="text-center">
                    <img src="../public/img/erik_yu_test.jpg" class="face-pic">
                </div>
                <h5 class="mx-auto mt-3" id='name'><?php echo $_SESSION['user_name'] ?></h5>
                <h5 class="mx-auto" id='email'><?php echo $_SESSION['user_email'] ?></h5>
                <div class="row">
                    <a href="#" class="btn btn-success col">Follow</a>
                    <a href="#" class="btn btn-primary col">view posts</a>
                    <a href="#" class="btn btn-danger col">Message</a>
                </div>
                <div class="card text-center mx-auto mt-3" style="">
                    <div class="card-body">
                        <h5 class="card-title">Personal Description</h5>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed vulputate ex dui, et aliquam diam vulputate viverra. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Suspendisse ipsum odio, lobortis mattis nisl ut, bibendum sodales quam. Donec id metus quis mi posuere interdum vitae eget massa. Suspendisse congue diam ac imperdiet molestie. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sed tortor vel massa luctus hendrerit sit amet vel magna.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require APPROOT . '/views/inc/footer.php'; ?>