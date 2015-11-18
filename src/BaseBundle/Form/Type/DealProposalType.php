<?php

namespace BaseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DealProposalType extends AbstractType
{
    protected $partida;

    public function __construct(Array $partida)
    {
        $this->partida = $partida;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $partida = $this->partida;
        $builder
            ->add('aluBlancaIn', 'integer', array('required' => true,
                'label' => 'oferta.alu-blanca', 'translation_domain' => 'BaseBundle',
                'scale' => 0,
                'attr' => array('step' => '1',
                    'min' => '0'),
            ))
            ->add('aluRojaIn', 'integer', array('required' => true,
                'label' => 'oferta.alu-roja', 'translation_domain' => 'BaseBundle',
                'scale' => 0,
                'attr' => array('step' => '1',
                    'min' => '0'),
            ))
            ->add('aluBlancaOut', 'integer', array('required' => true,
                'label' => 'oferta.alu-blanca', 'translation_domain' => 'BaseBundle',
                'scale' => 0,
                'attr' => array('step' => '1',
                    'min' => '0',
                    'max' => $partida['alu_blanca_actual']),
            ))
            ->add('aluRojaOut', 'integer', array('required' => true,
                'label' => 'oferta.alu-roja', 'translation_domain' => 'BaseBundle',
                'scale' => 0,
                'attr' => array('step' => '1',
                    'min' => '0',
                    'max' => $partida['alu_roja_actual']),
            ));
    }


    public function getName()
    {
        return 'deal_proposal';
    }

}