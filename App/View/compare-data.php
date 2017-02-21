<div class="container-fluid">
    <div class="row">
        <div class="col-md-5"><strong>データの比較</strong></div>
        <div class="col-md-7"><strong>挿入されたデータ</strong></div>
    </div>
    <div class="row">
        <?php if ($clientMapData) : ?>
        <?php
        // Get server data
        $serverData = $_SESSION['serverData'];
        $titles = reset($serverData);
        $newTitles = array();
//        foreach($titles as $key => $title) {
//            if (!isset($serverMapData[$title])) {
//                unset($titles[$key]);
//            }
//        }
        foreach($mapsData as $key => $title) {
            $titleKey = array_keys($titles, $title)[0];
            $newTitles[$titleKey] = $title;
        }

        array_splice($serverData, 0, 1, array($newTitles));
        // Get array key
        $keys = $serverData[0];
        ?>
        <div class="col-md-5">
            <?php foreach ($serverData as $rIndex => $row) : ?>
                <?php if ($rIndex > 0) : ?>
                    <div class="compare-row-data" id="row-<?php echo $rIndex;?>">
                        <h3 class="mt-0">列 <?php echo $rIndex; ?></h3>
                        <table>
                            <thead>
                            <tr>
                                <td>項目名</td>
                                <td>変換元</td>
                                <td>変換先</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($keys as $index => $key): ?>
                                <tr>
                                    <td><?php echo $key; ?></td>
                                    <td><?php echo isset($serverData[$rIndex][$index]) ? $serverData[$rIndex][$index] : ''; ?></td>
                                    <td><?php echo isset($clientMapData[$rIndex][$key]) ? $clientMapData[$rIndex][$key] : ''; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="col-md-7">
            <?php
            // Get title
            $titles = reset($serverData);
            ?>
            <div class="data-wrapper w-100 ml-0">
                <table class="row-content">
                    <thead>
                    <tr class="bg-header">
                        <?php foreach ($titles as $title) : ?>
                            <th><?php echo $title; ?></th>
                        <?php endforeach; ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($clientMapData as $k => $row) : ?>
                        <tr>
                            <?php foreach ($titles as $title) : ?>
                                <td><?php echo $row[$title];?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
