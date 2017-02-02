<?php
namespace Actions;

use Library\Presentation;

class App extends Presentation {

    /**
     * Run home page
     */
    public function home()
    {
        Member::checkLogin();

        $data = array();

        // Has file upload
        if (isset($_FILES) && !empty($_FILES)) {
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
                $error = $message['MISSING_FILE'];
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
                    $this->redirect('index.php?action=home');
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
        $data['clientMapData'] = Convert::MapData($_SESSION['clientData'], $this->data);
        // Set session
        if ($data['clientMapData']) {
            $_SESSION['clientMapData'] = $data['clientMapData'];
        }

        // Update match count for matchData
        foreach ($this->data as $key => $serverWord) {
            MatchCount::addMatchCount($serverWord);
        }

        // Get matched data
        //$data['insertedData'] = Convert::InsertLinkData($data['clientMapData']);

        return $this->render('compare-data', $data);
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