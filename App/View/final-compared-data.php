<?php include('elements/head.php'); ?>
<?php include('elements/header.php'); ?>

<style>
    .border_separator{
        border-right: 3px solid black !important;
        padding-right: 5px !important;
    }
    .border_separator2{
        border-left: 3px solid black !important;
        padding-left: 5px !important;
    }
    .final_table_row .borderless_col_right{
             border-bottom: none !important;
         }
    .final_table_row:last-child{
        border-bottom: 1px solid #333;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <?php if ($clientMapData) : ?>

            <?php
            // Get server data
            $serverData = $_SESSION['serverData'];
            $titles = reset($serverData);

            // Get array key
            $keys = $serverData[0];
            ?>

            <div class="col-md-12" style="margin-top: 30px;">
                <?php
                // Get title
                $titles = reset($serverData);
                //var_dump($titles);
                ?>
                <div class="data-wrapper w-100 ml-0">
                    <table class="row-content">
                        <thead>
                        <tr class="bg-header">
                            <th rowspan="2">ブランド/セグメント</th>
                            <th rowspan="2">新・リ</th>
                            <th rowspan="2">品名</th>
                            <th rowspan="2">量目</th>
                            <th rowspan="2">入数</th>
                            <th rowspan="2">ＪＡＮＣＤ<br>＜4901231＞</th>
                            <th rowspan="2">包装形態</th>
                            <th rowspan="2">賞味<br>期間</th>
                            <th rowspan="2">保存<br>温度</th>

                            <!--2nd row and 1st 4 cols-->
                            <th colspan="4">税別</th>

                            <th rowspan="2">税込価格</th>

                            <!--2nd row and 2nd 5 cols-->
                            <th colspan="5">商品サイズ（ｍｍ）</th>

                            <th rowspan="2">発売予定</th>
                            <th rowspan="2">発売の狙い・コンセプト</th>
                        </tr>
                        <tr class="bg-header">
                            <!--2nd row 1st 4 cols-->
                            <th>卸</th>
                            <th>本体価格案</th>
                            <th>値入％</th>
                            <th>希望小売価格</th>

                            <!--2nd row 2nd 3 cols-->
                            <th rowspan="2">縦</th>
                            <th rowspan="2">&nbsp;</th>
                            <th rowspan="2">横</th>
                            <th rowspan="2">&nbsp;</th>
                            <th rowspan="2">奥行</th>

                        </tr>
                        </thead>
                        <tbody>
                            <?php $totalNumoRow = count($clientMapData);
                            $totalNumoRow = $totalNumoRow+1;
                            ?>

                            <tr>
                                <td rowspan="<?php echo isset($totalNumoRow)?$totalNumoRow:''; ?>"></td>
                            </tr>

                            <?php foreach ($clientMapData as $k => $row) : ?>

                                <tr class="final_table_row">
                                    <?php foreach ($donkiTitle as $title) :  ?>
                                        <td><div contenteditable="true" class="cell"><?php
                                                if ($title=='JAN'){
                                                   echo substr($row[$title], 7);
                                                }else{
                                                    echo isset($row[$title]) ? $row[$title]:'';
                                                }

                                        ?></div></td>
                                        <?php

                                        /*<!--new tow cols added-->*/
                                        if ($title=='縦'){
                                            echo "<td>X&nbsp;</td>";
                                        }
                                        if ($title=='横'){
                                            echo "<td>X&nbsp;</td>";
                                        }
                                        ?>
                                    <?php endforeach; ?>
                                    <td class="borderless_col_right">&nbsp;</td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>

            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    $('<td style="padding: 1px;">').insertAfter('.border_separator');
    var non_editable =$('.non_editable');
    non_editable.attr('contenteditable',false);
</script>