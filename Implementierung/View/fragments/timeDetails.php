<?php
$activeProject = Project::loadFromSession();
?>
<main class="container" data-activeid="<?php if ($activeProject != null) echo $activeProject->getProjectId(); ?>">
    <div class="row">
        <div class="col s12">
            <?php
            $total = $this->workingTime->getTotalMinutes();
            if ($this->currentRecording != null && $this->currentRecording->getProjectId() == $activeProject->getProjectId()) {
                $total += $this->currentRecording->getWorkingMinutes();
            }
            echo '<div data-projectid="' . $this->model->getProjectId() . '">';
            echo '<h1 id="projectName""><a href="/project/id/' . $this->model->getProjectId() . '">' . $this->model->getProjectName() . '</a></h1>';
            if (!$this->interval) {
                echo '<span class="workingtime"> Total time spent on this project: </span><span class="workingtime total-time" data-minutes="' . $this->workingTime->getTotalMinutes() . '">' . minutesToTimeString($total) . '</span><br><br>';
            } else {
                echo '<span class="workingtime"> Working time in given interval: </span><span class="workingtime total-time" data-minutes="' . $this->workingTime->getTotalMinutes() . '">' . minutesToTimeString($total) . '</span><br><br>';
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col m6 s12">
            <?php
            if ($activeProject != null && $activeProject->getProjectId() == $this->model->getProjectId()) {
                echo '<a class="time-toggle btn-floating btn-large waves-effect waves-light red" href="/time/stop/" data-projectId="' . $this->model->getProjectId() . '"><i class="material-icons">pause</i></a>';
            } else {
                echo '<a class="time-toggle btn-floating btn-large waves-effect waves-light red" href="/time/start/' . $this->model->getProjectId() . '" data-projectId="' . $this->model->getProjectId() . '"><i class="material-icons">play_arrow</i></a>';
            }
            ?>
            <a class="btn-floating btn-large waves-effect waves-light green right" id="download"><i
                        class="material-icons">get_app</i></a>
        </div>
        <div class="col m6 s12">
            <form action="/time/projectTimesInterval/1/" method="get">
                <div class="col m5">
                    <label for="start_time">From:</label>
                    <input type="date" name="start_time" id="start_time" value="<?php echo $this->interval[0] ?>">
                </div>
                <div class="col m5">
                    <label for="end_time">To:</label>
                    <input type="date" name="end_time" id="end_time" value="<?php echo $this->interval[1] ?>">
                </div>
                <div class="col m2 center-align">
                    <br>
                    <button type="submit" class="btn waves-effect waves-light">OK</button>
                </div>
            </form>
        </div>
    </div>

    <?php
    if ($this->workingTime->getEntries() !== null){
    ?>
    <table class="striped">
        <thead>
        <tr>
            <th>StartTime</th>
            <th>EndTime</th>
            <th>WorkingMinutes</th>
        </tr>
        </thead>
        <tbody>
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
        </tbody>
    </table>
</main>
