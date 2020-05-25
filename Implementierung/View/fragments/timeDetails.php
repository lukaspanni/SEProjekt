<?php
$activeProject = Project::loadFromSession();
?>
<main class="container" data-activeid="<?php if ($activeProject != null) echo $activeProject->getProjectId(); ?>">
    <div class="row">
        <div class="col s12">
            <?php
            $total = $this->workingTime->getTotalMinutes();
            if($this->currentRecording != null && $this->currentRecording->getProjectId() == $activeProject->getProjectId()){
                $total += $this->currentRecording->getWorkingMinutes();
            }
            echo '<div data-projectid="'.$this->model->getProjectId().'">';
            echo '<h1><a href="/project/id/' . $this->model->getProjectId() . '">' . $this->model->getProjectName() . '</a></h1>';
            echo '<span class="workingtime"> Total time spent on this project: </span><span class="workingtime total-time" data-minutes="'.$this->workingTime->getTotalMinutes().'">' . minutesToTimeString($total) . '</span><br><br>';
            if ($activeProject != null && $activeProject->getProjectId() == $this->model->getProjectId()) {
                echo '<a class="btn-floating btn-large waves-effect waves-light red" href="/time/stop/" data-projectId="' . $this->model->getProjectId() . '"><i class="material-icons">pause</i></a>';
            } else {
                echo '<a class="btn-floating btn-large waves-effect waves-light red" href="/time/start/' . $this->model->getProjectId() . '" data-projectId="' . $this->model->getProjectId() . '"><i class="material-icons">play_arrow</i></a>';
            }
            echo '</div>';
            ?>
        </div>
    </div>

    <?php
    if ($this->workingTime->getEntries() !== null){
    ?>
    <table class="striped">
        <tr>
            <th>StartTime</th>
            <th>EndTime</th>
            <th>WorkingMinutes</th>
        </tr>
        <?php
        foreach ($this->workingTime->getEntries() as $entry) {
            echo '<tr>';
            echo '<td>' . date('d.m.Y H:i', strtotime($entry->getStartTime())) . '</td>';
            echo '<td>' . date('d.m.Y H:i', strtotime($entry->getEndTime())) . '</td>';
            echo '<td>' . minutesToTimeString($entry->getWorkingMinutes()) . '</td>';
            echo '</tr>';
        }
        if ($this->currentRecording != null) {
            echo '<tr>';
            echo '<td>' . date('d.m.Y H:i', strtotime($this->currentRecording->getStartTime())) . '</td>';
            echo '<td>-</td>';
            echo '<td>' . minutesToTimeString($this->currentRecording->getWorkingMinutes()) . '</td>';
            echo '</tr>';
        }
        }
        ?>
    </table>
</main>
