<?php
session_start();

define('DIR', dirname(__FILE__));
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('APP_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'App');
define('LIB_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'Library');

set_include_path(ROOT_PATH . PATH_SEPARATOR . LIB_PATH . PATH_SEPARATOR . APP_PATH);

spl_autoload_register(function ($class_name) {
    $class_name = str_replace('\\', DIRECTORY_SEPARATOR, $class_name);
    include $class_name . '.php';
});

// Start app
$app = new \Admin\App();

// Get action
$action = '';

if (isset($_GET['action']) && !empty($_GET['action'])) {
    // Check function exist:
    if (method_exists($app, $_GET['action'])) {
        $action = trim($_GET['action']);

        echo $app->{$action}();
    } else {
        echo 'method not exist';
    }
} else {
    if (isset($_POST) && !empty($_POST)) {
        // Check user exit
        if (!\Actions\Member::usernameExist($_POST['username'])) {
            // Create new user
            if ($userId = \Actions\Member::createUser($_POST['username'], $_POST['password'])) {
                $message = '新ユーザーを作成しました。';
            }
        } else {
            $message = 'ユーザー名は存在してます。';
        }
    }

    // Get list user
    $users = \Library\DBHelper::getArrayRow('*', 'users', 'status = 1', '', 'username');
    ?>
    <html>
        <head>
            <meta charset="utf-8"/>
            <title>Create account</title>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css.map" rel="stylesheet" type="text/css"/>
            <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
            <link href="assets/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css">

            <script src="https://code.jquery.com/jquery-2.2.3.min.js" integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo=" crossorigin="anonymous"></script>
            <script type="text/javascript" src="assets/bootstrap/js/bootstrap.min.js"></script>
            <script type="text/javascript" src="assets/jquery-ui/jquery-ui.min.js"></script>
        </head>
        <body>
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="">
                            <?php echo $message;?>
                        </div>
                        <form class="form-horizontal" method="post" action="">
                            <div class="form-group">
                                <label class="control-label">ユーザー名:</label>
                                <input type="text" name="username" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label class="control-label">パスワード:</label>
                                <input type="password" name="password" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary">アカウント登録</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table-bordered table list-user">
                            <thead>
                                <tr>
                                    <th>ユーザー名</th>
                                    <th>パスワード</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if ($users) :?>
                                <?php foreach ($users as $user) : ?>
                                <tr>
                                    <th><?php echo $user['username'];?></th>
                                    <th>********</th>
                                    <th>
                                        <a href="javascript:" class="btn btn-xs btn-warning btn-edit" data-id="<?php echo $user['id'];?>" data-toggle="modal" data-target="#resetPasswordModal">パスワード再設定</a>
                                        <a href="javascript:" class="btn btn-xs btn-danger delete-btn" data-id="<?php echo $user['id'];?>">削除</a>
                                    </th>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="resetPasswordModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body align-center">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="modal-title">パスワード再設定</h3>
                                        <form class="form-horizontal frm-reset-password" method="post" action="index.php?action=resetPassword">
                                            <div class="row mb-10">
                                                <div class="form-group col-md-12">
                                                    <label class="control-label">新パスワード</label>
                                                    <input type="password" name="password" required class="form-control"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" name="id" value=""/>
                                                <button type="submit" class="btn btn-primary">変更</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                $(document).ready(function(){
                    $('#ajaxModal').on('hidden.bs.modal', function () {
                        $(this).find("input,textarea,select").val('').end();

                    });

                    $('body').on('submit', '.frm-reset-password', function(){
                        var frm = $(this);

                        $.ajax({
                            url: frm.attr('action'),
                            dataType: 'json',
                            type: 'post',
                            data: frm.serialize(),
                            success: function(res) {
                                if (res.success) {
                                    // Add to list field or reload page
                                    window.location.reload();
                                } else {

                                }
                            }
                        });

                        // Close modal
                        $(this).closest('.modal').modal('hide');

                        return false;
                    });

                    $('.list-user .btn-edit').on('click', function(){
                        // Update id to modal
                        $("#resetPasswordModal").find('input[name=id]').val($(this).data('id'));
                    });

                    $('.list-user .delete-btn').on('click', function(){
                        if (confirm('本気ですか？')) {
                            $.ajax({
                                url: 'index.php?action=removeUser',
                                dataType: 'json',
                                type: 'post',
                                data: {type: 'delete', id: $(this).data('id')},
                                success: function(res) {
                                    if (res.success) {
                                        // Remove item from list field or reload page
                                        window.location.reload();
                                    } else {

                                    }
                                }
                            });
                        }
                    });
                });
            </script>
        </body>
    </html>
<?php } ?>