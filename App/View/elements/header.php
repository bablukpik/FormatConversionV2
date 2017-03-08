<header>
    <div class="fl">
        <!-- <?php if (isset($_SESSION['convert_member'])) : ?>
        <strong><?php echo $_SESSION['convert_member']['username'];?></strong>, <a href="index.php?action=logout">ログアウト</a>
        <?php endif;?> -->
        <a href="index.php?action=page" id="home_posting" class="posting">転記</a>
    </div>
    <div class="fr btn-wrapper">
        <a href="index.php?action=page" title="Link match data" class="btn btn-primary">リンク図</a>

        <!-- <a href="javascript:startMatching()" title="Link match data" class="btn btn-primary">リンク図</a> -->

        <a href="index.php?action=configSimilarWord" title="config match data" class="btn btn-primary">リンク表</a>

        <a href="javascript:compareData()" title="View Result" class="btn btn-primary">転記</a>

        <a href="#" title="" class="btn btn-primary btn-sort-matching">終了</a>
    </div>
    <div class="cl"></div>
    <form id="header-form" method="post" enctype="multipart/form-data" action="index.php?action=home">
        <input type="file" name="client-data" onchange="readExcelFileSeller(this)" />
        <input type="file" name="server-data" onchange="readExcelFileMaker(this)" />
    </form>
</header>