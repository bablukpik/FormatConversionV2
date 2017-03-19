<?php
namespace Actions;

use Library\Presentation;
use Library\DBHelper;
use Library\Spreadsheet_Excel_Reader;
use \PHPExcel_Cell;
use \PHPExcel_Cell_AdvancedValueBinder;
use \PHPExcel;
use \PHPExcel_IOFactory;

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
                $serverFile = $_FILES['server-data']['tmp_name'];

                $data['clientData'] = Convert::GetDataFromFile($clientFile, $_FILES['client-data']['name'], 'client');

                if (!isset($_FILES['server-data']) || empty($_FILES['server-data']['tmp_name'])) {
                    // Get server data from database
                    $data['serverData'] = array();

                    $lastFile = Convert::getLastFile(1);
                    if ($lastFile) {
                        $data['serverData'] = json_decode($lastFile['file_data'], true);
                    }

                } else {
                    $data['serverData'] = Convert::GetDataFromFile($serverFile, $_FILES['server-data']['name'], 'server');
                }

                if ($data['clientData'] && $data['serverData']) {
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
        foreach ($this->data as $key => $serverWord) {
            MatchCount::addMatchCount($serverWord);
        }

        // Get matched data
        //$data['insertedData'] = Convert::InsertLinkData($data['clientMapData']);

        $data['clientMapData'] = $_SESSION[$sessionKeyName];
        return $this->render('compared-data-page', $data);
    }

    public function finalCompareData()
    {
        $_SESSION['clientMakerMapData'];
        $_SESSION['clientSellerMapData'];

        $array1 = $_SESSION['clientMakerMapData'];
        $array2 = $_SESSION['clientSellerMapData'];

        $array2JanMap = array();

        foreach ($array2 as $key => $value) {
            $janCode = $value['JAN'];
            $array2JanMap[$janCode] = $key;
        }

        foreach($array1 as $key=>$value)
        {
            $janCode = $value['JAN'];
            if (isset($array2JanMap[$janCode])) {
                $replaceKey = $array2JanMap[$janCode];
                $array2[$replaceKey] = $value;
            }
        }

        $data['clientMapData'] = $array2;
        //$data['clientMapData'] = array_merge($_SESSION['clientMakerMapData'], $_SESSION['clientSellerMapData']);

        return $this->render('final-compared-data', $data);
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