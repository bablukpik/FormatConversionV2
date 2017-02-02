<?php
namespace Actions;

use Library\DBHelper;

/**
 * Class MapData
 */
class MapData {
    public static $tableBasic = 'basic_words';
    public static $tableSimilar = 'similar_words';

    /**
     * Get list basic words
     *
     * @param string $search
     * @param int $page
     * @param int $limit
     * @return array|bool
     */
    public static function getListBasic($search = '', $page = 1, $limit = 15)
    {
        if ($page > 0) {
            $offset = ($page - 1) * $limit;
            $limit = $offset . ', ' . $limit;
        } else {
            $limit = '';
        }

        return DBHelper::getArrayRow('*', self::$tableBasic, $search, $limit);
    }

    /**
     * Get list similar word by list basicId
     * @param array $basicIds
     * @return array|bool
     */
    public static function getListSimilarByListBasic($basicIds = array())
    {
        if (!empty($basicIds) && is_array($basicIds)) {
            $basicIds = implode(',', $basicIds);

            // Get list similar
            $arrSimilar = DBHelper::getArrayRow('*', self::$tableSimilar, 'basic_word_id IN (' . $basicIds . ')');

            // Format result array
            if ($arrSimilar) {
                $result = array();
                foreach ($arrSimilar as $similar) {
                    $result[$similar['basic_word_id']][] = $similar;
                }

                return $result;
            }
        }

        return false;
    }

    public static function countListBasic($search = '')
    {
        $count = DBHelper::getRow('count(*) as total', self::$tableBasic, $search);
        return $count['total'];
    }

    /**
     * Update map data by id
     *
     * @param $id
     * @param $data
     * @return bool
     */
    public static function UpdateById($id, $data)
    {
        $updateData = array(
            'basic_word'  => $data['basic_word']
        );

        if (isset($data['similar_words']) && is_array($data['similar_words'])) {
            $updateData['similar_words'] = implode(',', $data['similar_words']);
        }

        // Update to database
        if (!$insertId = DBHelper::Update(self::$table, $updateData, 'id = ' . (int)$id)) {
            return false;
        } else {
            return $data;
        }
    }

    /**
     * Remove map data by id
     *
     * @param $id
     * @return bool|mysqli_result
     */
    public static function removeBasicById($id)
    {
        if ($id) {
            return DBHelper::Delete(self::$tableBasic, 'id = ' . (int)$id);
        } else {
            return false;
        }

    }

    /**
     * Get basic word info and list similar
     *
     * @param $id
     * @return array|bool
     */
    public static function getBasic($id)
    {
        $basic = DBHelper::getRow('*', self::$tableBasic, 'id = ' . (int)$id);

        // Get list similar for basic word:
        $arrSimilar = DBHelper::getArrayRow('*', self::$tableSimilar, 'basic_word_id = ' . $id);

        return array('basic' => $basic, 'arrSimilar' => $arrSimilar);
    }

    /**
     * Get basic word by word
     *
     * @param $word
     * @return bool
     */
    public static function getBasicByWord($word)
    {
       if (!empty($word)) {
           return DBHelper::getRow('*', self::$tableBasic, "basic_word = '" . addslashes(trim($word)) . "'");
       } else {
           return false;
       }
    }

    /**
     * Add basic word
     *
     * @param $insertData
     * @return bool|mixed
     */
    public static function addBasicWord($insertData)
    {
        if (!empty($insertData)) {
            $insertData = array(
                'basic_word' => addslashes(trim($insertData['basic_word']))
            );
            $insertData['id'] = DBHelper::Insert(self::$tableBasic, $insertData);

            if ($insertData['id']) {
                return $insertData;
            }
        }

        return false;
    }

    /**
     * Get list similar words
     *
     * @return array|bool
     */
    public static function getListBasicWords()
    {
        return self::getListBasic('', 0);
    }

    public static function getListSimilar()
    {
        $arrSimilar = DBHelper::getArrayRow('*', self::$tableSimilar);

        return $arrSimilar;
    }

    /**
     * Update basic word
     *
     * @param $id
     * @param array $updateData
     * @return bool|mysqli_result
     */
    public static function updateBasicWord($id, $updateData = array())
    {
        if (!empty($id) && is_numeric($id) && !empty($updateData)) {
            // Do update basic word
            $updateData = array(
                'basic_word' => addslashes(trim($updateData['basic_word']))
            );

            return DBHelper::Update(self::$tableBasic, $updateData, 'id = ' . (int)$id);
        }

        return false;
    }

    /**
     * Add similar word
     *
     * @param $insertData
     * @return bool|mixed
     */
    public static function AddSimilarWord($insertData)
    {
        if (!empty($insertData)) {
            return DBHelper::Insert(self::$tableSimilar, $insertData);
        }

        return false;
    }

    /**
     * Update similar word
     *
     * @param $id
     * @param array $updateData
     * @return bool|mysqli_result
     */
    public static function updateSimilarWord($id, $updateData = array())
    {
        if (!empty($id) && is_numeric($id) && !empty($updateData)) {
            // Do update similar word
            $arrUpdate = array();
            if (isset($updateData['basic_word'])) {
                $arrUpdate['basic_word'] = addslashes(trim($updateData['basic_word']));
            }
            $arrUpdate['similar_word'] = addslashes(trim($updateData['similar_word']));

            return DBHelper::Update(self::$tableSimilar, $arrUpdate, 'id = ' . (int)$id);
        }

        return false;
    }

    /**
     * Remove similar word
     *
     * @param $id
     * @return bool|mysqli_result
     */
    public static function removeSimilarWord($id)
    {
        if (!empty($id) && is_numeric($id)) {

            return DBHelper::Delete(self::$tableSimilar, 'id = ' . (int)$id);
        }

        return false;
    }

    /**
     * Remove similar word by basic word id
     *
     * @param $id
     * @return bool|mysqli_result
     */
    public static function removeSimilarByBasicWordId($id)
    {
        if (!empty($id) && is_numeric($id)) {

            return DBHelper::Delete(self::$tableSimilar, 'basic_word_id = ' . (int)$id);
        }

        return false;
    }

    /**
     * Get similar word
     *
     * @param $basicId
     * @param $similarWord
     * @return array|bool
     */
    public static function getSimilarByWord($basicId, $similarWord)
    {
        if (!empty($similarWord) && is_numeric($basicId)) {
            return DBHelper::getRow('*', self::$tableSimilar, "similar_word = '" . addslashes(trim($similarWord)) . "' AND basic_word_id = " . (int)$basicId);
        } else {
            return false;
        }
    }

    public static function updateCounterSimilar($id, $counter = 1)
    {
        if (is_numeric($id) && (int) $counter > 0) {
            $updateQuery = 'UPDATE ' . self::$tableSimilar . ' SET match_count = match_count + ' . (int)$counter .
                ' WHERE id = ' . (int)$id;

            return DBHelper::runQuery($updateQuery);
        }

        return false;
    }
}