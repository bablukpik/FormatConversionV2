<?php include('elements/head.php'); ?>
<?php include('elements/header.php'); ?>

<script type="text/javascript">
    var hasData = false,
        similarWords = json_parse('<?php echo json_encode($listSimilar); ?>'),
        listIndex = json_parse('<?php echo json_encode($listIndex); ?>');
</script>

<div class="content-wrapper container-fluid">
<?php if ($clientData && $serverData) : ?>
    <script type="text/javascript">
        hasData = true;
        $(document).ready(function() {
            initTranslate();
            sortMatchServer();
            autoCompare();
        });
    </script>
    <!-- Show data from file -->
    <div class="csv-data-wrapper csv-data-client">
        <?php if ($clientData) : ?>
            <?php
            // Get title
            $titles = array_keys(reset($clientData));
            $totalRow = count($titles);
            $tableClass = '';

            if ($totalRow <= 30) {
                $tableClass = ' shortTable';
            }
            ?>
            <div class="data-wrapper">
                <table class="row-content<?php echo $tableClass;?>">
                    <?php $headCols = reset($clientData); ?>
                    <?php foreach ($headCols as $i => $title) : ?>
                        <!-- Make sure title is not empty -->
                        <?php if (!empty($clientData[0][$i])) : ?>
                            <?php
                            if (isset($matchCount[$clientData[0][$i]])) {
                                $dataCount = $matchCount[$clientData[0][$i]];
                            } else {
                                $dataCount = 0;
                            }
                            ;?>
                            <?php if (!empty($clientData[0][$i])) : ?>
                                <tr>
                                    <?php foreach ($clientData as $k => $row) : ?>
                                        <?php $class = ($k == 0) ? 'head-col' : ''; ?>
                                        <td class="<?php echo $class; ?>" data-value="<?php echo str_replace(' ', '', $clientData[$k][$i]); ?>" title="<?php echo $clientData[$k][$i]; ?>"><?php echo $clientData[$k][$i]; ?></td>
                                    <?php endforeach; ?>
                                    <td class="connect-item client-data-connect">
                                        <?php echo $dataCount;?>
                                        <a id="row-<?php echo $clientData[0][$i]; ?>" data-type="client" data-key="<?php echo $clientData[0][$i]; ?>"></a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>
    </div>
    <div class="csv-data-wrapper csv-data-server">
        <?php if ($serverData) : ?>
            <?php
            // Get title
            $titles = array_keys(reset($serverData));
            $totalRow = count($titles);

            if ($totalRow <= 30) {
                $tableClass = ' shortTable';
            }
            ?>
            <div class="data-wrapper">
                <table class="row-content tbl-server-data<?php echo $tableClass;?>">
                    <?php $headCols = reset($serverData); ?>
                    <?php foreach ($headCols as $i => $title) : ?>
                        <?php
                        if (isset($matchCount[$serverData[0][$i]])) {
                            $dataCount = $matchCount[$serverData[0][$i]];
                        } else {
                            $dataCount = 0;
                        }
                        ?>
                        <?php if (!empty($serverData[0][$i])): ?>
                            <tr data-count="<?php echo $dataCount;?>" data-index="<?php echo $i;?>">
                                <td class="connect-item server-data-connect">
                                    <a id="row-<?php echo $serverData[0][$i]; ?>" data-type="server" data-key="<?php echo str_replace(' ', '', $serverData[0][$i]); ?>"></a>
                                    <?php echo $dataCount;?>
                                </td>
                                <?php foreach ($serverData as $k => $row) : ?>
                                    <?php $class = ($k == 0) ? 'head-col' : ''; ?>
                                    <td class="<?php echo $class; ?>" data-value="<?php echo $serverData[$k][$i]; ?>" title="<?php echo $serverData[$k][$i]; ?>"><?php echo $serverData[$k][$i]; ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </table>
            </div>
            <div>
                <a id="export-to-excel" href="javascript:exportCompareData()">Export to Excel</a>
            </div>
        <?php endif; ?>
    </div>
    <canvas id="canvas" width=300 height=300 onclick="handleCanvasClick(event)"></canvas>
    <button class="btn btn-danger btn-xs remove-canvas-btn"><i class="glyphicon glyphicon-remove"></i></button>
    <div class="cl"></div>
    <div align="center" class="footer-btn">
        <!--<button class="btn btn-primary" onclick="compareData()">リンク</button>-->
    </div>
    <div id="fountainG">
        <div id="fountainG_1" class="fountainG"></div>
        <div id="fountainG_2" class="fountainG"></div>
        <div id="fountainG_3" class="fountainG"></div>
        <div id="fountainG_4" class="fountainG"></div>
        <div id="fountainG_5" class="fountainG"></div>
        <div id="fountainG_6" class="fountainG"></div>
        <div id="fountainG_7" class="fountainG"></div>
        <div id="fountainG_8" class="fountainG"></div>
    </div>
    <div class="compare-wrapper">

    </div>
<?php else : ?>
    <?php include('demo-data.php');?>
<?php endif; ?>
</div>
<div id="notifyModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <p>
                    ① 変換元を自社フォーマットで
                    　 取込んでください。
                </p>
                <p>
                    ② 続いて、販売先（ユーザー）を
                    　 取込んでください。
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">認確</button>
            </div>
        </div>

    </div>
</div>

<div class="support-div sort-support">
    <p>二度押すと戻ります。</p>
</div>

<div class="support-div center-box no-matching">
    <p>①線を手動で引っぱる事でリンクが可能です。（取消も出来ます</p>
    <p>②類似語登録すると自動でリンク出来ます。</p>
    <button class="btn btn-primary close-support">認確</button>
</div>

    <div id="loading-page">
        <div class="loading-content">
            <img src="assets/image/preloader.gif"/>
            <div id="loading-text"></div>
        </div>
    </div>

<?php include('elements/footer.php'); ?>