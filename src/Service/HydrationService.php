<?php
namespace App\Service;

use App\Entity\Materiel;
use App\Entity\Metier;
use App\Entity\Type as MaterielType;
use App\Entity\Fabricant;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;


class HydrationService
{
    public function __construct(
            HttpClientInterface $client,
            ContainerInterface $container,
            EntityManagerInterface $em
        )
    {
        $this->client       = $client;
		$this->apiUrl		= $container->getParameter('api.url');
		$this->apiToken		= $container->getParameter('api.token');
		$this->filesFolder	= $container->getParameter('files.folder');
		$this->publicPath	= $container->getParameter('path.public');
		$this->absolutePath	= $container->getParameter('path.absolute');
        $this->em           = $em;
    }


    /**
    * Insert les données essentiels dans la base de données.
	* @return array
	*/
    public function insertToDatabase()
    {
        // Récupération des données.
        $data = $this->getApiData()['content'];

        if ($this->emptyTable() != true)
            return [
                'success' => false,
                'message' => 'Le vidage des tables ne s\'est pas executé correctement.'
            ];

        // Préparation de la données.
        foreach ($data as $materiel) {

            $metier     = $materiel->type->metier;
            $type       = $materiel->type;
            $fabricant  = $materiel->fabricant;
            
            $sortedData['materiel'][$materiel->materiel_id]      = $materiel;
            $sortedData['metier'][$type->metier_id]              = $metier;
            $sortedData['type'][$type->type_id]                  = $type;
            $sortedData['fabricant'][$fabricant->fabricant_id]   = $fabricant;
            
        }

        // Insertion des données dans la base.
        $logs['metier']    = $this->insertFromEntity($sortedData['metier'], Metier::class);
        $logs['fabricant'] = $this->insertFromEntity($sortedData['fabricant'], Fabricant::class);
        $logs['type']      = $this->insertFromEntity($sortedData['type'], MaterielType::class, [
            'metier_id' => Metier::class
        ]);
        $logs['materiel']  = $this->insertFromEntity($sortedData['materiel'], Materiel::class, [
            'type_id'       => MaterielType::class,
            'fabricant_id'  => Fabricant::class
        ]);

        // Création du message de retour.
        $message = '';
        foreach ($logs as $log) {
            $message = $message.$log."<br>";
        }
        
        return [
            'success' => true,
            'message' => $message
        ];
    }

    /**
	* Retourne les données récupérées dans l'API
	* @return array
	*/
    private function getApiData()
    {
        // Déclaration des variables
        $array      = [];
        $lastPage   = 1;
        $i          = 1;

        // Génère une URL dynamique.
        $urlGenerate = function($parameter = 'materiel', $search = null, $page = '1'){
            return $this->apiUrl.$parameter.'?search='.$search.'&catalogues[]=beproactiv&page='.$page.'&token='.$this->apiToken;   
        };
        
        // Récupère les valeurs par page si la limite est trop faible.
        while ($i <= $lastPage) {
            $response   = $this->client->request('GET',$urlGenerate('materiels',null,$i));
            $data       = json_decode($response->getContent());
            $array      = array_merge($array, $data->data);
            $lastPage   = $data->last_page;
            $i++;
        }

        return [
            'success' => true,
            'message' => 'La récupération des données depuis l\'API a été effectuée.',
            'content' => $array 
        ];
    }

    /**
	* Vide les tables de la base de données.
	* @return boolean
	*/
    private function emptyTable()
    {
        $sql = '
            SET FOREIGN_KEY_CHECKS = 0;
            TRUNCATE `fabricant`;
            TRUNCATE `materiel`;
            TRUNCATE `metier`;
            TRUNCATE `type`;
            SET FOREIGN_KEY_CHECKS = 1;
        ';

        $conn = $this->em->getConnection();
        $stmt = $conn->prepare($sql);

        if ($stmt->execute())
            return true;
        else return false;
    }

    /**
	* Insert les données pour une entity précise.
    * @param array $data
    * @param class $entityName
    * @param array $relations
	* @return string
	*/
    private function insertFromEntity($data, $entityName, $relations = [])
    {
        // Insertion des données depuis les setters.
        foreach ($data as $value) {
    
            $entity     = new $entityName();
            $setters    = $entity->getSetters();
            $values     = array_intersect_key((array)$value,$setters);
            
            foreach ($setters as $key => $setter) {
                if (array_key_exists($key, $relations))
                    $entity->$setter($this->em->getRepository($relations[$key])->findById(intval($values[$key]))[0]);
                else
                    $entity->$setter($values[$key]);  
            }
            
            $this->em->persist($entity);
        }

        try {
            $this->em->flush();
            return 'Les données de la table '.str_replace('App\Entity\\','',$entityName).' ont bien été importées.';
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

    }  
       
}