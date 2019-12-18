# StudyJob

Développement d'une platforme de mise en relation entre des étudiants et des employeurs dans le but de faciliter les démarches de l'alternance.

ATTENTION => S'assurer au préalable que le projet contienne le fichier composer.lock si il n'est pas présent copier le fichier qui est présent dans ce repository à la racine du projet archivé.

1) Installer composer :

``` 
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'baf1608c33254d00611ac1705c1d9958c817a1a33bce370c0595974b342601bd80b92a3f46067da89e3b06bff421f182') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');" 
```

2) Installer les dépendances du projet ( on ne met pas à jour les dépendances ou cas où ils y auraient des dépreciations avec une version plus avancé de symfony)  :

```
composer install

```
