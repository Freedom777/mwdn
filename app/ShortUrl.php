<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{
    const MIN_CHAR_CNT = 5;
    const MAX_CHAR_CNT = 10;
    const CHAR_VOCABULARY = '1234567890abcdefghijklmnopqrstuvwxyz';

    protected $fillable = ['name', 'url', 'expired_at'];

    /**
     * Return random string with length between min and max from vocabulary
     * String will not repeat existing short_url table values
     *
     * @param int $min
     * @param int $max
     *
     * @return string
     */
    public static function generate(int $min, int $max) : string {
        $result = '';
        $success = false;
        $vocCount = mb_strlen(self::CHAR_VOCABULARY);
        $existRecs = ShortUrl::pluck('name')->all();

        while (!$success) {
            $charsCnt = rand($min, $max);

            for ($i = 0; $i < $charsCnt; $i++) {
                $index = rand(0, $vocCount - 1);
                $result .= mb_substr(self::CHAR_VOCABULARY, $index, 1);
            }

            $success = !in_array($result, $existRecs);
        }

        return $result;
    }
}
