# Cluster

#Comment intégrer un slug dans Symfony 5
 - composer require antishov/doctrine-extensions-bundle
 - rajouter le bundle ci-dessous
  ```php
  //config/bundles.php
  return [
     // ...
      Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle::class => ['all' => true],
  ];
  ```
  
 - Un fichier yaml ( stof_doctrine_extensions.yaml )est créé automatiquement (si non le créée )
   ```yaml
   stof_doctrine_extensions:
    default_locale: fr_FR
    orm:
        default:
            sluggable: true
   
   ```
 
 - Rajouter le namespace dans l'entité concerné et l'annotation sur la propriété concernée
 ```php 
    
    use Gedmo\Mapping\Annotation as Gedmo;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255, nullable=false)
     * 
     */
    private $slug;
 ```
