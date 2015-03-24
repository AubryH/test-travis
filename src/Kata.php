<?php
/**
 * Created by PhpStorm.
 * User: dasaule
 * Date: 24/03/15
 * Time: 10:18
 */

class Kata {
    /**
     * Sum the numbers
     * @param $numbers
     * @return int
     */
    const BEGIN_TOK_CUSTOM_DELIMETER = "//";
    const END_TOK_CUSTOM_DELIMETER = "\n";

    const BEGIN_C_DELIM = "[";
    const END_TOK_C_DELIM = "]";

    const BASICDELIMETERS = "[,][\n]";

    const MAX_SUMMABLE_INT = 1000;


    private $logger;
    private $webservice;
    private $console;

    /**
     * @return mixed
     */
    public function getConsole()
    {
        return $this->console;
    }

    /**
     * @param mixed $console
     */
    public function setConsole($console)
    {
        $this->console = $console;
    }


    function __construct($logger, $webservice, $console)
    {
        $this->logger = $logger;
        $this->webservice = $webservice;
        $this->console = $console;
    }


    public function add($numbers){
        $input = $numbers;
        $delimeters = self::BASICDELIMETERS;

        if(substr($input,0,strlen(self::BEGIN_TOK_CUSTOM_DELIMETER)) == self::BEGIN_TOK_CUSTOM_DELIMETER){
            $input = substr($input,strlen(self::BEGIN_TOK_CUSTOM_DELIMETER));

            $nlindex = strpos($input, self::END_TOK_CUSTOM_DELIMETER);
            if ($nlindex === false) {
                return false;
            }
            $delimitersInputs = substr($input, 0, $nlindex);

            if($delimitersInputs[0] !== self::BEGIN_C_DELIM){
                $delimeters = $delimeters . self::BEGIN_C_DELIM . $delimitersInputs . self::END_TOK_C_DELIM;
            }else{
                $delimeters = $delimeters . $delimitersInputs ;
            }
        }


        $delimeters = substr($delimeters, strlen(self::BEGIN_C_DELIM), strlen($delimeters)-strlen(self::BEGIN_C_DELIM)-strlen(self::END_TOK_C_DELIM));
        $delTab = explode("][", $delimeters);



        $input = str_replace($delTab, $delTab[0], $input);


        $nums = explode(",", $input);
        $negative = array();
        $numTosum = array();
        foreach($nums as $num){
            if(substr($num,0,1) == "-"){
                array_push($negative, $num);
            }
            $num = intval($num);
            if($num > self::MAX_SUMMABLE_INT){
                array_push($numTosum, 0);
            }else{
                array_push($numTosum, $num);
            }
        }

        if(sizeof($negative)>0){
            throw new Exception("negatives not allowed '" . implode("' '", $negative) . "'");
        }

        $result = array_sum($numTosum);
        try{
            $this->logger->Write($result);
        }catch(Exception $e){
            $this->webservice->notifyLoggingError($e->getMessage());
        }


        $this->console->printMsg("The result is " . $result);
        return $result;
  }
}