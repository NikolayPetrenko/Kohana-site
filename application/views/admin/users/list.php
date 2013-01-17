<?php Helper_Output::renderErrors() ?>
    <table class="table table-condensed">
            <thead>
                    <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Logins</th>
                            <th>Bithday</th>
                            <th>Last Login</th>
                            <th style="width: 20%">Actions</th>
                    </tr>
            </thead>
    </table>
<div class="buttons">
    <a class="btn btn-primary" href="<?php echo URL::site('admin/users/edit/') ?>">Add new User</a>
</div>