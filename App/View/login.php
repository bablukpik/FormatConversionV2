<?php include('elements/head.php'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <form class="form-horizontal" method="post" action="">
                <div class="form-group">
                    <label class="control-label">ユーザー名:</label>
                    <input type="text" class="form-control" name="username"/>
                </div>
                <div class="form-group">
                    <label class="control-label">パスワード:</label>
                    <input type="password" class="form-control" name="password"/>
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary">ログイン</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include('elements/footer.php'); ?>
