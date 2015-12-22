<?php

namespace BaseBundle\Tests\Forms;


use BaseBundle\Entity\UserPartida;
use BaseBundle\Form\Type\DealProposalType;

class DealProposalTypeTest extends Type
{
    public function testForm()
    {
        /** @var UserPartida $userPartida */
        $userPartida = $this->em->getRepository('BaseBundle:UserPartida')->findByIDS(7, 1);
        $type = new DealProposalType($userPartida);
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