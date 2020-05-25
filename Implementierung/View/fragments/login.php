<main id="login_container" class="container" <?php if ($this->register) echo 'style="display:none"'; ?>>
    <div class="row">
        <div class="col s12">
            <h3>Login</h3>
        </div>
    </div>
    <?php
    if($this->loginError){
        ?>
        <div id="login_error" class="card-panel red">Error during login</div>
        <?php
    }
    ?>
    <div class="row">
        <form class="col s12" id="login-form" method="post" action="/authentication/login">
            <div class="row">
                <div class="input-field col s12">
                    <input type="email" id="user_email" name="user_email" required value="<?php echo $this->email; ?>"/>
                    <label for="user_email">Email</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input type="password" id="user_password" name="user_password" required
                           value="<?php echo $this->password; ?>"/>
                    <label for="user_password">Passwort</label>
                </div>
            </div>
            <button type="submit" class="btn waves-effect waves-light">
                Login
                <i class="material-icons right">exit_to_app</i>
            </button>
            <a style="background-color:lightpink" id="register" class="waves-effect waves-light btn">
                Registrieren
                <i class="material-icons right">person_add</i>
            </a>
        </form>
    </div>
</main>
<main id="register_container" class="container" <?php if ($this->register) echo 'style="display:block"'; ?>>
    <div class="row">
        <div class="col s12">
            <h3>Registrieren</h3>
        </div>
    </div>
    <?php
    if($this->registerError){
        ?>
        <div id="register_error" class="card-panel red">Error during registration</div>
        <?php
    }
    ?>
    <div class="row">
        <form class="col s12" id="login-form" method="post" action="/authentication/register">
            <div class="row">
                <div class="input-field col s12">
                    <input type="email" id="user_email" name="user_email" required value="<?php echo $this->email; ?>"/>
                    <label for="user_email">Email</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6">
                    <input type="text" id="user_firstname" name="user_firstname" required value="<?php echo $this->firstname; ?>"/>
                    <label for="user_firstname">Vorname</label>
                </div>
                <div class="input-field col s6">
                    <input type="text" id="user_lastname" name="user_lastname" required value="<?php echo $this->lastname; ?>"/>
                    <label for="user_lastname">Nachname</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input type="password" id="user_password" name="user_password" required value="<?php echo $this->password; ?>"/>
                    <label for="user_password">Passwort</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <input type="password" id="user_password_retype" name="user_password_retype" required/>
                    <label for="user_password_retype">Passwort wiederholen</label>
                </div>
            </div>
            <button type="submit" class="btn waves-effect waves-light">
                Registrieren
                <i class="material-icons right">person_add</i>
            </button>
            <a style="background-color:lightcoral" id="cancel_register" class="waves-effect waves-light btn">
                Abbrechen
                <i class="material-icons right">cancel</i>
            </a>
        </form>
    </div>
</main>