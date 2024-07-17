<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @extends ServiceEntityRepository<Customer>
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private ParameterBagInterface $parameterBag
    )
    {

        parent::__construct($registry, Customer::class);
    }

    public function storeUsersAsCustomers(array $users)
    {
        $batchSize = $this->parameterBag->get('store_batch_size');
        $i = 1;
        $em = $this->getEntityManager();
        $em->getConnection()->beginTransaction();

        try {
            foreach ($users as $user) {
                $customer = $this->findOneByEmail($user['email']);

                if (!$customer) {
                    $customer = new Customer();
                }

                $customer->setFullName($user['name']['first'] . ' ' . $user['name']['last']);
                $customer->setEmail($user['email']);
                $customer->setGender($user['gender']);
                $customer->setPhone($user['phone']);
                $customer->setUsername($user['login']['username']);
                $customer->setPassword(md5($user['login']['password']));
                $customer->setCity($user['location']['city']);
                $customer->setCountry($user['location']['country']);
                $em->persist($customer);

                if ($i % $batchSize == 0) {
                    $em->flush();
                    $em->clear(); // Detaches all objects from Doctrine!
                }

                $i++;
            }

            $em->flush(); // Persist objects that did not make up an entire batch
            $em->getConnection()->commit();
            $em->clear();
        } catch (Exception $e) {
            $em->getConnection()->rollBack();
            throw new NotFoundHttpException('Failed to store customers');
        }
    }
}
