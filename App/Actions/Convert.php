<?php
namespace Actions;
use Akeneo\Component\SpreadsheetParser\SpreadsheetParser;
use Library\DBHelper;
use Library\Spreadsheet_Excel_Reader;

require_once LIB_PATH . DIRECTORY_SEPARATOR . 'PHPExcel.php';

/**
 * Class Convert
 */
class Convert {
    public static $table = 'result';
    public static $fileTable = 'files';

    /**
     * Convert data from csv file
     *
     * @param $file
     * @param string $name
     * @return array
     */
    public static function GetDataFromFile($file, $name = '', $type = '', $company = '') {
        // Check file type
        $data = self::readData($file, $company, $type == 'client');

        // Add data to database, apply for server type only
        if ($type == 'server') {
            $type = 1;
            self::InsertFileData($name, $type, $data);
        }

        return $data;
    }

    /**
     * Insert data to database
     * @param array $data
     * @return bool
     */
    public static function InsertFileData($fileName, $type, $data = array()) {
        if (!empty($data)) {
            $insertData = [
                'file_name' => $fileName,
                'file_datas' => json_encode($data),
                'type'      => $type,
                'created'   => date('Y-m-d H:i:s')
            ];

            return DBHelper::Insert(self::$fileTable, $insertData);
        }

        return false;
    }

    /**
     * Insert link data to database
     * @param array $data
     * @return bool
     */
    public static function InsertLinkData($data = array()) {
        // Map data
        $result = array();

        foreach ($data as $row) {
            $insertData = array();

            // Insert to database
            if (!$insertId = DBHelper::Insert(self::$table, $insertData)) {
                return false;
                break;
            } else {
                // Get row inserted
                $insertRow = DBHelper::getRow('*', self::$table, 'id = ' . $insertId);
                // Remove index data
                unset($insertRow['id']);

                $tmp = array();
                // Replace key title
                foreach ($insertRow as $field => $value) {
                    $tmp[self::$fieldTitle[$field]] = $value;
                }

                $result[] = $tmp;
            }
        }
        return $result;
    }

    /**
     * Export array to csv file
     * @param $array
     * @param string $fileName
     * @param string $delimiter
     */
    public static function ExportCSVFile($array, $fileName = 'export.csv', $delimiter = ';') {
        header('Content-Encoding: UTF-8');
        // open raw memory as file so no temp files needed, you might run out of memory though
        $f = fopen('php://memory', 'w');
        // Set header
        fputcsv($f, array_keys($array[0]));
        // loop over the input array
        foreach ($array as $line) {
            // generate csv lines from the inner arrays
            fputcsv($f, $line, $delimiter);
        }
        // reset the file pointer to the start of the file
        fseek($f, 0);
        // tell the browser it's going to be a csv file
        header('Content-Type: application/csv; charset=UTF-8');
        // tell the browser we want to save it instead of displaying it
        header('Content-Disposition: attachment; filename="'.$fileName.'";');
        // make php send the generated csv lines to the browser
        fpassthru($f);
    }

    /**
     * Create map data
     *
     * @param $inputData
     * @param $mapData
     * @return array|bool
     */
    public static function MapData($inputData, $mapData)
    {
        if (!empty($mapData) && is_array($mapData)) {
            // Rebuild server data and client data by list key - value
            $keys = isset($inputData[0])?$inputData[0]:'';
            $result = array();
            foreach ($inputData as $k => $value) {
                if ($k > 0) {
                    foreach ($keys as $index => $key) {
                        if (isset($mapData[$key]) && !empty($mapData[$key])){
                            $result[$k][$mapData[$key]] = $value[$index];
                        }
                    }
                }
            }

            return $result;
        }

        return false;
    }

    private static function mergeData($dataArray1, $dataArray2)
    {
        if (empty($dataArray1)) return $dataArray2;
        if (empty($dataArray2)) return $dataArray1;

        $mergedArray = array();
        $length = max(count($dataArray1), count($dataArray2));
        for ($i=0; $i < $length; $i++) {
            if (!isset($dataArray1[$i])) {
                $dummyData = array_fill_keys(array_keys($dataArray1[0]), '');
                $mergedArray[$i] = $dummyData + $dataArray2[$i];
            } elseif (!isset($dataArray2[$i])) {
                $dummyData = array_fill_keys(array_keys($dataArray2[0]), '');
                $mergedArray[$i] = $dataArray1[$i] + $dummyData;
            } else {
                $mergedArray[$i] = $dataArray1[$i] + $dataArray2[$i];
            }
        }
        return $mergedArray;
    }

    /**
     * Read file and return array data. (Merge cells processed)
     * @param $file
     * @return array
     * @throws \PHPExcel_Exception
     */
    private static function readData($file, $company, $highlightedColumnOnly = false)
    {
        $objPhpExcel = \PHPExcel_IOFactory::load($file);

        $objWorksheet = $objPhpExcel->getActiveSheet();

        $highestRow    = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();

        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

        $arrayData = array();
        $i = 0;
        $k = 1;
        $lastColumnPos = ['col' => null, 'row' => null];
        if ($company == "Don Quixote") {
            $janFound = false;
            $janPrefix = null;

            for ($col = 0; $col < $highestColumnIndex; $col++) {
                for ($row = 0; $row <= $highestRow; $row++) {
                    $cell = $objWorksheet->getCellByColumnAndRow($col, $row);
                    if ($cell->getStyle()->getFill()->getEndColor()->getARGB() == 'FFFFFFFF') {
                        if ($i <= 0)
                            continue;
                        $value = $cell->getFormattedValue();
                        if ($janFound)
                            $value = $janPrefix . $value;
                        if ($cell->isInMergeRange()) {
                            if ($cell->isMergeRangeValueCell()) {
                                $temp = explode(":", preg_replace("/[^0-9:.]/", "", $cell->getMergeRange()));
                                for ($j = 0; $j < $temp[1] - $temp[0] + 1; $j++) {
                                    $arrayData[$k][$i - 1] = trim($value);
                                    $k++;
                                }
                            }
                        } else if (!empty($value)) {
                            $arrayData[$k][$i - 1] = trim($value);
                            $k++;
                        }
                    } else {
                        if ($janFound)
                            $janFound = false;
                        $value = trim($cell->getFormattedValue());
                        if ($lastColumnPos['col'] == $col && $row - $lastColumnPos['row'] == 1) {
                            if ($cell->isInMergeRange() && !$cell->isMergeRangeValueCell()) {
                                continue;
                            }
                            if ($arrayData[0][$i - 1] == "ＪＡＮＣＤ") {
                                $janFound = true;
                                $janPrefix = trim(preg_replace("/[^0-9.]/", "", $value));
                                continue;
                            }
                            $i--;
                        }

                        $arrayData[0][$i] = $value;
                        $lastColumnPos['col'] = $col;
                        $lastColumnPos['row'] = $row;
                        $i++;
                        $k = 1;

                    }
                }
            }
        } else if ($company == "Itoham") {
            $doubleCol = false;
            $oddIndex = false;
            $topHeaderRow = -1;
            for ($col = 0; $col < $highestColumnIndex; $col++) {
                for ($row = 0; $row <= $highestRow; $row++) {
                    $cell = $objWorksheet->getCellByColumnAndRow($col, $row);
                    if ($cell->getStyle()->getFill()->getEndColor()->getARGB() == 'FFFFFFFF') {
                        if ($i <= 0 || $cell->getRow() < $topHeaderRow)
                            continue;
                        $value = $cell->getFormattedValue();
                        if ($cell->getStyle()->getFont()->getColor()->getARGB() != 'FFFFFFFF') {
                            if ($cell->isInMergeRange()) {
                                if ($cell->isMergeRangeValueCell()) {
                                    $temp = explode(":", preg_replace("/[^0-9:.]/", "", $cell->getMergeRange()));
                                    for ($j = 0; $j < $temp[1] - $temp[0] + 1; $j++) {
                                        $arrayData[$k][$i - 1] = trim($value);
                                        $k++;
                                    }
                                }
                            } else if (!empty($value)) {
                                if ($doubleCol) {
                                    if ($oddIndex) {
                                        $arrayData[$k][$i - 2] = trim($value);
                                        $oddIndex = false;
                                    } else {
                                        $arrayData[$k][$i - 1] = trim($value);
                                        $k++;
                                    }
                                } else {
                                    $arrayData[$k][$i - 1] = trim($value);
                                    $k++;
                                }
                            }
                        }
                    } else {
                        if ($cell->isInMergeRange() && !$cell->isMergeRangeValueCell())
                            continue;
                        $arrayData[0][$i] = trim($cell->getFormattedValue());
                        if ($cell->getRow() > $topHeaderRow)
                            $topHeaderRow = $cell->getRow();
                        if ($lastColumnPos['col'] == $col && $row - $lastColumnPos['row'] == 1) {
                            $doubleCol = true;
                            $oddIndex = true;
                        } else {
                            $doubleCol = false;
                            $oddIndex = false;
                        }
                        $lastColumnPos['col'] = $col;
                        $lastColumnPos['row'] = $row;
                        $i++;
                        $k = 1;
                    }
                }
            }
        }
        return $arrayData;
    }

//    private static function readData($file, $highlightedColumnOnly = false)
//    {
//        $objPhpExcel = \PHPExcel_IOFactory::load($file);
//
//        $objWorksheet = $objPhpExcel->getActiveSheet();
//
//        $highestRow    = $objWorksheet->getHighestRow();
//        $highestColumn = $objWorksheet->getHighestColumn();
//        $mergeCell = $objPhpExcel->getSheet()->getMergeCells();
//
//        $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
//
//        $columnKeyAdjustments = 0;
//        $rowKeyAdjustments = 0;
//        $arrayDataMerged = array();
//        $arrayData = array();
//        $maxColumn = 0;
//        $filterColumns = array();
//        $i = 0;
//        for ($row = 0; $row <= $highestRow;++$row)
//        {
//            $rowData = array();
//            for ($col = 0; $col <$highestColumnIndex;++$col)
//            {
//                $cell = $objWorksheet->getCellByColumnAndRow($col, $row);
//                $value = $cell->getValue();
//                if ($highlightedColumnOnly) {
//                    $fillColor = $cell->getStyle()->getFill()->getStartColor()->getARGB();
//                    if ($fillColor != "FFFFFFFF" && $fillColor != "FF000000") {
//                        if (!empty($arrayData)) {
//                            $arrayDataMerged = self::mergeData($arrayDataMerged, $arrayData);
//                            $arrayData = array();
//                            $rowData = array();
//                            $filterColumns = array();
//                            $columnKeyAdjustments += $highestColumnIndex;
//                            $i = 0; //reset index for array data
//                        }
//
//                        $filterColumns[] = $col;
//                    }
//                }
//                if (!empty($value) && (!$highlightedColumnOnly || in_array($col, $filterColumns))) {
//                    $rowData[$col+$columnKeyAdjustments] = trim($value);
//                }
//            }
//
//            $totalColumn = count($rowData);
//
//            if ($totalColumn) {
//                $arrayData[$i] = $rowData;
//                $i++;
//            }
//
//            if ($totalColumn > $maxColumn) {
//                $maxColumn = $totalColumn;
//            }
//        }
//
//
//        if ($mergeCell) {
//            // Check merge
//            $firstMerge = reset($mergeCell);
//
//            $arr = explode(':', $firstMerge);
//
//            $start = filter_var($arr[0], FILTER_SANITIZE_NUMBER_INT);
//            $end   = filter_var($arr[1], FILTER_SANITIZE_NUMBER_INT);
//            $rowsMerge = $end - $start;
//
//            if ($rowsMerge >= 1) {
//                // Process array data for merge cell
//                $tmpArray = array();
//                $index = 0;
//                $isMerging = false;
//                $i = 1;
//
//                foreach ($arrayData as $columns) {
//                    if (!$isMerging) {
//                        $tmpArray[$index] = $columns;
//                        $isMerging = true;
//                        $index++;
//                    } else  {
//                        if ($i <= $rowsMerge && $isMerging) {
//                            // Merge to prev row, add value to the last
//                            foreach ($columns as $key => $column) {
//                                $tmpArray[$index - $i][$maxColumn + $key] = $column;
//                            }
//                            if ($i == $rowsMerge) {
//                                $i = 1;
//                                $isMerging = false;
//                            } else {
//                                $i++;
//                            }
//                        } else {
//                            $isMerging = false;
//                            $i = 1;
//                        }
//                    }
//                }
//
//                $arrayData = $tmpArray;
//            }
//        }
//
//
//        if (!empty($arrayDataMerged)) {
//            $arrayData = self::mergeData($arrayDataMerged, $arrayData);
//        }
//
//        return $arrayData;
//    }
//
    /**
     * Get last file data
     *
     * @param string $type
     * @return array|bool
     */
    public static function getLastFile($type = '')
    {
        $condition = '';

        if (!empty($type)) {
            $condition = 'type = ' . (int)$type;
        }

        return DBHelper::getRow('*', self::$fileTable, $condition, 'created DESC');
    }
}