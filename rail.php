<?php
function encodeRailFenceCipher($string, $numberRails) {
    if (strval($string) == "") { return ""; }
    if (is_numeric($numberRails)) {
        if ($numberRails<2) { return "";}
    } else { 
        return "";
    }
    $rails_data = [];

    $direction_down = true;
    $current_rail = 1;
    for ($i=0; $i < strlen($string); $i++) {

        if (array_key_exists($current_rail, $rails_data)) {
            $rails_data[$current_rail] = $rails_data[$current_rail].substr($string,$i,1);
        } else {
            $rails_data[$current_rail] = substr($string,$i,1);
        }
        if ($direction_down) {
            if ($current_rail==$numberRails) {
                $direction_down = !$direction_down;
                $current_rail--;
            } else {
                $current_rail++;
            }    
        } else {
            if ($current_rail==1) {
                $direction_down = !$direction_down;
                $current_rail++;
            } else {
                $current_rail--;
            }    
        }
    }
    $ret_text = "";
    foreach ($rails_data as $single_rail_data) {
        $ret_text = $ret_text.$single_rail_data;
    }
    return $ret_text;
}


function decodeRailFenceCipher($string, $numberRails) {
    if (strval($string) == "") { return ""; }
    if (is_numeric($numberRails)) {
        if ($numberRails<2) { return "";}
    } else { 
        return "";
    }

    $ret_text = "";
    $ret_arr = [];
    $pos_lim = $numberRails *2 - 2;
    $pos_offset = $pos_lim;
    $pos_text = 0;
    for ($i=0; $i < $numberRails ; $i++) {
        $pos_offset_1 = $pos_lim - $i*2;
        $pos_offset_2 = $pos_lim - $pos_offset_1;
        if ($pos_offset_1 == 0) { $pos_offset_1 = $pos_lim; }
        if ($pos_offset_2 == 0) { $pos_offset_2 = $pos_lim; }
        $pos_offset = $i;
        $direction_down = true;
        while ($pos_offset<strlen($string)) {
            $ret_arr[$pos_offset] = substr($string,$pos_text,1);
            if ($direction_down) {
                $pos_offset = $pos_offset + $pos_offset_1;
            } else {
                $pos_offset = $pos_offset + $pos_offset_2;                
            }
            $direction_down = !$direction_down;
            $pos_text++;
        }
    }

    for ($i=0; $i<count($ret_arr);$i++) {
        $ret_text = $ret_text.$ret_arr[$i];
    }

    return $ret_text;

  }
  ?>