<?php
if (isset($_SESSION["activeProject"])) {
    $activeProject = unserialize($_SESSION["activeProject"]);
} else {
    $activeProject = null;
}
?>
<main class="container" data-activeid="<?php if ($activeProject != null) echo $activeProject->getProjectId(); ?>">
    <?php
    if ($this->openInvitations != null) {
        echo '<div class="row invitation-container"><h2>Open Invitations:</h2>';
        foreach ($this->openInvitations as $project) {
            ?>
            <div class="col s12 m4">
                <div class="card invitation">
                    <div class="card-content">
                        <span class="card-title">You are invited to work on: <?php echo $project->getProjectName(); ?></span>
                        <p class="card-content"><?php echo $project->getProjectDescription(); ?></p>
                    </div>
                    <div class="card-action">
                        <a href="/project/invitation/<?php echo $project->getProjectId(); ?>/accept">Accept</a>
                        <a href="/project/invitation/<?php echo $project->getProjectId(); ?>/decline">Decline</a>
                    </div>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    }
    echo '<h2>Recent Projects</h2>';
    projectListSummary($this->recent, $activeProject, $this->activeEntry);
    if (count($this->acceptedProjects) > 0) {
        echo '<hr><h2>Accepted Projects</h2>';
        $this->acceptedProjects = array_diff($this->acceptedProjects, $this->recent);
        projectListSummary($this->acceptedProjects, $activeProject, $this->activeEntry);
    }
    if (count($this->managerProjects) > 0) {
        echo '<hr><h2>Project Manager Projects</h2>';
        projectListSummary($this->managerProjects, $activeProject, $this->activeEntry);
    }
    ?>
</main>

<?php

function projectListSummary($projects, $activeProject, $activeEntry)
{
    $counter = 0;
    $rowClosed = true;
    foreach ($projects as $summary) {
        if ($counter % 3 == 0) {
            echo '<div class="row">';
            $rowClosed = false;
        }
        ?>
        <div class="col s12 m4">
            <div class="card" <?php echo 'data-projectId="' . $summary["project"]->getProjectId() .'"';?>>
                <div class="card-content">
                    <span class="card-title">Project: <?php echo '<a href="/project/id/' . $summary["project"]->getProjectId() . '">' . $summary["project"]->getProjectName() . '</a>'; ?></span>
                    <?php
                    if ($activeProject != null && $activeProject->getProjectId() == $summary["project"]->getProjectId()) {
                        echo '<p>Working Minutes <span class="total-time" data-minutes="'.$summary["timeSum"].'">' . minutesToTimeString(intval($summary["timeSum"])+intval($activeEntry->getWorkingMinutes())) . '</span></p>';
                        echo '<a class="time-toggle btn-floating right waves-effect waves-light red" href="/time/stop/"><i class="material-icons">pause</i></a>';
                    } else {
                        echo '<p>Working Minutes <span class="total-time" data-minutes="'.$summary["timeSum"].'">' . minutesToTimeString(intval($summary["timeSum"])) . '</span></p>';
                        echo '<a class="time-toggle btn-floating right waves-effect waves-light red" href="/time/start/' . $summary["project"]->getProjectId() . '"><i class="material-icons">play_arrow</i></a>';
                    }
                    ?>
                </div>
                <div class="card-action">
                    <a href="<?php echo "/time/showProject/" . $summary["project"]->getProjectId(); ?>">View Project
                        Time
                        Details</a>
                </div>
            </div>
        </div>
        <?php
        $counter++;
        if ($counter % 3 == 0) {
            echo ' </div > ';
            $rowClosed = true;
        }
    }
    // Make sure that div is closed
    if (!$rowClosed) {
        echo '</div > ';
    }
}

?>
