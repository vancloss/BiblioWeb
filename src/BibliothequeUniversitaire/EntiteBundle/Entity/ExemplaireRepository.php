<?php

namespace BibliothequeUniversitaire\EntiteBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ExemplaireRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ExemplaireRepository extends EntityRepository
{
    public function generateCote($nombreExemplaires)
    {
        $arrayCote = array();
        $alphabet = str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZ");
        shuffle($alphabet);
        $nombre = rand(100,999);

        for ($i = 0; $i < $nombreExemplaires; $i++)
        {
            $arrayCote[] = $alphabet[0] . $nombre++;
            if ($this->findOneBy(array('cote' => $arrayCote[$i])))
            {
                $this->generateCote($nombreExemplaires);
            }
        }

        return $arrayCote;
    }

    public function deleteExemplairesByLivre($livre)
    {
        $query = $this->getEntityManager()->createQuery("DELETE BibliothequeUniversitaireEntiteBundle:Exemplaire e
                                                         WHERE e.livres_dupliques = :livre_id");
        $query->setParameter(':livre_id', $livre->getId());

        $result = $query->execute();
    }

    public function deleteOneExemplairesByLivre($livre)
    {
        $querySelect = $this->getEntityManager()->createQuery("SELECT e.id
                                                               FROM BibliothequeUniversitaireEntiteBundle:Exemplaire e
                                                               WHERE e.livres_dupliques = :livre_id");

        $querySelect->setParameter(':livre_id', $livre->getId());
        $querySelect->setMaxResults(1);
        $resultSelect = $querySelect->getResult();

        $query = $this->getEntityManager()->createQuery("DELETE BibliothequeUniversitaireEntiteBundle:Exemplaire e
                                                         WHERE e.livres_dupliques = :livre_id AND e.id = :exemplaire_id");

        $query->setParameter(':livre_id', $livre->getId());
        $query->setParameter(':exemplaire_id', $resultSelect);

        $result = $query->execute();
    }

    public function findExemplairesDispo(Livre $livre)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder2 = $this->getEntityManager()->createQueryBuilder();

        $emprunts = $queryBuilder->select('IDENTITY(ee.exemplaire_emprunte)')
                     ->from('BibliothequeUniversitaireEntiteBundle:Emprunt', 'ee')
                     ->getQuery()
                     ->getResult();

        $tab = array();

        foreach($emprunts as $value)
            $tab[] = $value[1];

        $queryBuilder2->select('e')
                     ->from('BibliothequeUniversitaireEntiteBundle:Exemplaire', 'e')
                     ->where('e.livres_dupliques = ?1');
                    if (count($tab) > 0){
                        $queryBuilder2->andWhere($queryBuilder->expr()->notIn('e.id', $tab));
                    }
                     $queryBuilder2->setParameter(1, $livre->getId())
        ;


        return $queryBuilder2;

    }
}
