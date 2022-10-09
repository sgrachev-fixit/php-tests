<?php
class FluentCalculator
{

  public static function init() {
        $calc = new FlueCalc();        
        return $calc;    
  }

  // you can define 2 (two) more methods

}

class FlueCalc {

    public $chain_data = [];

    private $calc_action_table = [
        "zero"      =>      "0",
        "one"       =>      "1",
        "two"       =>      "2",
        "three"     =>      "3",
        "four"      =>      "4",
        "five"      =>      "5",
        "six"       =>      "6",
        "seven"     =>      "7",
        "eight"     =>      "8",
        "nine"      =>      "9",
        "plus"      =>      "+",
        "minus"     =>      "-",
        "times"     =>      "*",
        "dividedBy" =>      "/",
    ];

    private function add_action($action) {

        if (array_key_exists($action, $this->calc_action_table)) {
            array_push($this->chain_data, $action);
            return $this;
        } else {
            throw new InvalidInputException();

        }

    }

    public function __get($action){
        $this->add_action($action);
        return $this;
    }   
    
    public function __call($action, $arguments) {
        $this->add_action($action);
        $this->add_action("plus");
        $calc_result = intval($this->calculate());        
        return $calc_result;

    }

    private function is_value($action) {
        if (is_numeric($action)) {
            return true;
        } else {
            return false;
        }
    }

    private function is_overflow($value) {
        if (strlen(abs($value))>9) {
            return true;
        } else {
            return false;
        }
    }

    private function calculate () {

        $num1 = "0";
        $action = "";
        $num2 = "";
        $result = 0;
        foreach ($this->chain_data as $value) {
            $converted_value = $this->calc_action_table[$value];
            if ($this->is_value($converted_value)) {
                if ($action=="") {
                    $num1 = $num1.$converted_value;
                    if ($this->is_overflow($num1)) {
                         throw new DigitCountOverflowException();
                    }
                } else {
                    $num2 = $num2.$converted_value;
                    if ($this->is_overflow($num2)) {
                         throw new DigitCountOverflowException();
                    }
                }                
            } else {
                if (strval($action)=="") {
                    $action = $converted_value;
                } else {                    
                    if ((strval($num1)!="") && (strval($action)!="") && (strval($num2)!="")) {
                        //time to calculate
                        switch ($action) {
                            case "+":
                                $temp_result = $num1 + $num2;
                                break;
                            case "-":
                                $temp_result = $num1 - $num2;
                                break;
                            case "*":
                                $temp_result = $num1 * $num2;
                                break;
                            case "/":
                                if (intval($num2) == 0) {
                                     throw new DivisionByZeroException();
                                }
                                $temp_result = $num1 / $num2;
                                break;
                        }
                        $temp_result = intval($temp_result);
                        if ($this->is_overflow($temp_result)) {
                             throw new DigitCountOverflowException();
                        }
                        $num1 = $temp_result;                        
                        $action = $converted_value;
                        $num2 = "";
                        $temp_result = "";
                    } else {
                        if (strval($num2) == "") {
                            $action = $converted_value;
                            if (strval($num1) == "-") { $num1 = ""; }
                        }
                         
                        if (strval($num1) == "") {
                            $num1 = $num2;
                            $action = $converted_value;
                            $num2 = "";
                        }                        
                    }          
                }                

            }
        }
        if (strval($num2)=="") {
            return $num1;
        } else {
            return $num2;
        }
    }

}
?>