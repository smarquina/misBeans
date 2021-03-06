<?php

namespace BaseBundle\Tests\Entity;

use BaseBundle\Entity\UserPartida;

class UserPartidaEntityTest extends Entity
{

    public function testUserPartida()
    {
        parent::mock();

        $player = new UserPartida($this->partida, $this->user, 40, 60, 45, 50);
        $player->setFUtilidad(2250);

        //mokear el repositorio para que devuelva esta partida
        $this->repository->expects($this->once())
            ->method('find')
            ->will($this->returnValue($player));

        // mockear el  EntityManager para que devuelva el repositorio mockeado
        $this->emMod->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($this->repository));

        $jugador = $this->emMod->getRepository('BaseBundle:UserPartida')->find(0);

        $this->assertEquals('test1?A', $jugador->getIdPartida()->getNombre());
        $this->partida->setNombre('test2?B');
        $player->setIdPartida($this->partida);
        $player->setFUtilidad(200);
        $this->assertEquals('test2?B', $jugador->getIdPartida()->getNombre());
        $this->assertEquals(200, $jugador->getFUtilidad());
    }

    public function testUserPartidas()
    {

        $jugadores = $this->em->getRepository('BaseBundle:UserPartida')->findAll();
        $this->assertTrue(count($jugadores) > 0);
    }

    public function testFindMisPardidas()
    {
        $partidas = $this->em->getRepository('BaseBundle:UserPartida')->findMisPardidas(7);
        $id = $partidas[0]['id'];
        $this->assertEquals(1, $id);
    }

    public function testFindAllFriends()
    {
        $jugadores = $this->em->getRepository('BaseBundle:UserPartida')->findAllFriends(1);
        $j1 = $jugadores[0]['id_user'];
        $j2 = $jugadores[1]['id_user'];


        $this->assertEquals(7, $j1);
        $this->assertEquals(8, $j2);
    }

    public function testfindByIDS(){
        $userPartida = $this->em->getRepository('BaseBundle:UserPartida')->findByIDS(7,1);
        $user = $this->em->getRepository('BaseBundle:User')->findOneById(7);
        $this->assertEquals($user->getId(), $userPartida->getIdUser());
    }


    public function testDistributeBeans()
    {
        $this->em->getRepository('BaseBundle:UserPartida')->distributeBeans(7, 7, 100, 100, 10000);
        $result = $this->em->getRepository('BaseBundle:UserPartida')->distributeBeans(7, 7, 10, 10, 100);
        $this->assertEquals(0, $result);
    }

    public function testRanking()
    {
        $ranking = $this->em->getRepository('BaseBundle:UserPartida')->getRanking(1);
        $name = $ranking[0]['username'];
        $this->assertEquals('petete', $name);
    }

}