<?php
function largeDiv($a,$b){
 
 return string_div_string($a,$b);
}

function string_div_string (string $num1, string $num2) {

   $ret_value = "";
   $show_info = false;

   $sign = get_math_sign($num1, $num2);
   $num1 = remove_math_sign($num1);
   $num2 = remove_math_sign($num2);
   
   check_division_by_zero($num2);
   if ($num1==0) { return "0"; }

   $ret_arr = remove_fractional($num1, $num2);
   
   $num1 = $ret_arr['num1'];
   $num2 = $ret_arr['num2'];        

   //remove 0
   $num1 = trim_string_left($num1);
   $num2 = trim_string_left($num2);

   if ($show_info) {
       echo "num1:".$num1."<br>";
       echo "num2:".$num2."<br>";
       echo "<br>";
   }    

   $num1_part = "";
   $trigger_dot = false;
   $div_digit = 0;
   $fractional_counter = 0;
   $test_counter = 0;

  // $num1_part = substr($num1,0,strlen($num2));
  $num1_part = substr($num1,0,strlen($num2));
  $num1 = substr($num1,strlen($num2));

  
   while ($fractional_counter<20){
   // while ($test_counter<4){        
       $test_counter++;
       
       if ($show_info) { echo "test:".$test_counter."<br>"; }
       if ($show_info) { echo "num1_part_income:".$num1_part."<br>"; }

       $while_counter = 0;
       while (($num1_part<$num2) && (strlen($num1)>0)) {   
           if ($show_info) { echo "fractional_while_1:".$fractional_counter."<br>"; }
           $num1_part = $num1_part.substr($num1,0,1);

           //if ($trigger_dot) { $fractional_counter++; }
           $num1 = substr($num1,1);
           
           if ($while_counter > 0) {
               // if (!$trigger_dot) {
               //    if ($ret_value == "") { $ret_value = "0"; }
               //     $ret_value = $ret_value.".";
               //     $trigger_dot = true;
               // } 
               $ret_value = $ret_value."0";
               if ($trigger_dot) { $fractional_counter++; }
           }
           $while_counter++;
       }

       while ($num1_part<$num2) {      
           if ($show_info) { echo "fractional_while_2:".$fractional_counter."<br>"; } 
           $num1_part = $num1_part."0";

           if ($trigger_dot) { $fractional_counter++; }
           
           if ($while_counter > 0) {
               $ret_value = $ret_value."0";

               if (!$trigger_dot) {
                   $ret_value = $ret_value.".";
                   $trigger_dot = true;                    
                   $fractional_counter++;                 
               } 

               if ($fractional_counter > 20) { break 2; }
           }

           if (!$trigger_dot) {
               $ret_value = $ret_value.".";
               $trigger_dot = true;   
               $fractional_counter++;                 
           } 

           $while_counter++;
       }

       if ($show_info) { echo "num1_part_before:".$num1_part."<br>"; }

       for ($i=2; $i<11;$i++) {
           if (string_multi_digit($num2,$i) > $num1_part) {
               $div_digit = $i - 1;
               break;
           }
       }

       $ret_value = $ret_value.$div_digit;

       $num1_part = string_sub_string($num1_part, string_multi_digit($num2,$div_digit));
       $num1_part = trim_string_left($num1_part);

       if ($show_info) {
           echo "num1_part_after:".$num1_part."<br>";
           echo "num1:".$num1."<br>";
           echo "num2:".$num2."<br>";
           echo "div_digit:".$div_digit."<br>";
           echo "fractional:".$fractional_counter."<br>";
           echo "ret_value>".$ret_value."<br>";
           echo "<br>";
       }
       if (is_only_zeros($num1) && ($num1_part=="")) {
           
           $ret_value = $ret_value.$num1;
           $num1 = "";
       }

       if (($num1_part == "0") && ($num1=="")) {break;}

   }

   $ret_value = trim_string_right($ret_value);
   $ret_value = trim_string_zeros($ret_value);
   
   $ret_value = $sign.$ret_value;
   echo "ret_value:".$ret_value."<br>";

   return ($ret_value);

}

function is_only_zeros (string $num1) {
   $zero_counter = 0;
   for ($i=0;$i<strlen($num1);$i++){
       if (substr($num1,$i,1) == "0") { $zero_counter++; }
   }
   if ($zero_counter == strlen($num1)) {
       return true;
   } else {
       return false;
   }

}

function string_multi_digit (string $num1, string $dig2) {
   $out_num = "";
   $in_mind = 0;
   for ($i=strlen($num1)-1; $i>=0; $i--) {
       $dig1 = substr($num1,$i,1);
       $dig2 = intval($dig2);
       $new_dig = $dig1 * $dig2;
       $new_dig = $new_dig + $in_mind;
       if ($new_dig>=10) {
           $in_mind = intdiv($new_dig,10);
           $new_dig = $new_dig % 10;
       } else {
           $in_mind = 0;
       }
       $out_num = $new_dig.$out_num;
   }
   if ($in_mind > 0) {
       $out_num = $in_mind.$out_num;
   }
   return $out_num;
}


function string_sub_string (string $num1, string $num2) {
   if ((strlen($num1)-strlen($num2))>=0) {
       $num2 = str_repeat("0", (strlen($num1)-strlen($num2))).$num2;
   }
   $out_num = "";
   $in_mind = 0;    
   for ($i=strlen($num1)-1; $i>=0; $i--) {
       $dig1 = substr($num1,$i,1);
       $dig2 = substr($num2,$i,1);
       if (($dig2+$in_mind) > $dig1) {
           $new_dig = $dig1 + 10 - ($dig2+$in_mind);
           $in_mind = 1;
       } else {
           $new_dig = $dig1 - ($dig2+$in_mind);
           $in_mind = 0;
       }
       $out_num = $new_dig.$out_num;
   }
   return $out_num;
}

function remove_dot (string $num1) {
   return str_replace(".","",$num1);
}


function remove_fractional (string $num1, string $num2) {

   // find count for digits before and after dot in $a
   $dot_pos_a = strpos($num1,".");
   if (!$dot_pos_a) { 
       $dig_count_after_dot_a = 0; 
   } else {
       $dig_count_after_dot_a = strlen($num1) - $dot_pos_a - 1;
   }

   // find count for digits before and after dot in $b
   $dot_pos_b = strpos($num2,".");
   if (!$dot_pos_b) { 
       $dig_count_after_dot_b = 0; 
   } else {
       $dig_count_after_dot_b = strlen($num2) - $dot_pos_b - 1;
   }

   // // remove dots from $a and $b
   $num1 = str_replace(".","",$num1);  
   $num2 = str_replace(".","",$num2);

   // add 0 after $a and $b
   if ($dig_count_after_dot_a > $dig_count_after_dot_b) {
       $num2 = $num2.str_repeat("0", $dig_count_after_dot_a - $dig_count_after_dot_b);        
   } else {
       $num1 = $num1.str_repeat("0", $dig_count_after_dot_b - $dig_count_after_dot_a);
   }

   // //remove 0
   // $num1 = trim_string_left($num1);
   // $num2 = trim_string_left($num2);

   $ret_array = array ('num1' => '', 'num2' => '' );
   $ret_array['num1'] = $num1;
   $ret_array['num2'] = $num2;

   return $ret_array;
}

function trim_string_left (string $num1) {
   $upper_bound = strlen($num1);
   for ($i=0;$i<$upper_bound;$i++) {
       $num1 = str_replace("@0","","@".$num1);
       $num1 = str_replace("@","",$num1);
   }
   return $num1;
}

function trim_string_right (string $num1) {
   $upper_bound = strlen($num1);
   for ($i=0;$i<$upper_bound;$i++) {
       $num1 = str_replace("0@","",$num1."@");
       $num1 = str_replace("@","",$num1);
   }
   return $num1;
}

function trim_string_zeros (string $ret_value) {
       $ret_value = str_replace("@.","0.","@".$ret_value);
       $ret_value = str_replace("@","",$ret_value);
       if ($ret_value=="0.") {$ret_value = "0";}
       $ret_value = str_replace(".@","",$ret_value."@");
       $ret_value = str_replace("@","",$ret_value);
   return $ret_value;
}


function get_math_sign (string $num1, string $num2) {
   $sign = "";
   if (($num1>=0) && ($num2<0)) { $sign="-";}
   if (($num1<0) && ($num2>=0)) { $sign="-";}
   if (($num1<0) && ($num2<0)) { $sign="";}   
   return $sign;
}

function remove_math_sign (string $num1) {
   return str_replace("-","",$num1);
}

function check_division_by_zero (string $num2) {
   if ($num2==0){ 
       throw new Exception("Error");
   }
}

function single_string_div_string (string $num1, string $num2) {
   
   $div = floor($num1 / $num2);

   $ret_array = array ('result' => 0, 'fraction' => 0 );
   $ret_array['result'] = $div;

   return $div;
}
?>