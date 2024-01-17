<?php
    // POSTデータを取得する
    function get_post($key)
    {
        $ret = '';

        if(isset($_POST[$key])){
            $ret = trim($_POST[$key]);
        }
    
        return $ret;
    }

    // 文字列の長さをチェックする
    function check_words($word, $minlength, $maxlength) 
    {
        $len = mb_strlen($word);

        if($len >= $minlength && $len <= $maxlength) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
