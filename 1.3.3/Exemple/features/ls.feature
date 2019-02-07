# language: fr
Fonctionnalité: ls
  Afin de visualiser l'arborescence d'un dossier
  En tant qu'utilisateur root
  Je dois être capable de lister le contenu du répertoire courant

  Scénario: Lister 2 fichiers dans un dossier
    Etant donné que je suis dans le dossier "test"
    Et que j'ai un fichier nommé "foo"
    Et que j'ai un fichier nommé "bar"
    Quand j'exécute la commande "ls"
    Alors je dois obtenir :
      """
      bar
      foo
      """
