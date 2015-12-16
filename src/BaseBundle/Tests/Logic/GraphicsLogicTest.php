<?php

namespace BaseBundle\Tests\Logic;


use BaseBundle\Controller\Logic\GraphicsLogic;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GraphicsLogicTest extends KernelTestCase
{
    private $graphicsLogic;

    protected function setUp()
    {
        static::bootKernel();
        $this->graphicsLogic = new GraphicsLogic();
    }

    public function testBeansStatus()
    {
        $data = array('aluRojaIn' => 10, 'aluRojaOut' => 0, 'aluBlancaIn' => 0, 'aluBlancaOut' => 10);
        $mode = 1;
        $resultado = $this->graphicsLogic->beansStatus($data, $mode);
        $esperado = array('aluRoja' => 10, 'aluBlanca' => -10);
        $this->assertEquals($resultado, $esperado);

        $mode = 2;
        $resultado = $this->graphicsLogic->beansStatus($data, $mode);
        $esperado = array('aluRoja' => -10, 'aluBlanca' => 10);
        $this->assertEquals($resultado, $esperado);
    }

    public function testRandomColor(){
        $resultado = $this->graphicsLogic->randomColor();
        $patron = '/^#{1}\S{5}/';
        $this->assertEquals(true, preg_match($patron,$resultado));
    }
}