<?php
if($this->model == null){
    return_404_error(); // TODO: Error Display
    exit();
}
$loggedInUser = User::loadFromSession();
?>

<main class="container">
    <div class="row card-panel">
        <div class="col s12">
            <?php
            if($loggedInUser != null && $loggedInUser->getUserId() == $this->model->getUserId()){
                echo '<a id="toggle-edit" class="btn-flat right"><i class="material-icons">create</i></a>';
            }
            ?>
            <div id="user-information">
                <h1 id="name" data-type="text"><?php echo $this->model->getFullName(); ?></h1>
                <h5 id="email" data-type="email"><?php echo $this->model->getEmailAddress(); ?></h5>
                <?php
                if($loggedInUser != null && $loggedInUser->getUserId() == $this->model->getUserId()){
                    echo '<span>Break Reminder: </span><span id="breakReminder" data-type="number">'.$this->model->getBreakReminder().'</span><span> Minutes</span>';
                }
                ?>
            </div>
            <div id="user-information-edit" style="display: none">
                <form action="/user/edit" method="POST">

                </form>
            </div>
        </div>

    </div>
</main>
