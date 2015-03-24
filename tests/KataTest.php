<?php
/**
 * Created by PhpStorm.
 * User: dasaule
 * Date: 24/03/15
 * Time: 10:20
 */

require_once 'src/Kata.php';
require_once 'src/Console.php';
require_once 'src/Prompter.php';

class KataTest extends PHPUNIT_Framework_TestCase{

    /*
     * @var Kata
     */
    private $katana;

    private $logger;
    private $webService;


    public function setUp(){
        $this->logger = $this->getMockBuilder('Logger')
            ->setMethods(array('Write'))
            ->getMock();

        $this->webService = $this->getMockBuilder('WebService')
            ->setMethods(array('notifyLoggingError'))
            ->getMock();

        $this->katana = new Kata($this->logger, $this->webService, new Console());
    }


    /**
     *
     */
    public function testAddEmptyString(){
        $emptyString = "";
        $this->assertEquals(0, $this->katana->add($emptyString));
    }

    public function testAddOneNumberString(){
        $fourtytwo = "42";
        $this->assertEquals(42, $this->katana->add($fourtytwo));
    }

    public function testAddTwoNumberString(){
        $fourtytwo = "21,21";
        $this->assertEquals(42, $this->katana->add($fourtytwo));
    }

    public function testAddLotOfNumberString(){
        $fourtytwo = "1,1,1,1,1,5,10,20,2";
        $this->assertEquals(42, $this->katana->add($fourtytwo));
    }

    public function testAddWithNewLine(){
        $six = "1\n2,3";
        $this->assertEquals(6, $this->katana->add($six));
    }

    public function testAddWithCustomDelim(){
        $five = "//;\n2;3";
        $this->assertEquals(5, $this->katana->add($five));
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage negatives not allowed '-3'
     */
    public function testNegative(){
        $neg = "1,-3,3";

        $result = $this->katana->add($neg);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage negatives not allowed '-1' '-3'
     */
    public function testMultipleNegative(){
        $neg = "-1,-3,3";

        $this->katana->add($neg);
    }

    public function testAddAboveThousand(){
        $two = "2,1001";
        $this->assertEquals(2, $this->katana->add($two));
    }

    public function testAddWithLongCustomDelim(){
        $five = "//[***]\n1***2***3";
        $this->assertEquals(6, $this->katana->add($five));
    }

    public function testAddWithMultipleCustomDelim(){
        $five = "//[*][%]\n1*2%3";
        $this->assertEquals(6, $this->katana->add($five));
    }

    public function testAddWithMultipleLongCustomDelim(){
        $five = "//[***][%]\n1***2%3";
        $this->assertEquals(6, $this->katana->add($five));
    }


    // KATA 2

    public function test_Add_EmptyString_0islogged(){

        $this->logger->expects($this->once())
                ->method('Write')
                ->with($this->stringContains('0'));

        $this->katana->add("");
    }


    public function test_Add_LoggerExceptionMessage_WebServiceNotifyWithMessage(){

        $this->logger->expects($this->once())
            ->method('Write')
            ->will($this->throwException(new Exception("Logger error 42")));

        $this->webService->expects($this->once())
            ->method('notifyLoggingError')
            ->with($this->stringContains("Logger error 42"));


        $this->katana->add("");
    }


    public function test_Add_EmptyString_ResultOutputInConsole(){

        $console = $this->getMockBuilder('Logger')
            ->setMethods(array('printMsg'))
            ->getMock();
        $console->expects($this->once())
            ->method('printMsg')
            ->with($this->stringContains("The result is 0"));

        $this->katana->setConsole($console);

        $this->katana->add("");
    }

    public function test_UserUsePrompterToCalculate_1_2_3_andObtaint6(){


        $console = $this->getMockBuilder('Logger')
            ->setMethods(array('printMsg'))
            ->getMock();
        $console->expects($this->at(0))
            ->method('printMsg')
            ->with($this->stringContains("\n"));
        $console->expects($this->at(1))
            ->method('printMsg')
            ->with($this->stringContains("The result is 6"));
        $console->expects($this->at(2))
            ->method('printMsg')
            ->with($this->stringContains("\nanother input please\n"));



        $this->katana->setConsole($console);


        $prompter = new Prompter($this->katana);


        $prompter->prompt("scalc ‘1,2,3’");

    }

    /**
     * @throws Exception
     */
    public function test_UserUsePrompterToCalculate_1_2_3_andObtaint6_FourTimesThenLeave(){


        $console = $this->getMockBuilder('Logger')
            ->setMethods(array('printMsg','finish'))
            ->getMock();

        for($i = 0; $i<4;$i++){
            $console->expects($this->at(3*$i + 0))
                ->method('printMsg')
                ->with($this->stringContains("\n"));
            $console->expects($this->at(3*$i + 1))
                ->method('printMsg')
                ->with($this->stringContains("The result is 6"));
            $console->expects($this->at(3*$i + 2))
                ->method('printMsg')
                ->with($this->stringContains("\nanother input please\n"));
        }
        $console->expects($this->once())
            ->method('finish');



        $this->katana->setConsole($console);


        $prompter = new Prompter($this->katana);

        for($i = 0; $i<4;$i++) {
            $prompter->prompt("scalc ‘1,2,3’");
        }
        $prompter->prompt("\n");

    }

}
