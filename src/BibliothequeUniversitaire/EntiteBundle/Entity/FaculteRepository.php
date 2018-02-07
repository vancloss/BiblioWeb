<?php

namespace BibliothequeUniversitaire\EntiteBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * FaculteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FaculteRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('nom' => 'ASC'));
    }

    public function removeLecteurs(Faculte $faculte)
    {
        $query = $this->getEntityManager()->createQuery("DELETE BibliothequeUniversitaireEntiteBundle:Lecteur l
                                                         WHERE l.faculte_choisie = :faculte_id");
        $query->setParameter(':faculte_id', $faculte->getId());

        $result = $query->execute();
    }
}
