# lang=en
@seo
Feature: Test SEO
    @atos
    Scenario: Verify that Atos is referenced
        Given I am on "/wiki/Wikipédia:Accueil_principal"
        When I fill in "search" with "Atos"
        And I press "searchButton"
        Then I should see "Atos est une entreprise de services du numérique (ESN) française"

    @financial
    Scenario: Verify that Atos is part of the CAC40
        Given I am on "/wiki/Wikipédia:Accueil_principal"
        When I fill in "search" with "CAC40"
        And I press "searchButton"
        Then I should see "Atos"
