<?php
/**
 * Created by PhpStorm.
 * User: dasaule
 * Date: 24/03/15
 * Time: 10:20
 */
require_once 'src/Kata.php';

class KataTest extends PHPUNIT_Framework_TestCase{


    /**
     *
     */
    public function testAddEmptyString(){
        $emptyString = "";
        $this->assertEquals(7, Kata::add($emptyString));
    }

    public function testAddOneNumberString(){
        $fourtytwo = "42";
        $this->assertEquals(42, Kata::add($fourtytwo));
    }

    public function testAddTwoNumberString(){
        $fourtytwo = "21,21";
        $this->assertEquals(42, Kata::add($fourtytwo));
    }

    public function testAddLotOfNumberString(){
        $fourtytwo = "1,1,1,1,1,5,10,20,2";
        $this->assertEquals(42, Kata::add($fourtytwo));
    }

    public function testAddWithNewLine(){
        $six = "1\n2,3";
        $this->assertEquals(6, Kata::add($six));
    }

    public function testAddWithCustomDelim(){
        $five = "//;\n2;3";
        $this->assertEquals(5, Kata::add($five));
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage negatives not allowed '-3'
     */
    public function testNegative(){
        $neg = "1,-3,3";

        $result = Kata::add($neg);
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage negatives not allowed '-1' '-3'
     */
    public function testMultipleNegative(){
        $neg = "-1,-3,3";

        $result = Kata::add($neg);
    }

    public function testAddAboveThousand(){
        $two = "2,1001";
        $this->assertEquals(2, Kata::add($two));
    }

    public function testAddWithLongCustomDelim(){
        $five = "//[***]\n1***2***3";
        $this->assertEquals(6, Kata::add($five));
    }

    public function testAddWithMultipleCustomDelim(){
        $five = "//[*][%]\n1*2%3";
        $this->assertEquals(6, Kata::add($five));
    }

    public function testAddWithMultipleLongCustomDelim(){
        $five = "//[***][%]\n1***2%3";
        $this->assertEquals(6, Kata::add($five));
    }
}
?>
