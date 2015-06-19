<?php namespace Site\TrailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Site\TrailBundle\Entity\Categorie;
use Site\TrailBundle\Entity\Image;
use Symfony\Component\HttpFoundation\Request;

class GalleryController extends Controller
{
    /**
     * Fonction qui récupère les catégories des images de la galeri
     *
     * Cette méthode requiert les paramètres suivants : 
     * 
     * <code>
     * indStart : Indice de début de la recherche
     * </code>
     * 
     * @return Response 
     */
    public function getTheCategories($indStart, $pagination=true)
    {
        $listCategories = array();
		
		$manager = $this->getDoctrine()->getManager();
        
        $repository = $manager->getRepository("SiteTrailBundle:Categorie");
        $qb = $manager->createQueryBuilder();
        $qb->select('cat')
            ->from('SiteTrailBundle:Categorie', 'cat')
			->from('SiteTrailBundle:Image', 'img')
			->where('cat.id = img.categorie')
            ->orderBy('cat.label', 'ASC')
			->distinct();
                
        if($pagination)
        {
            $qb->setFirstResult($indStart)
                ->setMaxResults(5);
        }
        
        $query = $qb->getQuery();
        $listCategories = $query->getResult();      
        
        return $listCategories;
    }
    
    /**
     * Fonction qui affiche la page d'accueil de la galerie
     *
     * Cette méthode ne requiert pas de paramètres : 
     * 
     * @return Response 
     */
    public function indexAction(Request $request)
    {
		$this->testDeDroits('Gallerie');
	
		$manager = $this->getDoctrine()->getManager();
		$indStart = $request->get('indStart');  
		$numPage = ($indStart/5)+1;

		$listeCategorie = GalleryController::getTheCategories($indStart, true);

		$listeImage = array();
		$result_categ = array();

		//récupération des 4 premières images de la catégorie
		foreach($listeCategorie as $categorie)
		{
			$qb = $manager->createQueryBuilder();
			$qb->select('img')
				->from('SiteTrailBundle:Image', 'img')
				->where('img.categorie = :idCategorie')
				->orderBy('img.id', 'DESC')
				->setParameter('idCategorie', $categorie->getId())
				->setMaxResults(4);

			$query = $qb->getQuery();

			$listeImage[] = $query->getResult();
		}
	
		/*$reqNb = "SELECT count(cat) FROM SiteTrailBundle:Categorie cat";
		$queryNb = $manager->createQuery($reqNb);
		$nbCategorie = $queryNb->getSingleScalarResult(); */

		$nbCategorie = sizeof(GalleryController::getTheCategories($indStart, false));

		$content = $this->get("templating")->render("SiteTrailBundle:Gallery:index.html.twig", array(
														'listeCategorie' => $listeCategorie,
														'listeImage' => $listeImage,
														'nbCategorie' => $nbCategorie,
														'numPage' => $numPage
													));
		return new Response($content);
    }
	
    /**
     * Fonction qui affiche les iamges d'une catégorie
     *
     * Cette méthode requiert les paramètres suivants : 
     * 
     * <code>
     * indStart : Indice de début de la recherche des images
     * idCategorie : ID de la catégorie
     * </code>
     * 
     * @return Response 
     */
    public function categoryAction(Request $request, $idCategorie)
    {
		$this->testDeDroits('Gallerie');
		
        $manager = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository("SiteTrailBundle:Categorie");
        $categorie = $repository->findOneById($idCategorie);
        
        $indStart = $request->get('indStart');  
        $numPage = ($indStart/12)+1;
        
        $manager = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository("SiteTrailBundle:Categorie");
        $qb = $manager->createQueryBuilder();
        $qb->select('img')
            ->from('SiteTrailBundle:Image', 'img')
            ->where('img.categorie = :idCategorie')
            ->orderBy('img.id', 'DESC')
            ->setParameter('idCategorie', $idCategorie)
            ->setFirstResult($indStart)
            ->setMaxResults(12);            

        $query = $qb->getQuery();
        $listeImage = $query->getResult();
        
        $reqNb = "SELECT count(img) FROM SiteTrailBundle:Image img WHERE img.categorie=".$idCategorie;
        $queryNb = $manager->createQuery($reqNb);
        $nbImage = $queryNb->getSingleScalarResult(); 

        $content = $this->get("templating")->render("SiteTrailBundle:Gallery:category.html.twig", array(
                                                            'categorie' => $categorie,
                                                            'listeImage' => $listeImage,
                                                            'nbImage' => $nbImage,
                                                            'numPage' => $numPage
                                                    ));
        return new Response($content);
    }
	
    /**
     * Fonction qui affiche la fiche de description d'une image
     *
     * Cette méthode requiert les paramètres suivants : 
     * 
     * <code>
     * idPicture : ID de l'image
     * </code>
     * 
     * @return Response 
     */
    public function pictureAction(Request $request)
    {
		$this->testDeDroits('Gallerie');
		
        $idPicture = $request->get('idPicture');        
        $manager = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository("SiteTrailBundle:Image");
        $picture = $repository->findOneById($idPicture);
        
        $arrayUnite = array("octets", "Ko", "Mo");
        $i = 0;
        $poidsConvert = $picture->getPoids();
        
        while((($poidsConvert/1024) > 1) && $i<2)
        {
            $poidsConvert /= 1024;
            $i += 1;
        }
        
        $content = $this->get("templating")->render("SiteTrailBundle:Gallery:picture.html.twig", array(
                                                        'picture' => $picture,
                                                        'poids' => round($poidsConvert,2),
                                                        'unite' => $arrayUnite[$i]
        ));
        
        return new Response($content);
    }
    
    /**
     * Fonction qui permet d'ajouter une catégorie
     *
     * Cette méthode requiert les paramètres suivants : 
     * 
     * <code>
     * label : Label de la catégorie
     * </code>
     * 
     * @return Response 
     */
    public function categorieAjoutAction(Request $request)
    {
		$this->testDeDroits('Gallerie');

		$categorie = new Categorie();
		$formBuilder = $this->get('form.factory')->createBuilder('form', $categorie);
		$formBuilder
				->setAction($this->generateUrl('site_trail_category_ajout'))
				->add('label', 'text', array('max_length' => 255));
		
		$form = $formBuilder->getForm();
		$form->handleRequest($request);

		$manager = $this->getDoctrine()->getManager();
		
		if ($form->isValid()) 
		{
			
			
			$repository = $manager->getRepository("SiteTrailBundle:Categorie");               
			$manager->persist($categorie);
			$manager->flush();

			return $this->redirect($this->generateUrl('site_trail_gallery'));
		}
		
		$formulaire = $this->get("templating")->render("SiteTrailBundle:Gallery:formAddCategorie.html.twig", array(
															'form' => $form->createView()
														));

		return new Response($formulaire);
    }
    
    /**
     * Fonction qui permet de supprimer une catégorie
     *
     * Cette méthode requiert les paramètres suivants : 
     * 
     * <code>
     * idCategorie : ID de la catégorie
     * </code>
     * 
     * @return Response 
     */
    public function categorieSuppressionAction(Request $request)
    {
		$this->testDeDroits('Gallerie');
		
        if(!$request->isXmlHttpRequest()) show_404();

		$idCategorie = $request->request->get('idCategorie', '');
		$manager=$this->getDoctrine()->getManager();

		//On récupère l'objet catégorie
		$repository=$manager->getRepository("SiteTrailBundle:Categorie");        
		$categorie = $repository->findOneById($idCategorie);

		//On récupère les images liées à la catégorie
		$repository=$manager->getRepository("SiteTrailBundle:Image"); 
		$images = $repository->findBy(
			array('categorie' => $idCategorie)
		);

		foreach($images as $image)
		{
			$manager->remove($image);       
		}

		//Suppression de l'entité catégorie
		$manager->remove($categorie);
		$manager->flush();

		$request->getSession()->getFlashBag()->add('notice', 'Catégorie supprimé');

		return $this->redirect($this->generateUrl('site_trail_gallery'));
    }
    
    /**
     * Fonction qui permet de modifier une catégorie
     *
     * Cette méthode requiert les paramètres suivants : 
     * 
     * <code>
     * idCategorie : ID de la catégorie
     * label : Label de la catégorie
     * </code>
     * 
     * @return Response 
     */
    public function CategorieModifAction(Request $request)
    {
		$this->testDeDroits('Gallerie');
		
        if(!$request->isXmlHttpRequest()) show_404();
		
		$idCategorie = $request->request->get('idCategorie', '');
		
		$manager=$this->getDoctrine()->getManager();
		$repository=$manager->getRepository("SiteTrailBundle:Categorie");        
		$maCategorie = $repository->findOneById($idCategorie);

		$formBuilder = $this->get('form.factory')->createBuilder('form', $maCategorie);
		$formBuilder
				->setAction($this->generateUrl('site_trail_category_modification'))
				->add('label', 'text', array('max_length' => 255,
												'data' => $maCategorie->getLabel()));

		$form = $formBuilder->getForm();
		$form->handleRequest($request);

		if ($form->isValid()) 
		{
			$manager = $this->getDoctrine()->getManager();
			$manager->flush();
			$request->getSession()->getFlashBag()->add('notice', 'Catégorie ajouté');

			return $this->redirect($this->generateUrl('site_trail_gallery'));
		}
		
		$formulaire = $this->get("templating")->render("SiteTrailBundle:Gallery:modifCategoryForm.html.twig", array(
															'categorie' => $maCategorie,
															'form' => $form->createView()
														));

		return new Response($formulaire);
    }
    
    /**
     * Fonction qui permet d'afficher le formulaire d'ajout d'une image
     *
     * Cette méthode ne requiert pas de paramètres 
     * 
     * @return Response 
     */
    public function showAddPictureAction()
    {
		$this->testDeDroits('Gallerie');
		
        //Création du select contenant les catégories
        $manager = $this->getDoctrine()->getManager();
        $repository = $manager->getRepository("SiteTrailBundle:Categorie"); 
        $listeCategorie = $repository->findAll();
        
        $selectCategorie = '<div class="form-group">';
        $selectCategorie .= '<div class="row">';
        $selectCategorie .= '<label class="col-sm-3 control-label">Catégorie :</label>';
        $selectCategorie .= '<div class="col-sm-9">';
        $selectCategorie .= '<select name="categorie" class="form-control">';
        foreach($listeCategorie as $uneCategorie)
        {
            $selectCategorie .= '<option value="'.$uneCategorie->getId().'">'.$uneCategorie->getLabel().'</option>';
        }
        $selectCategorie .= '</select>';
        $selectCategorie .= '</div>';
        $selectCategorie .= '</div>';
        $selectCategorie .= '</div>';

        $formulaire = $this->get("templating")->render("SiteTrailBundle:Gallery:formAddPicture.html.twig", array(
                                                            'selectCategorie' => $selectCategorie
        ));

        return new Response($formulaire);
    }
    
    /**
     * Fonction qui permet d'ajouter une image
     *
     * Cette méthode requiert les paramètres suivants : 
     * 
     * <code>
     * titre : Titre de l'image
     * description : Description de l'image
     * categorie : Catégorie de l'image
     * fichier : Fichier
     * </code>
     * 
     * @return Response 
     */
    public function addPictureAction(Request $request)
    {        
		$this->testDeDroits('Gallerie');
		
        //Sauvegarde du fichier   
        //$target_dir = "C:/testUp/";

		$target_dir = $this->container->getParameter("upload_directory");

        $target_file = $target_dir . basename($_FILES["fichier"]["name"]);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
        
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fichier"]["tmp_name"]);
            if($check !== false) {
                //echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                //echo "Le fichier n'est pas une image.";
                $uploadOk = 0;
            }
        }
        
        //On vérifie la taille du fichier
        if ($_FILES["fichier"]["size"] > 5000000) {
            //echo "L'image est trop volomineuse.";
            $uploadOk = 0;
        }
        
        //Autorisation de certaines extensions de fichier
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            //echo "Seules les extensions JPG, JPEG, PNG & GIF sont autorisées.";
            $uploadOk = 0;
        }
        
        //On vérifie qu'il n'y a pas eu d'erreurs lors de l'upload
        if ($uploadOk == 0)
        {
            //echo "Il y a eu un problème lors de l'envoi du fichier.";
        }
        else
        {
            $date = new \DateTime;
            $fileName = "image".date_format($date, 'U').".".$imageFileType;
            $newFile = $target_dir.$fileName;
                
            if (move_uploaded_file($_FILES["fichier"]["tmp_name"], $newFile)) {
                list($width, $height, $type, $attr) = getimagesize($newFile); 
                
                $manager = $this->getDoctrine()->getManager();
                
                //On rajoute l'image dans la base de données                
                $titre = $request->request->get('titre', '');
                $description = $request->request->get('description', '');
                $poids = $_FILES["fichier"]["size"];
                $taille = $width.'x'.$height;
                $auteur = $this->getUser();
                if($auteur == null)
                {
                    $auteur = $manager->getRepository("SiteTrailBundle:Membre")->find(1);
                }
                $repository = $manager->getRepository("SiteTrailBundle:Categorie");
                
                $categorie = $repository->findOneById($request->request->get('categorie', ''));
                if($categorie == null)
                {
                    $categorie = $repository->find(1);
                }
                $repository = $manager->getRepository("SiteTrailBundle:Image");
                $newImage = new Image();
                $newImage->setTitre($titre);
                $newImage->setDescription($description);
                $newImage->setPoids($poids);
                $newImage->setTaille($taille);
                $newImage->setAuteur($auteur);
                $newImage->setCategorie($categorie); 
                $newImage->setPath($this->container->getParameter("base_url")."/uploads/".$fileName);
                $manager->persist($newImage);
                $manager->flush();
                
                $manager = $this->getDoctrine()->getManager();
                $repository = $manager->getRepository("SiteTrailBundle:Categorie");
                $listeCategorie = $repository->findAll();

                $listeImage = array();

                //récupération des 4 premières images de la catégorie
                foreach($listeCategorie as $categorie)
                {
                    $qb = $manager->createQueryBuilder();
                    $qb->select('img')
                        ->from('SiteTrailBundle:Image', 'img')
                        ->where('img.categorie = :idCategorie')
                        ->orderBy('img.id', 'DESC')
                        ->setParameter('idCategorie', $categorie->getId())
                        ->setMaxResults(4);

                    $query = $qb->getQuery();

                    $listeImage[] = $query->getResult();
                }

                /*$content = $this->get("templating")->render("SiteTrailBundle:Gallery:index.html.twig", array(
                                                                'listeCategorie' => $listeCategorie,
                                                                'listeImage' => $listeImage
                                                            ));
                return new Response($content);*/
                return $this->redirect($this->generateUrl('site_trail_gallery'));
                
            } else {
                return new Response("Il y a eu un problème lors de l'envoi du fichier.");
            }
        }
    }
    
    /**
     * Fonction qui permet d'afficher le formulaire de modification d'une image
     *
     * Cette méthode requiert les paramètres suivants : 
     * 
     * <code>
     * idPicture : ID de l'image
     * </code>
     * 
     * @return Response 
     */
    public function showUpdatePictureFormAction(Request $request)
    {
		$this->testDeDroits('Gallerie');
		
        if(!$request->isXmlHttpRequest()) show_404();
		
		$idPicture = $request->request->get('idPicture', '');
		$manager=$this->getDoctrine()->getManager();
		$repository=$manager->getRepository("SiteTrailBundle:Image");        
		$picture = $repository->findOneById($idPicture);
		
		$repository = $manager->getRepository("SiteTrailBundle:Categorie"); 
		$listeCategorie = $repository->findAll();
		$selectCategorie = '<div class="form-group">';
		$selectCategorie .= '<div class="row">';
		$selectCategorie .= '<label class="col-sm-3 control-label">Catégorie :</label>';
		$selectCategorie .= '<div class="col-sm-9">';
		$selectCategorie .= '<select name="categorie" class="form-control">';
		
		foreach($listeCategorie as $uneCategorie)
		{
			if($uneCategorie->getId() == $picture->getCategorie()->getId())
			{
				$bonusSelected = " selected='selected'";
			}
			else
			{
				$bonusSelected = "";
			}
			
			$selectCategorie .= '<option value="'.$uneCategorie->getId().'"'.$bonusSelected.'>'.$uneCategorie->getLabel().'</option>';
		}
		$selectCategorie .= '</select>';
		$selectCategorie .= '</div>';
		$selectCategorie .= '</div>';
		$selectCategorie .= '</div>';
		
		$content = $this->get("templating")->render("SiteTrailBundle:Gallery:updatePictureForm.html.twig", array(
															'picture' => $picture,
															'selectCategorie' => $selectCategorie
														));
		return new Response($content);
    }
    
    /**
     * Fonction qui permet de mettre à jour une image
     *
     * Cette méthode requiert les paramètres suivants : 
     * 
     * <code>
     * idPicture : ID de l'image
     * titre : Titre de l'image
     * description : Description de l'image
     * categorie : Catégorie de l'image
     * </code>
     * 
     * @return Response 
     */
    public function updatePictureAction(Request $request)
    {
		$this->testDeDroits('Gallerie');
		
        $idPicture = $request->request->get('idPicture', '');
        $titre = $request->request->get('titre', '');
        $description = $request->request->get('description', '');
        $idCategorie = $request->request->get('categorie', '');
        
        $manager=$this->getDoctrine()->getManager();
        $repository=$manager->getRepository("SiteTrailBundle:Image");        
        $picture = $repository->findOneById($idPicture);
        $repository=$manager->getRepository("SiteTrailBundle:Categorie");        
        $categorie = $repository->findOneById($idCategorie);
        
        $picture->setTitre($titre);
        $picture->setDescription($description);
        $picture->setCategorie($categorie);
        $manager->flush();
        
        $response = $this->forward('SiteTrailBundle:Gallery:picture', array(
            'idPicture'  => $picture->getId()
        ));

        return $response;
    }
    
    /**
     * Fonction qui permet de supprimer une image
     *
     * Cette méthode requiert les paramètres suivants : 
     * 
     * <code>
     * idPicture : ID de l'image
     * </code>
     * 
     * @return Response 
     */
    public function deletePictureAction(Request $request)
    {
		$this->testDeDroits('Gallerie');
		
        $idPicture = $request->request->get('idPicture', '');
        
        $manager=$this->getDoctrine()->getManager();
        $repository=$manager->getRepository("SiteTrailBundle:Image");        
        $picture = $repository->findOneById($idPicture);
        
        //On cherche si le chemin de l'image contient /uploads/image*.* pour savoir si elle est sur le serveur
        $isOnServ = ereg("/uploads/image.*\..*", $picture->getPath());
        
        //Suppression du fichier sur le serveur
        if($isOnServ)
        {
            $findMe   = '/uploads/image';
            $startInd = strpos($picture->getPath(), $findMe);
            $absolutePath = "/var/www".substr($picture->getPath(), $startInd);
            
            if(file_exists($absolutePath))
            {
                unlink($absolutePath);
            }
        }
        
        //Suppression dans la base de données
        $manager->remove($picture);
        $manager->flush();
        
        return new Response();
    }
	
	public function testDeDroits($permission)
	{
		$manager = $this->getDoctrine()->getManager();
		
		$repository_permissions = $manager->getRepository("SiteTrailBundle:Permission");
		
		$permissions = $repository_permissions->findOneBy(array('label' => $permission));

		if(Count($permissions->getRole()) != 0)
		{
			$list_role = array();
			foreach($permissions->getRole() as $role)
			{
				array_push($list_role, 'ROLE_'.$role->getLabel());
			}
			
			// Test l'accès de l'utilisateur
			if(!$this->isGranted($list_role))
			{
				throw $this->createNotFoundException("Vous n'avez pas acces a cette page");
			}
		}
	}
}    
