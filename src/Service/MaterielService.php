<?php
namespace App\Service;

use App\Entity\Materiel;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;


class MaterielService
{
    public function __construct(
            ContainerInterface $container,
            EntityManagerInterface $em
        )
    {
		$this->publicPath	= $container->getParameter('path.public');
		$this->absolutePath	= $container->getParameter('path.absolute');
        $this->em           = $em;
    }
 
       
}