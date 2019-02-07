Feature: Test PyString
    Scenario: Les PyString 
        Given a blog post "Titre" with:
        """
        Ceci est le texte de mon post.
        Il peut-être très long.....
        """
        Then I am happy
