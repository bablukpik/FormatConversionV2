<?php
namespace Actions;

use Library\DBHelper;

/**
 * Class MatchCount
 * Count match number for word
 */
class MatchCount
{
    public static $table = 'word_match_count';

    /**
     * Add match count for word
     *
     * @param $word
     * @param int $count
     * @return array|bool
     */
    public static function addMatchCount($word, $count = 1)
    {
        $word = addslashes(trim($word));
        // Check matchCount data
        $matchCount = DBHelper::getRow('*', self::$table, 'word = ' . "'$word'");

        if ($matchCount) {
            $updateQuery = 'UPDATE ' . self::$table . ' SET match_count = match_count + ' . (int)$count .
                ' WHERE id = ' . (int)$matchCount['id'];
            // Update match count
            DBHelper::runQuery($updateQuery);
        } else {
            // Create new match count
            $matchCount = array(
                'word' => $word,
                'match_count' => (int)$count
            );

            $matchCount['id'] = DBHelper::Insert(self::$table, $matchCount);
        }

        return $matchCount;
    }

    /**
     * Get list match count
     */
    public static function getList()
    {
        $words = DBHelper::getArrayRow('*', self::$table);

        if ($words) {
            // Return array, take word as index
            $returnArray = array();
            foreach ($words as $item) {
                $returnArray[$item['word']] = $item['match_count'];
            }

            return $returnArray;
        }

        return false;
    }
}