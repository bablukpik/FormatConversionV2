<header>
    <div class="fl">
        <?php if (isset($_SESSION['convert_member'])) : ?>
        <strong><?php echo $_SESSION['convert_member']['username'];?></strong>, <a href="index.php?action=logout">ログアウト</a>
        <?php endif;?>
    </div>
    <div class="fr btn-wrapper">
        <a href="javascript:browserClientFile()" title="browse client file" class="btn btn-primary">販売先</a>
        <a href="javascript:startMatching()" class="btn btn-primary">開始</a>
        <a href="index.php?action=configSimilarWord" title="config match data" class="btn btn-primary">類似語</a>
        <a href="javascript:sortMatchServer()" class="btn btn-primary btn-sort-matching">頻度順</a>
        <a href="javascript:compareData()" class="btn btn-primary">完了</a>
        <a href="javascript:browserServerFile()" title="Browser server file" class="btn btn-primary">機能</a>
    </div>
    <div class="cl"></div>
    <form id="header-form" method="post" enctype="multipart/form-data" action="">
        <input type="file" name="client-data"/>
        <input type="file" name="server-data"/>
    </form>
</header>