<?php


namespace BaseBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Oferta.estado
 *      0 -> no tratada
 *      1 -> aceptada
 *      2 -> rechazada
 *      3 -> Oferta concluida
 *
 * Class OfertasRepository
 * @package BaseBundle\Entity
 */
class OfertasRepository extends EntityRepository
{
    /**
     * Guarda una oferta
     *
     * @param $user_id
     * @param $idPlayer
     * @param $id_partida
     * @param $data
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function SetNewOffer($user_id, $idPlayer, $id_partida, $data)
    {
        $entityManager = $this->getEntityManager();
        $connection = $entityManager->getConnection();

        $dql = "INSERT INTO ofertas (idPartida, idCreador, idDestinatario, creado, estado, aluBlancaIn, aluRojaIn, aluBlancaOut, aluRojaOut)
                VALUES (:idPartida, :userId, :idPlayer, now(), 0, :aluBlancaIn, :aluRojaIn, :aluBlancaOut, :aluRojaOut)";

        $statement = $connection->prepare($dql);

        $statement->bindValue('userId', $user_id);
        $statement->bindValue('idPlayer', intval($idPlayer));
        $statement->bindValue('idPartida', intval($id_partida));
        $statement->bindValue('aluBlancaIn', $data['aluBlancaIn']);
        $statement->bindValue('aluBlancaOut', $data['aluBlancaOut']);
        $statement->bindValue('aluRojaIn', $data['aluRojaIn']);
        $statement->bindValue('aluRojaOut', $data['aluRojaOut']);

        return $statement->execute();
    }

    /**
     * Comprueba Ofertas recibidas por idDestinatario
     *
     * @param $idDestinatario
     * @return array
     */
    public function findRecievedOffers($idDestinatario)
    {
        $entityManager = $this->getEntityManager();

        $dql = "SELECT IDENTITY(ofertas.idCreador) AS idCreador, ofertas.aluBlancaIn, ofertas.aluRojaIn, ofertas.aluBlancaOut,
                ofertas.aluRojaOut, ofertas.creado, ofertas.id, IDENTITY(ofertas.idDestinatario) AS idDestinatario,
                partida.tiempoOferta, partida.id AS idPartida, user.username
                FROM BaseBundle:Ofertas ofertas
                JOIN BaseBundle:Partida partida WITH partida.id = ofertas.idPartida
                JOIN BaseBundle:User user WITH user.id = ofertas.idCreador
                WHERE ofertas.idDestinatario = :idDestinatario AND ofertas.estado = 0";

        $query = $entityManager->createQuery($dql);
        $query->setParameter('idDestinatario', $idDestinatario);

        return $query->getResult();
    }

    /**
     * Comprueba ofertas enviadas por idCreador
     *
     * @param $idCreador
     * @return array
     */
    public function findSentOffers($idCreador)
    {
        $entityManager = $this->getEntityManager();

        $dql = "SELECT  IDENTITY(ofertas.idDestinatario) AS idDestinatario, ofertas.aluBlancaIn, ofertas.aluRojaIn,
                ofertas.aluBlancaOut, ofertas.aluRojaOut, ofertas.creado, ofertas.id, IDENTITY(ofertas.idCreador) AS idCreador,
                partida.tiempoOferta,  partida.id AS idPartida, user.username
                FROM BaseBundle:Ofertas ofertas
                JOIN BaseBundle:Partida partida WITH partida.id = ofertas.idPartida
                JOIN BaseBundle:User user WITH user.id = ofertas.idDestinatario
                WHERE ofertas.idCreador = :idCreador AND ofertas.estado = 0";

        $query = $entityManager->createQuery($dql);
        $query->setParameter('idCreador', $idCreador);

        return $query->getResult();
    }

    /**
     * Actualiza el estado de una partida y la fecha de modificación
     *
     * @param $status
     * @param $idOferta
     * @return array
     */
    public function updateStatus($status, $idOferta)
    {
        $entityManager = $this->getEntityManager();

        $dql = "UPDATE BaseBundle:Ofertas ofertas
                SET ofertas.estado = :STATUS, ofertas.modificado = CURRENT_TIMESTAMP()
                WHERE ofertas.id = :idOferta";

        $query = $entityManager->createQuery($dql);
        $query->setParameter('status', $status);
        $query->setParameter('idOferta', $idOferta);

        return $query->getResult();
    }

    /**
     * Cuenta las ofertas en curso de un usuario
     *
     * @param $idUser
     * @param $idPartida
     * @return array
     */
    public function countOffers($idUser, $idPartida)
    {
        $entityManager = $this->getEntityManager();

        $dql = "SELECT COUNT(ofertas.id) AS num, partida.maxOfertas
                FROM BaseBundle:Ofertas ofertas
                JOIN BaseBundle:Partida partida WITH partida.id = ofertas.idPartida
                WHERE ofertas.idCreador = :idUser AND ofertas.idPartida = :idPartida AND ofertas.estado =0";

        $query = $entityManager->createQuery($dql);
        $query->setParameter('idUser', $idUser);
        $query->setParameter('idPartida', $idPartida);

        return $query->getResult();
    }

    /**
     * Comprueba que la oferta buscada existe con los datos recibidos
     *
     * @param $idCreador
     * @param $idPartida
     * @param $idOferta
     * @param $idDestinatario
     * @return array
     */
    public function checkDeal($idCreador, $idPartida, $idOferta, $idDestinatario)
    {
        $entityManager = $this->getEntityManager();

        $dql = "SELECT ofertas.id
                FROM BaseBundle:Ofertas ofertas
                WHERE ofertas.idCreador = :idCreador AND ofertas.idPartida = :idPartida AND ofertas.idDestinatario = :idDestinatario
                      AND ofertas.id = :id";

        $query = $entityManager->createQuery($dql);
        $query->setParameter('idCreador', $idCreador);
        $query->setParameter('idPartida', $idPartida);
        $query->setParameter('idDestinatario', $idDestinatario);
        $query->setParameter('id', $idOferta);

        return $query->getResult();
    }

    /**
     * Eliminar una deterinada oferta
     *
     * @param $idCreador
     * @param $idPartida
     * @param $idOferta
     * @param $idDestinatario
     * @return array
     */
    public function deleteDeal($idCreador, $idPartida, $idOferta, $idDestinatario)
    {
        $entityManager = $this->getEntityManager();

        $dql = "DELETE
                FROM BaseBundle:Ofertas ofertas
                WHERE ofertas.idCreador = :idCreador AND ofertas.idPartida = :idPartida AND ofertas.idDestinatario = :idDestinatario
                      AND ofertas.id = :id AND ofertas.estado = 0";

        $query = $entityManager->createQuery($dql);
        $query->setParameter('idCreador', $idCreador);
        $query->setParameter('idPartida', $idPartida);
        $query->setParameter('idDestinatario', $idDestinatario);
        $query->setParameter('id', $idOferta);

        return $query->getResult();
    }

//todo: revisar con findSentOffers y fusion.
    public function dealsInfoSent($idPartida, $idCreador)
    {
        $entityManager = $this->getEntityManager();

        $dql = "SELECT ofertas.id, ofertas.modificado, ofertas.aluBlancaIn, ofertas.aluRojaIn, ofertas.aluBlancaOut, ofertas.aluRojaOut
                FROM BaseBundle:Ofertas ofertas
                WHERE ofertas.idCreador = :idCreador AND ofertas.idPartida = :idPartida AND ofertas.estado = 1";

        $query = $entityManager->createQuery($dql);
        $query->setParameter('idCreador', $idCreador);
        $query->setParameter('idPartida', $idPartida);

        return $query->getResult();
    }

    public function dealsInfoRecieved($idPartida, $idDestinatario)
    {
        $entityManager = $this->getEntityManager();

        $dql = "SELECT ofertas.id, ofertas.modificado, ofertas.aluBlancaIn, ofertas.aluRojaIn, ofertas.aluBlancaOut, ofertas.aluRojaOut
                FROM BaseBundle:Ofertas ofertas
                WHERE ofertas.idDestinatario = :idDestinatario AND ofertas.idPartida = :idPartida AND ofertas.estado = 1";

        $query = $entityManager->createQuery($dql);
        $query->setParameter('idDestinatario', $idDestinatario);
        $query->setParameter('idPartida', $idPartida);

        return $query->getResult();
    }

    /**
     * Todas las ofertas que se han hecho en una partida.
     *
     * @param $idPartida
     * @return array
     */
    public function findAllGameDeals($idPartida)
    {
        $entityManager = $this->getEntityManager();

        $dql = "SELECT ofertas.id, ofertas.modificado, ofertas.aluBlancaIn, ofertas.aluRojaIn, ofertas.aluBlancaOut, ofertas.aluRojaOut
                FROM BaseBundle:Ofertas ofertas
                WHERE ofertas.idPartida = :idPartida AND ofertas.estado = 1";

        $query = $entityManager->createQuery($dql);
        $query->setParameter('idPartida', $idPartida);

        return $query->getResult();
    }
}