Feature: Display bookkeeping data
  In order to work with bookkeeping data
  As a user
  I need to be able to display formatted data

  Scenario: I display some data
    Given an organization named "foo"
    When I import:
    """
    #FLAGGA 0
    #SIETYP 4
    #FNAMN "foo"
    #TAXAR 2017
    #KONTO 1920 bank
    #KONTO 3000 income
    #VER A 1 20170101 "sales"
    {
        #TRANS 1920 {} 100.00
        #TRANS 3000 {} -100.00
    }
    """
    And I generate a ledger
    Then the output contains "1920 BANK"
    Then the output contains "sales"
    Then the output contains "100.00"
