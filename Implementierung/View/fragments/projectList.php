<main class="container">
    <?php
    listPagination("project", intval($this->count), intval($this->pagination_interval), intval($this->pagination_page));
    ?>

    <table class="striped">
        <tr>
            <th>Id</th>
            <th>ProjectName</th>
            <th>ProjectManager</th>
        </tr>
        <?php
        foreach ($this->model as $row) {
            ?>
            <tr>
                <td><a href="<?php echo "/project/id/" . $row->getProjectId(); ?>"><?php echo $row->getProjectId(); ?></a></td>
                <td><?php echo $row->getProjectName(); ?></td>
                <td><a href="<?php echo "/user/id/" . $row->getProjectManager(); ?>">Profile</a></td>
            </tr>
            <?php
        }

        ?>
    </table>
    <a id="add_project" class="btn-floating btn-large waves-effect waves-light red right" href="/project/add"><i
                class="material-icons">add</i></a>

    <div id="add_project_form" class="modal" <?php if ($this->showAddForm) echo 'data-visibility="show"'; ?> >
        <div class="modal-content">
            <h3>Create new Project</h3>
            <form class="col s12 m8" method="post" action="/project/add">
                <div class="row">
                    <div class="input-field col s12 m8">
                        <input type="text" id="project_name" name="project_name" required/>
                        <label for="project_name">Project Name</label>
                    </div>
                    <div class="input-field col s12 m8">
                        <textarea name="project_description" id="project_description"
                                  class="materialize-textarea"></textarea>
                        <label for="project_description">Project Description</label>
                    </div>
                </div>
                <button type="submit" class="btn waves-effect waves-light">
                    OK
                    <i class="material-icons right">done</i>
                </button>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#" class="modal-close btn-flat">Close</a>
        </div>
    </div>
</main>
