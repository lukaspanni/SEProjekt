<main class="container">
    <div id="pagination-search" class="row">
        <div class="col s12 m6">
            <?php
            if (!$this->searchResult) {
                listPagination("user", intval($this->count), intval($this->pagination_interval), intval($this->pagination_page));
            }
            ?>
        </div>
        <div class="col s12 m4 offset-m2">
            <form action="/user/search/" method="POST">
                <div class="input-field">
                    <i class="material-icons prefix">search</i>
                    <input id="search" type="search" name="searchString" required>
                </div>
            </form>
        </div>
    </div>
    <table class="striped">
        <tr>
            <th>Id</th>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>email</th>
        </tr>
        <?php
        foreach ($this->model as $row) {
            ?>
            <tr>
                <td><a href="<?php echo "/user/id/" . $row->getUserId(); ?>"><?php echo $row->getUserId(); ?></a></td>
                <td><?php echo $row->getFirstname(); ?></td>
                <td><?php echo $row->getLastname(); ?></td>
                <td><?php echo $row->getEmailAddress(); ?></td>
            </tr>
            <?php
        }

        ?>
    </table>
</main>
