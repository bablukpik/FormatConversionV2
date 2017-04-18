<header>
    <div class="fl">
        <!-- <?php if (isset($_SESSION['convert_member'])) : ?>
        <strong><?php echo $_SESSION['convert_member']['username'];?></strong>, <a href="index.php?action=logout">ログアウト</a>
        <?php endif;?> -->
        <p class="posting fl">転記</p>
        <?php
        $finalCompareData = isset($_GET['action'])?$_GET['action']:'';
        $compareData = isset($_GET['action'])?$_GET['action']:'';

        if (($finalCompareData=='finalCompareData') || ($compareData == 'compareData')): ?>
        <h2 class="fl" style="margin-left: 200px;">ジャコス標準帳票（フォーマット）</h2>
        <?php endif;?>
    </div>
    <div class="fr btn-wrapper" style="margin-top: 5px;">

        <!--<a href="index.php?action=page&select=manufacturer" id="buyer_posting" title="Maker" class="btn btn-primary">メーカー</a>-->
        <a href="#" id="viewMapData" class="btn btn-primary" title="view map data">リンク図</a>

        <!-- <a href="javascript:startMatching()" title="Link match data" class="btn btn-primary">リンク図</a> -->

        <a href="index.php?action=configSimilarWord" title="config match data" class="btn btn-primary">リンク表</a>

        <!--<a href="javascript:compareData()" id="viewResultOnly" title="View Result" class="btn btn-primary">転記</a>-->
        <!--<a href="index.php?action=finalCompareData" id="viewOverwriteResult" title="View Overwrite Result" class="btn btn-primary">上書き</a>-->
        <a href="index.php" id="seller_posting" title="Sales destination" class="btn btn-primary">完了</a>
        <!--<a href="index.php?action=page&select=seller" id="seller_posting" title="Sales destination" class="btn btn-primary">完了</a>-->
        <!--<a href="#" id="seller_posting" title="Sales destination" class="btn btn-primary">完了</a>-->
    </div>

    <div class="cl"></div>
    <a href="index.php?action=exportFinalComparedData" id="ExportToExcel" title="Export to Excel" class="btn btn-default pull-right display_none" style="margin-right: 5px;">エクセル出力</a>
    <a href="#" id="viewMapDataBackBtn" title="Map Data Back" class="btn btn-default display_none" style="background-color:#FFF77D;position: absolute; right:5px; top:65px; padding: 3px 10px;">戻る</a>

    <form id="header-form" method="post" enctype="multipart/form-data" action="index.php?action=home">
        <input type="file" id="inputClientData" name="client-data" onchange="readExcelFileClient(this)" />
        <input type="file" name="server-data" onchange="readExcelFileStadard(this)" />
        <input type="text" id="inputReportType" name="report-type" value="" />
        <input type="text" id="inputCompanyType" name="company-type" value="" />
    </form>
    <div class="cl"></div>
</header>
