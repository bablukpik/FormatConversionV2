<header>
    <div class="fl">
        <!-- <?php if (isset($_SESSION['convert_member'])) : ?>
        <strong><?php echo $_SESSION['convert_member']['username'];?></strong>, <a href="index.php?action=logout">ログアウト</a>
        <?php endif;?> -->
        <p class="posting">転記</p>
    </div>
    <div class="fr btn-wrapper">

        <a href="index.php?action=page&select=manufacturer" id="buyer_posting" title="Maker" class="btn btn-primary">メーカー</a>
        <a href="index.php?action=page&select=seller" id="seller_posting" title="Sales destination" class="btn btn-primary">販売先</a>

        <a href="javascript:startMatching()" title="Link match data" class="btn btn-primary">リンク図</a>

        <!-- <a href="javascript:startMatching()" title="Link match data" class="btn btn-primary">リンク図</a> -->

        <a href="index.php?action=configSimilarWord" title="config match data" class="btn btn-primary">リンク表</a>

        <a href="javascript:compareData()" title="View Result" class="btn btn-primary">転記</a>

        <a href="index.php?action=finalCompareData" title="" class="btn btn-primary btn-sort-matching">終了</a>
    </div>
    <div class="cl"></div>
    <form id="header-form" method="post" enctype="multipart/form-data" action="index.php?action=home">
        <input type="file" name="client-data" onchange="readExcelFileClient(this)" />
        <input type="file" name="server-data" onchange="readExcelFileStadard(this)" />
        <input type="text" id="inputReportType" name="report-type" value="" />
    </form>
</header>