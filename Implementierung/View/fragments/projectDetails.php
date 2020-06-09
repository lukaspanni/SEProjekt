<main class="container">
    <div class="row card-panel">
        <div class="col s12">
            <?php
            $user = User::loadFromSession();
            $isProjectManager = $user != null && $user->getUserId() == $this->model->getProjectManager();
            if ($isProjectManager) {
                echo '<a id="share-btn" class="btn-flat right"><i class="material-icons">share</i></a>';
                echo '<a id="toggle-edit" class="btn-flat right"><i class="material-icons">create</i></a>';
            }
            ?>
            <div id="project-information">
                <h1 id="name" data-type="text"><?php echo $this->model->getProjectName(); ?></h1>
                <h5 id="description" data-type="email"><?php echo $this->model->getProjectDescription(); ?></h5>
            </div>
            <div id="project-information-edit" style="display: none">
                <form action="/project/edit" method="POST">
                    <input type="hidden" name="id" value="<?php echo $this->model->getProjectId(); ?>"/>
                    <div class="input-fields">

                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="share_confirmation" class="modal">
        <div class="modal-content">
            <h3>Are You sure to share this project?</h3>
            <form class="col s12 m8" method="post" action="/project/share">
                <div class="row">
                    <input type="hidden" id="project_id" name="project_id"
                           value="<?php echo $this->model->getProjectId(); ?>"/>
                    <input type="date" id="expires_date" name="expires_date" required>
                    <input type="time" id="expires_time" name="expires_time" required>
                    <label for="expires_date">Expires</label>
                </div>
                <div class="row">
                    <button type="submit" class="btn waves-effect waves-light">
                        CONFIRM
                        <i class="material-icons right">done</i>
                    </button>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#" class="modal-close btn-flat">Close</a>
        </div>
    </div>
    </div>
    <?php
    if ($isProjectManager) {
        $teamMembers = $this->projectRepository->getMembers($this->model);
        ?>
        <div class="row" id="manage-team">
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Manage your Team</span>
                        <div class="row">
                            <table class="striped col s12 m6">
                                <caption><b>Current Team</b></caption>
                                <tr>
                                    <th>Id</th>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>email</th>
                                </tr>
                                <?php
                                foreach ($teamMembers as $user) {
                                    ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo "/user/id/" . $user->getUserId(); ?>"><?php echo $user->getUserId(); ?></a>
                                        </td>
                                        <td><?php echo $user->getFirstname(); ?></td>
                                        <td><?php echo $user->getLastname(); ?></td>
                                        <td><?php echo $user->getEmailAddress(); ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                            <form id="add_member" class="col s12 m6" action="/project/inviteMember" method="post">
                                <div class="center-align"><b>Add User to Team</b></div>
                                <input type="hidden" name="id" value="<?php echo $this->model->getProjectId(); ?>"/>
                                <input type="email" id="user_email" name="user_email" required/>
                                <label for="user_email">User Email</label>
                                <button type="submit" class="btn waves-effect waves-light right">
                                    OK
                                    <i class="material-icons right">done</i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if ($this->isShared || $isProjectManager) {
        $teamWorkingTime = $this->timesRepository->getByProject($this->model);
        if (count($teamWorkingTime->getEntries()) > 0) {
            ?>
            <div class="row" id="team-times">
                <div class="col s12">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title"><b>Team Stats</b></span>
                            <p>Working time: <?php echo minutesToTimeString($teamWorkingTime->getTotalMinutes()); ?></p>
                            <?php
                            $maxEntry = $teamWorkingTime->getEntries()[0];
                            $userTimes = array();
                            error_reporting(E_ALL & ~E_NOTICE); //Don't show notice for dynamically adding array indices
                            foreach ($teamWorkingTime->getEntries() as $entry) {
                                $userTimes[$entry->getUserId()] += $entry->getWorkingMinutes();
                                if ($entry->getWorkingMinutes() > $maxEntry->getWorkingMinutes()) {
                                    $maxEntry = $entry;
                                }
                            }
                            $maxUserTime = max($userTimes);
                            $mostActive = array_keys($userTimes, $maxUserTime)[0];
                            echo '<p>Max Working time: ' . minutesToTimeString($maxEntry->getWorkingMinutes()) . ' By User <a href="/user/id/' . $maxEntry->getUserId() . '">' . $maxEntry->getUserId() . '</a></p>';
                            echo '<p>Most active user: <a href="user/id/' . $mostActive . '">' . $mostActive . '</a> worked for a total of ' . minutesToTimeString($maxUserTime) . '</p>';
                            echo '<span id="user-total-time" class="json_hidden">'. json_encode($userTimes) .'</span>';
                            echo '<span id="team-time-entries" class="json_hidden">'.json_encode($teamWorkingTime->getEntries()).'</span>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <canvas id="user-total-time-chart"></canvas>
            </div>
            <div class="row">
                <canvas id="team-times-chart"></canvas>
            </div>
            <?php
        }
    }
    ?>
</main>
