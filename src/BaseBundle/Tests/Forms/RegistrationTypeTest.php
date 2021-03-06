<?php

namespace BaseBundle\Tests\Forms;


use BaseBundle\Entity\User;
use BaseBundle\Form\Type\RegistrationType;

class RegistrationTypeTest extends Type
{
    public function testSubmit()
    {
        $user = new User();
        $user->setFullName('NameTest');
        $user->setEmail('john@doe.com');
        $user->setPlainPassword('test');
        $user->setUsername('bar');

        $type = new RegistrationType();
        $form = $this->formFactory->create($type);
        $formData = array(
            'fullName'  => 'NameTest',
            'username' => 'bar',
            'email' => 'john@doe.com',
            'plainPassword' => array(
                'first' => 'test',
                'second' => 'test',
            )
        );
        $form->submit($formData);
        $data = $form->getData();

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isValid());
        $this->assertTrue($data instanceof $user);
        $this->assertEquals($data->getUsername(), $user->getUsername());
        $this->assertEquals($data->getEmail(), $user->getEmail());
        $this->assertEquals($data->getPlainPassword(), $user->getPlainPassword());
    }
}
