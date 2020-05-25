<main class="container">
    <div class="row">
        <div class="card-panel red">
            <h3><?php echo $this->model->getErrorType(); ?></h3>
            <h5><?php echo $this->model->getErrorMessage(); ?></h5>
            <?php
            if ($this->model->getErrorType() == ErrorType::LOGIN_ERROR) {
                ?>
                <a href="/authentication">
                    <button class="btn waves-effect waves-light"> Go to Login<i
                            class="material-icons right">exit_to_app</i>
                    </button>
                </a>
                <?php
            }
            ?>
        </div>
    </div>
</main>