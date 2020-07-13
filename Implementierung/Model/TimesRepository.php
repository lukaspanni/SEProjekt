<?php

/**
 * Class TimesRepository
 * adding, selecting and updating Working-Time and Time-Entry Objects from database
 */
class TimesRepository extends Repository
{

    /**
     * @param $user User
     * @return WorkingTime
     */
    public function getByUser($user)
    {
        $sql = "SELECT UserId, ProjectId, StartTime, EndTime, TIMESTAMPDIFF(MINUTE, StartTime, EndTime) AS WorkingMinutes FROM workingtime WHERE UserId=:User AND TIMESTAMPDIFF(MINUTE, StartTime, EndTime) > 0";
        $stmt = $this->dbConnection->prepare($sql);
        $res = $stmt->execute(array(":User" => $user->getUserId()));
        if ($res !== false) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "TimeEntry");
            return new WorkingTime($stmt->fetchAll());
        }
        return null;
    }

    /**
     * @param $project Project
     * @return WorkingTime
     */
    public function getByProject($project)
    {
        $sql = "SELECT UserId, ProjectId, StartTime, EndTime, TIMESTAMPDIFF(MINUTE, StartTime, EndTime) AS WorkingMinutes FROM workingtime WHERE ProjectId=:Project AND TIMESTAMPDIFF(MINUTE, StartTime, EndTime) > 0";
        $stmt = $this->dbConnection->prepare($sql);
        $res = $stmt->execute(array(":Project" => $project->getProjectId()));
        if ($res !== false) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "TimeEntry");
            return new WorkingTime($stmt->fetchAll());
        }
        return null;
    }

    /**
     * Get active entry(=running time recording) of specific user
     * @param $user User
     * @return TimeEntry
     */
    public function getActiveEntry($user)
    {
        if (is_numeric($user)) {
            $param = array(":User" => $user);
        } else {
            $param = array(":User" => $user->getUserId());
        }
        $sql = "SELECT UserId, ProjectId, StartTime, EndTime, TIMESTAMPDIFF(MINUTE, StartTime, NOW()) AS WorkingMinutes FROM workingtime WHERE UserId=:User AND EndTime IS NULL";
        $stmt = $this->dbConnection->prepare($sql);
        $res = $stmt->execute($param);
        if ($res !== false) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "TimeEntry");
            return $stmt->fetch();
        }
        return null;
    }

    /**
     * Get all entries of user in project
     * @param $project Project
     * @param $user User
     * @return WorkingTime|null
     */
    public function getAllEntries($project, $user)
    {
        $sql = "SELECT UserId, ProjectId, StartTime, EndTime, TIMESTAMPDIFF(MINUTE, StartTime, EndTime) AS WorkingMinutes FROM workingtime WHERE ProjectId=:Project AND UserId=:User AND TIMESTAMPDIFF(MINUTE, StartTime, EndTime) > 0";
        $stmt = $this->dbConnection->prepare($sql);
        $res = $stmt->execute(array(":User" => $user->getUserId(), ":Project" => $project->getProjectId()));
        if ($res !== false) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "TimeEntry");
            return new WorkingTime($stmt->fetchAll());
        }
        return null;
    }

    /**
     * Get entries in given Interval
     * @param $project Project
     * @param $user User
     * @param $startTime StartTime of the Interval
     * @param $endTime EndTime of the Interval
     * @return WorkingTime|null
     */
    public function getEntriesInterval($project, $user, $startTime, $endTime)
    {
        $sql = "SELECT UserId, ProjectId, StartTime, EndTime, TIMESTAMPDIFF(MINUTE, StartTime, EndTime) AS WorkingMinutes FROM workingtime WHERE ProjectId=:Project AND UserId=:User AND TIMESTAMPDIFF(MINUTE, StartTime, EndTime) > 0 AND StartTime>:StartTime AND EndTime<:EndTime";
        $stmt = $this->dbConnection->prepare($sql);
        $res = $stmt->execute(array(":User" => $user->getUserId(), ":Project" => $project->getProjectId(), ":StartTime"=>$startTime, ":EndTime"=>$endTime));
        if ($res !== false) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, "TimeEntry");
            return new WorkingTime($stmt->fetchAll());
        }
        return null;
    }

    /**
     * Get short summary of user working on project, if user null: get short summary of all users working on project
     * @param $project Project
     * @param null $user User
     * @return int
     */
    public function getSummary($project, $user = null)
    {
        if ($user == null) {
            $sql = "SELECT SUM(TIMESTAMPDIFF(MINUTE, StartTime, EndTime)) AS WorkingMinutes FROM workingtime WHERE ProjectId=:Project AND UserId IN (SELECT UserId FROM invited_to_work_on WHERE workingtime.ProjectId=:Project AND Accepted IS TRUE UNION  ALL SELECT ProjectManager FROM project WHERE project.ProjectId=:Project) GROUP BY ProjectID";
            $param_array = array(":Project" => $project->getProjectId());
        } else {
            $sql = "SELECT SUM(TIMESTAMPDIFF(MINUTE, StartTime, EndTime)) AS WorkingMinutes FROM workingtime WHERE ProjectId=:Project AND UserId=:User GROUP BY ProjectID";
            $param_array = array(":User" => $user->getUserId(), ":Project" => $project->getProjectId());
        }
        $stmt = $this->dbConnection->prepare($sql);
        $res = $stmt->execute($param_array);
        if ($res !== false) {
            return $stmt->fetchColumn();
        }
        return null;
    }

    /**
     * Get the last n projects the user started a time recording for
     * @param $user User
     * @param $count int
     * @return array
     */
    public function getLastWorkingProjects($user, $count)
    {
        $sql = "SELECT DISTINCT ProjectId, MAX(StartTime) FROM workingtime WHERE UserId=:User GROUP BY ProjectId ORDER BY MAX(StartTime) DESC LIMIT :Count";
        $stmt = $this->dbConnection->prepare($sql);
        $id = $user->getUserId(); //Else: Notice!
        $stmt->bindParam(":User", $id, PDO::PARAM_INT);
        $stmt->bindParam(":Count", $count, PDO::PARAM_INT);
        $res = $stmt->execute();
        if ($res !== false) {
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        return null;
    }

    /**
     * @param $timeEntry TimeEntry
     * @return bool
     */
    public function add($timeEntry)
    {
        $params = array(":User" => $timeEntry->getUserId(), ":Project" => $timeEntry->getProjectId());
        $sql = "SELECT COUNT(*) FROM invited_to_work_on RIGHT OUTER JOIN project ON invited_to_work_on.projectId=project.ProjectId WHERE project.ProjectId=:Project AND (invited_to_work_on.userId=:User OR project.ProjectManager=:User)";
        $stmt = $this->dbConnection->prepare($sql);
        $res = $stmt->execute($params);
        if ($res !== false && $stmt->fetchColumn() == 0) {
            //User isnt allowed to work at this project
            return false;
        }
        $active = $this->getActiveEntry($timeEntry->getUserId());
        if ($active != null) {
            if ($timeEntry->getProjectId() == $active->getProjectId()) {
                return true;
            } else {
                $this->update($active);
            }
        }
        $sql2 = "INSERT INTO workingtime (ProjectId, UserId) VALUES(:Project, :User)";
        $stmt2 = $this->dbConnection->prepare($sql2);
        return $stmt2->execute(array(":User" => $timeEntry->getUserId(), ":Project" => $timeEntry->getProjectId()));
    }


    /**
     * @param $timeEntry TimeEntry
     * @return mixed
     */
    public function update($timeEntry)
    {
        $sql = "UPDATE workingtime SET EndTime=:EndTime WHERE EndTime IS NULL AND UserId=:User AND ProjectId=:Project AND StartTime=:StartTime";
        $stmt = $this->dbConnection->prepare($sql);
        return $stmt->execute(array(":User" => $timeEntry->getUserId(), ":Project" => $timeEntry->getProjectId(), ":StartTime" => $timeEntry->getStartTime(), ":EndTime" => $timeEntry->getEndTime()));
    }

    /**
     * Count is not sensible for Time-Entries
     */
    public function getCount()
    {
        throw new BadMethodCallException();
    }
}


?>