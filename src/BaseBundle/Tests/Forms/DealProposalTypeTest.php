<?php

namespace BaseBundle\Tests\Forms;


use BaseBundle\Form\Type\DealProposalType;

class DealProposalTypeTest extends Type
{
    public function testForm(){
        $partida = array('alu_roja_actual' => 100, 'alu_blanca_actual'=> 200);

        $type = new DealProposalType($partida);
        $form = $this->formFactory->create($type);
        $formData = array(
            'aluBlancaIn' => 2,
            'aluRojaIn' => 8,
            'aluBlancaOut' => 8,
            'aluRojaOut' => 2
        );

        $form->submit($formData);
        $data = $form->getData();

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
        $this->assertEquals($data['aluBlancaIn'], 2);
    }
}