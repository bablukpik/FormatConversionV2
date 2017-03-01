<header>
    <div class="fl">
        <!-- <?php if (isset($_SESSION['convert_member'])) : ?>
        <strong><?php echo $_SESSION['convert_member']['username'];?></strong>, <a href="index.php?action=logout">ログアウト</a>
        <?php endif;?> -->
        <button id="home_posting" class="posting">転記</button>
    </div>
    <div class="fr btn-wrapper">
        <a href="javascript:browserClientFile()" title="Link match data" class="btn btn-primary">リンク図</a>
        <a href="index.php?action=configSimilarWord" title="config match data" class="btn btn-primary">リンク表</a>
        <a href="#" title="View Result" class="btn btn-primary">転記</a>
        <a href="index.php?action=logout" title="Logout" class="btn btn-primary btn-sort-matching">終了</a>
    </div>
    <div class="cl"></div>
</header>