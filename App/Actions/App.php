<?php
namespace Actions;

use Library\Presentation;
use Library\DBHelper;
use Library\Spreadsheet_Excel_Reader;
use \PHPExcel_Cell;
use \PHPExcel_Style_Alignment;
use \PHPExcel_Style_Fill;
use \PHPExcel_Cell_AdvancedValueBinder;
use \PHPExcel;
use \PHPExcel_IOFactory;
use \PHPExcel_Style_Border;
use \PHPExcel_Shared_Font;

require_once LIB_PATH . DIRECTORY_SEPARATOR . 'PHPExcel.php';

class App extends Presentation {

    /**
     * Run home page
     */
    public function home()
    {
        //Member::checkLogin();

        $data = array();

        // Has file upload
        if (isset($_FILES) && !empty($_FILES)) {

            $_SESSION['ReportType'] = $_POST['report-type'];
            $companyType = $_POST['company-type'];

            // Initial data
            // Get list similar words
            $listBasicWords = MapData::getListBasicWords();
            $arrDataSimilar = MapData::getListSimilar();

            $data['listIndex'] = array();
            $data['listSimilar'] = array();

            // Add basic word to list
            if ($listBasicWords) {
                foreach ($listBasicWords as $basicWord) {
                    if (!empty($basicWord['basic_word'])) {
                        $word = str_replace(' ', '', $basicWord['basic_word']);
                        $data['listIndex'][$word] = $basicWord['id'];
                        $data['listSimilar'][$basicWord['id']][]     = $word;
                    }
                }
            }

            // Rebuild array list to check
            if ($arrDataSimilar) {
                foreach ($arrDataSimilar as $similar) {
                    if (!empty($similar['similar_word'])) {
                        $word = str_replace(' ', '', $similar['similar_word']);
                        $data['listIndex'][$word]      = $similar['basic_word_id'];
                        $data['listSimilar'][$similar['basic_word_id']][] = $word;
                    }
                }
            }

            $data['hasData'] = false;
            $data['first'] = true;
            $data['clientData'] = array();
            $data['serverData'] = array();


            $error = '';

            // Process upload file
            // Check csv file
            if (!isset($_FILES['client-data']['tmp_name']) || empty($_FILES['client-data']['tmp_name'])) {
                $error = isset($message['MISSING_FILE'])?$message['MISSING_FILE']:'';
            } else {
                // Read two file and save to session
                $clientFile = $_FILES['client-data']['tmp_name'];
                //$serverFile = $_FILES['server-data']['tmp_name'];

                $data['clientData'] = Convert::GetDataFromFile($clientFile, $_FILES['client-data']['name'], 'client', $companyType);

                $lastFile = Convert::getLastFile(1);
                if ($lastFile) {
                    $data['serverData'] = json_decode($lastFile['file_datas'], true);
                }

             /*   if (!isset($_FILES['server-data']) || empty($_FILES['server-data']['tmp_name'])) {
                    // Get server data from database
                    $data['serverData'] = array();

                    $lastFile = Convert::getLastFile(1);
                    if ($lastFile) {
                        $data['serverData'] = json_decode($lastFile['file_data'], true);
                    }

                } else {
                    $data['serverData'] = Convert::GetDataFromFile($serverFile, $_FILES['server-data']['name'], 'server');
                }*/

                if ($data['clientData'] && $data['serverData']) {

                    //New line character replaced of Server data
                    $titles = array_shift($data['serverData']);
                    foreach ($titles as $key => $title) {
                        $titles[$key] = preg_replace("/\s/", '', $title);
                    }
                    array_unshift($data['serverData'], $titles);

                    //New line character replaced of Client data
                    $clientTitles = array_shift($data['clientData']);
                    foreach ($clientTitles as $clientKey => $clientTitle) {
                        $clientTitles[$clientKey] = preg_replace("/\s/", '', $clientTitle);
                    }
                    array_unshift($data['clientData'], $clientTitles);


                    // Set data
                    $_SESSION['serverData'] = $data['serverData'];
                    $_SESSION['clientData'] = $data['clientData'];
                    $data['hasData'] = true;

                    // Get list matchCount
                    $data['matchCount'] = MatchCount::getList();
                }
            }

            $data['first'] = false;
        }

        // Render home page
        return $this->render('home', $data);
    }

    //View Map Data page
    public function viewMapData(){
        $data['serverData'] = isset($_SESSION['serverData'])?$_SESSION['serverData']:array();
        $data['clientData'] = isset($_SESSION['clientData'])?$_SESSION['clientData']:array();


        // Initial data
        // Get list similar words
        $listBasicWords = MapData::getListBasicWords();
        $arrDataSimilar = MapData::getListSimilar();

        // Add basic word to list
        if ($listBasicWords) {
            foreach ($listBasicWords as $basicWord) {
                if (!empty($basicWord['basic_word'])) {
                    $word = str_replace(' ', '', $basicWord['basic_word']);
                    $data['listIndex'][$word] = $basicWord['id'];
                    $data['listSimilar'][$basicWord['id']][]     = $word;
                }
            }
        }

        // Rebuild array list to check
        if ($arrDataSimilar) {
            foreach ($arrDataSimilar as $similar) {
                if (!empty($similar['similar_word'])) {
                    $word = str_replace(' ', '', $similar['similar_word']);
                    $data['listIndex'][$word]      = $similar['basic_word_id'];
                    $data['listSimilar'][$similar['basic_word_id']][] = $word;
                }
            }
        }

        $data['hasData'] = false;
        $data['first'] = true;

        if ($data['clientData'] && $data['serverData']) {

            $titles = array_shift($data['serverData']);
            foreach ($titles as $key => $title) {
                $titles[$key] = preg_replace("/\s/", '', $title);
            }
            array_unshift($data['serverData'], $titles);

            // Set data
            $_SESSION['serverData'] = $data['serverData'];
            $_SESSION['clientData'] = $data['clientData'];
            $data['hasData'] = true;

            // Get list matchCount
            $data['matchCount'] = MatchCount::getList();
        }

        $data['first'] = false;

        return $this->render('home', $data);
    }

    //Home page
    public function page(){
        //Member::checkLogin();
        $data = array();
        $data['buyer'] = BuyerAndSellerModel::buyer();
        $data['seller'] = BuyerAndSellerModel::seller();
        return $this->render('home_page', $data);
    }

    /**
     * User login
     * @return string
     */
    public function login()
    {
        if ($this->isPost()) {
            // Check login
            if (isset($this->data['username']) && isset($this->data['password'])) {
                if (Member::login($this->data['username'], $this->data['password'])) {
                    // Redirect to home page
                    $this->redirect('index.php?action=page');
                } else {
                    // Login false
                }
            }
        }
        // Render login page
        return $this->render('login');
    }

    /**
     * User logout
     */
    public function logout()
    {
        // Clear session, redirect login page
        unset($_SESSION['convert_member']);

        $this->redirect('index.php?action=login');
    }

    /**
     * Action config similar word
     */
    public function configSimilarWord()
    {
        $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
        $limit = (isset($_GET['limit'])) ? (int)$_GET['limit'] : 15;

        $data = array();

        // Get list map data
        $data['listMapData'] = MapData::getListBasic('', $page, $limit);

        // Get list id
        $basicIds = array();
        foreach ($data['listMapData'] as $item) {
            $basicIds[] = $item['id'];
        }

        // Get list similar by list basic words
        $data['arrSimilar'] = MapData::getListSimilarByListBasic($basicIds);

        $data['totalRow'] = MapData::countListBasic();

        $data['totalPage'] = ceil($data['totalRow'] / $limit);

        return $this->render('similar-word', $data);
    }

    /**
     * Ajax request compare data
     */
    public function compareData()
    {
        $data = [];
        $data['mapsData'] = $this->data;

        $reportType = $_SESSION['ReportType'];

        $sessionKeyName = $reportType == 'maker' ? 'clientMakerMapData' : 'clientSellerMapData';

        $data['clientMapData'] = Convert::MapData(isset($_SESSION['clientData'])?$_SESSION['clientData']:'', $this->data);

        // Set session
        if ($data['clientMapData']) {
            $_SESSION[$sessionKeyName] = $data['clientMapData'];
        }

        $clientDataArr = $_SESSION[$sessionKeyName];
      //Table edited data for Buyer and Seller
        if (isset($_POST['janCode'])){
            $janCode = $_POST['janCode'];
            $fieldName = $_POST['fieldName'];
            $cellValue = $_POST['cellValue'];
            $id = $_POST['id'];

            foreach ($clientDataArr as $key => $value) {
               if ($key == $id) {
                   $clientDataArr[$key][$fieldName] = $cellValue;
                   $_SESSION[$sessionKeyName] = $clientDataArr;
                   break;
               }
            }
        }


        // Update match count for matchData
/*        foreach ($this->data as $key => $serverWord) {
            MatchCount::addMatchCount($serverWord);
        }

 */
        // Get matched data
        //$data['insertedData'] = Convert::InsertLinkData($data['clientMapData']);

        $data['clientMapData'] = $_SESSION[$sessionKeyName];

        //var_dump($_SESSION);
        return $this->render('compared-data-page', $data);
    }

    public function finalCompareData()
    {

        $array1 = isset($_SESSION['clientMakerMapData'])?$_SESSION['clientMakerMapData']:array();
        $array2 = isset($_SESSION['clientSellerMapData'])?$_SESSION['clientSellerMapData']:array();

/*        $editFields = array('JAN', '商品名', '規格', '発注 単位', '原価 （税抜）', '売価 （税抜）', 'メーカー名', '発売日', '賞味期限');
        $nonEditFields = array("包装形態","保存温度","税込価格","縦","横","奥行","発売予定","新・リ");

        $rowCount = max(count($array1), count($array2));
        $array3 = array();

        for ($key=1; $key<=$rowCount; $key++) {
            $aData = isset($array1[$key]) ? $array1[$key] : array();
            $bData = isset($array2[$key]) ? $array2[$key] : array();
            $cData = array();

            foreach($editFields as $fieldName) {
                $cData[$fieldName] = !empty($aData[$fieldName]) ? $aData[$fieldName] : '';
            }

            foreach($nonEditFields as $fieldName) {
                $cData[$fieldName] = !empty($bData[$fieldName]) ? $bData[$fieldName] : '';
            }

            $array3[] = $cData;
        }*/

        //$donkiTitle = array('新・リ', '品名', '量目', '入数	', 'ＪＡＮＣＤ ＜4901231＞', '包装形態', '賞味 期間', '保存 温度', '卸', '本体価格案', '値入％', '希望小売価格', '税込価格', '縦', '横', '奥行', '発売予定', '発売の狙い・コンセプト');
        $standardAccordingToTdonki = array(/*'ブランド/セグメント', */'新・リ', '商品名', '規格', '発注単位', 'JAN', '包装形態', '賞味期限', '保存温度', '卸', '原価（税抜）', '値入％', '売価（税抜）', '税込価格', '縦', '横', '奥行', '発売予定'/*, '発売の狙い・コンセプト'*/);

        $data['clientMapData'] = $array1;
        $_SESSION['donkiTitle'] = $standardAccordingToTdonki; //For export excel
        $data['donkiTitle'] = $standardAccordingToTdonki;

        //$data['clientMapData'] = $array3;
        //$data['clientMapData'] = array_merge($_SESSION['clientMakerMapData'], $_SESSION['clientSellerMapData']);
        /*var_dump($_SESSION);
        exit;*/
        return $this->render('final-compared-data', $data);
    }


    /**
     * Export final compared data BBB
     */
    public function exportFinalComparedData(){
        $cSheetTitles = array();
        //$donkiTitle = array('新・リ', '品名', '量目', '入数	', 'ＪＡＮＣＤ ＜4901231＞', '包装形態', '賞味 期間', '保存 温度', '卸', '本体価格案', '値入％', '希望小売価格', '税込価格', '縦', '横', '奥行', '発売予定', '発売の狙い・コンセプト');

        $cSheetTitlesRow1 = array('ブランド/セグメント', '新・リ', '品名', '量目', '入数', 'ＪＡＮＣＤ ＜4901231＞', '包装形態', '賞味期間', '保存温度', '税別', '','','', '税込価格', '商品サイズ（ｍｍ）','','','','', '発売予定', '発売の狙い・コンセプト');

        $cSheetTitlesRow2 = array('', '', '', '', '', '', '', '', '','卸', '本体価格案', '値入％	', '希望小売価格','', '縦','','横','','奥行', '','');

        $donkiTitle = array();
        $clientMakerMapData = array();
        $donkiTitle = isset($_SESSION['donkiTitle'])?$_SESSION['donkiTitle']:array();
        $clientMakerMapData = isset($_SESSION['clientMakerMapData'])?$_SESSION['clientMakerMapData']:array();

        //$serverData = $_SESSION['serverData'];
        //$titles = reset($serverData);

        $exportData = array();

        $exportData[0] = $cSheetTitlesRow1;
        $exportData[1] = $cSheetTitlesRow2;

        //die(var_dump($clientMakerMapData));

        foreach ($clientMakerMapData as $k => $row) {
            $rowData = array();
            $i = 0;

            foreach ($donkiTitle as $title) {

                //Jan Code 7 digits showing
                if ($title=='JAN'){
                    //echo $row[$title];
                    $rowData[$title] = substr($row[$title], 7);
                }else{
                    $rowData[$title] = isset($row[$title]) ? $row[$title] : '';
                }

                //x Sign print
                if ($i == 13) {
                    $rowData['x1'] = 'x';
                } else if ($i == 14) {
                    $rowData['x2'] = 'x';
                }
                $i++;
            }//Cols

            $exportData[] = $rowData;
        }//Rows

        $nn = count($exportData);
        for ($i=2; $i<$nn; $i++){
            array_unshift($exportData[$i], '');
        }


        //die(var_dump($exportData));


        // array_unshift($exportData, $cSheetTitlesRow1, $cSheetTitlesRow2);
        //array_unshift($exportData[2], '');



        // Set value binder
        PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Anonimous")
            ->setLastModifiedBy("Anonimous")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $activeSheet = $objPHPExcel->setActiveSheetIndex(0);
        //$headers = array_keys(reset($exportData));
        //array_unshift($exportData, $headers);

        $activeSheet->fromArray($exportData, NULL, 'A1');

        //Set Fonts
        $objPHPExcel->getDefaultStyle()->getFont()->setName('ＭＳ Ｐゴシック');
        PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);

        /*$objPHPExcel->getActiveSheet()->getStyle('A1:U4')
            ->getAlignment()->setWrapText(true);*/

        //Merge cells
        $activeSheet->mergeCells('A1:A2');
        $activeSheet->mergeCells('B1:B2');
        $activeSheet->mergeCells('C1:C2');
        $activeSheet->mergeCells('D1:D2');
        $activeSheet->mergeCells('E1:E2');
        $activeSheet->mergeCells('F1:F2');
        $activeSheet->mergeCells('G1:G2');
        $activeSheet->mergeCells('H1:H2');
        $activeSheet->mergeCells('I1:I2');

        $activeSheet->mergeCells('J1:M1');

        $activeSheet->mergeCells('N1:N2');

        $activeSheet->mergeCells('T1:T2');
        $activeSheet->mergeCells('U1:U2');
        /* $activeSheet->mergeCells('O1:O2');
        $activeSheet->mergeCells('P1:P2');
        $activeSheet->mergeCells('Q1:Q2');*/
        /*$activeSheet->mergeCells('R1:R2');
        $activeSheet->mergeCells('S1:S2');*/

        $activeSheet->mergeCells('O1:S1');

        $n = count($exportData)+2;
        $activeSheet->mergeCells("A3:A$n");
        $activeSheet->mergeCells("U3:U$n");


        /*//Set Width
        for($col = 'A'; $col !== 'U'; $col++) {
            $activeSheet->getColumnDimension($col)
                ->setRowHeight(40);
        }*/


        //Set Width and height
        //set height
          /*$bb = count($exportData) + 2;
           for($col =3 ; $col <=$bb; $col++) {
               $activeSheet->getRowDimension("$col")->setRowHeight(20.75);
           }*/



        // Default Styles
        $defaultStyle = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
            )
        );
        //Default style
        $objPHPExcel->getDefaultStyle()->applyFromArray($defaultStyle);

        //Title style
        $titleStyle = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'wrap' => true
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'CCFFCC')
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        //Title style
        $activeSheet->getStyle("A1:U2")->applyFromArray($titleStyle);

        //After title style
        $afterTitleStyle= array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        //After Title style
        $n = count($exportData) + 2;
        $activeSheet->getStyle("A3:U$n")->applyFromArray($afterTitleStyle);

//        for($i=3; $i<=$n; $i++){
//            $activeSheet->getStyle("A$i:U$i")->applyFromArray($afterTitleStyle);
//        }

        //No specific cell border
       $noBorder = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_NONE
                )
            )
        );
        //No specific cell border
        $activeSheet->getStyle("O2:S$n")->applyFromArray($noBorder);

        //Horizontal cell border
        $horizontalBorder = array(
            'borders' => array(
                'horizontal' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        //Horizontal cell border
        for($hori=3; $hori<=$n; $hori++){
            $activeSheet->getStyle("O$hori:S$n")->applyFromArray($horizontalBorder);
        }


        //background color O-S
        $Nobackgroundcolor = array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'FFFFFF')
            ),
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
            )
        );

        //background color O-S
        $activeSheet->getStyle("O3:S$n")->applyFromArray($Nobackgroundcolor);

        //Set last cell border
        $setLastBorder = array(
            'borders' => array(
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        //Set last cell border
        $activeSheet->getStyle("O$n:S$n")->applyFromArray($setLastBorder);

        //Set last cell border of title
        $setLastTitleBorder = array(
            'borders' => array(
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        //Set last cell border of title
        $activeSheet->getStyle("O2:S2")->applyFromArray($setLastTitleBorder);

        //Set cell width
        $activeSheet->getColumnDimension('P')->setWidth(2.5);
        $activeSheet->getColumnDimension('R')->setWidth(2.5);
        $activeSheet->getColumnDimension('C')->setWidth(38.14);
        $activeSheet->getColumnDimension('F')->setWidth(13.25);
        $activeSheet->getColumnDimension('A')->setWidth(20);
        $activeSheet->getColumnDimension('U')->setWidth(20);

        //Alignment
        $objPHPExcel->getActiveSheet()
            ->getStyle("C3:C$n")
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $objPHPExcel->getActiveSheet()
            ->getStyle("A3:A$n")
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $objPHPExcel->getActiveSheet()
            ->getStyle("B3:B$n")
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()
            ->getStyle("U3:U$n")
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        $objPHPExcel->getActiveSheet()
            ->getStyle("T3:T$n")
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()
            ->getStyle("N3:N$n")
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $alignmentAtoI = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'wrap' => false
            )
        );
        $activeSheet->getStyle("D3:I$n")->applyFromArray($alignmentAtoI);


        $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $writerName = 'Excel2007';

        $fileNameDate = date('Y-m-d-H-i-s');

        $fullName = "Report_$fileNameDate.xlsx";

        // Redirect output to a clientâ€™s web browser (Excel5)
        header('Content-Type: '.$contentType);
        header('Content-Disposition: attachment;filename="'.$fullName.'"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $writerName);
        $objWriter->save('php://output');
    }

    /**
     * Ajax request compare data
     */
    public function exportCompareData()
    {
        $mapsData = json_decode($this->data['mapsData'], true);

        $clientMapData = Convert::MapData($_SESSION['clientData'], $mapsData);
        // Set session
        if ($clientMapData) {
            $_SESSION['clientMapData'] = $clientMapData;
        }

        // Update match count for matchData
        foreach ($mapsData as $key => $serverWord) {
            MatchCount::addMatchCount($serverWord);
        }

        // Get matched data
        //$data['insertedData'] = Convert::InsertLinkData($data['clientMapData']);

        // Get server data
        $serverData = $_SESSION['serverData'];
        $titles = reset($serverData);

        $exportData = array();

        foreach ($clientMapData as $k => $row) {
            $rowData = array();
            foreach ($titles as $title) {
                $rowData[$title] = isset($row[$title]) ? $row[$title] : '';
            }
            $exportData[] = $rowData;
        }


        // Set value binder
        PHPExcel_Cell::setValueBinder( new PHPExcel_Cell_AdvancedValueBinder() );

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Anonimous")
            ->setLastModifiedBy("Anonimous")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $activeSheet = $objPHPExcel->setActiveSheetIndex(0);
        $headers = array_keys(reset($exportData));
        array_unshift($exportData, $headers);

        $activeSheet->fromArray($exportData, NULL, 'A1');

        $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $writerName = 'Excel2007';
        $fullName = "exportCompareData.xlsx";

        // Redirect output to a clientâ€™s web browser (Excel5)
        header('Content-Type: '.$contentType);
        header('Content-Disposition: attachment;filename="'.$fullName.'"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $writerName);
        $objWriter->save('php://output');
        exit;
    }



    public function updateSimilarCounter()
    {
        if (isset($this->data['word']) && !empty($this->data['word']) && is_numeric($this->data['basic_id'])) {
            // Check similar word
            $similarWord = MapData::getSimilarByWord($this->data['basic_id'], $this->data['word']);

            if ($similarWord) {

                // Update counter
                return MapData::updateCounterSimilar($similarWord['id']);
            }
        }

        return false;
    }

    /**
     * Do add similar word
     */
    public function addSimilar()
    {
        if (!empty($this->data) && isset($this->data['basic_word'])) {
            // Check basic word exist
            $basicWord = MapData::getBasicByWord($this->data['basic_word']);

            if (!$basicWord) {
                // Basic word not exist, create new basic word
                $basicWord = MapData::addBasicWord(array('basic_word' => $this->data['basic_word']));
            }

            // Add similar
            if (!empty($this->data['similar_words'])) {
                foreach ($this->data['similar_words'] as $similar) {
                    // Check similar word exist
                    $similarWord = MapData::getSimilarByWord($basicWord['id'], $similar);



                    if (!$similarWord) {
                        // Add new similar
                        MapData::AddSimilarWord(array(
                            'basic_word_id' => $basicWord['id'],
                            'basic_word'    => $basicWord['basic_word'],
                            'similar_word'  => $similar
                            ));
                    }
                }
            }

            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false]);
    }

    /**
     * Do update similar word
     */
    public function editSimilar()
    {
        if (isset($this->params['id'])) {
            // Get basic word
            $basicWord = MapData::getBasic($this->params['id']);

            if ($basicWord) {
                if ($this->isPost() && !empty($this->data)) {
                    // Update exist similar word
                    if (isset($this->data['old_similar'])) {
                        foreach ($this->data['old_similar'] as $key => $similar) {
                            if (empty($similar)) {
                                // Remove similar word
                                MapData::removeSimilarWord($key);
                            } else {
                                // Update similar word
                                MapData::updateSimilarWord($key, array(
                                    'basic_word'   => $this->data['basic_word'],
                                    'similar_word' => $similar
                                    ));
                            }
                        }
                    }

                    // Update new similar word
                    if (isset($this->data['new_similar'])) {
                        foreach ($this->data['new_similar'] as $key => $similar) {
                            if (!empty($similar)) {
                                // Add similar word
                                MapData::AddSimilarWord(array(
                                    'basic_word_id'     => (int)$this->params['id'],
                                    'basic_word'   => addslashes(trim($this->data['basic_word'])),
                                    'similar_word' => addslashes(trim($similar))
                                    ));
                            }
                        }
                    }

                    // Update basic word if has change
                    if (isset($this->data['basic_word']) && $this->data['basic_word'] != $this->data['basic_word']) {
                        MapData::updateBasicWord($this->params['id'], $this->data);
                    }

                    return json_encode(['success' => true]);
                } else {
                    // Get list similar

                    // Render edit form
                    return $this->render('edit-similar', $basicWord);
                }
            }
        }

        return json_encode(['success' => false]);
    }

    /**
     * Remove basic word and list similar
     *
     * @return bool
     */
    public function removeMapData()
    {
        if (isset($this->data['id']) && !empty($this->data['id'])) {
            // Check basic word exit
            $basicWord = MapData::getBasic($this->data['id']);

            if ($basicWord) {
                // Remove all similar
                MapData::removeSimilarByBasicWordId($this->data['id']);
                // Remove basic word
                MapData::removeBasicById($this->data['id']);
                return json_encode(['success' => true]);
            }
        }

        return json_encode(['success' => false]);
    }
}