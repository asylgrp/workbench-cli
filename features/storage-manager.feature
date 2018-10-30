Feature: Storage manager
  In order to work with bookkeeping data
  As a user
  I need to be able to manage stored files

  Scenario: I inspect the storage
    When I import:
    """
    #FLAGGA 0
    #SIETYP 4
    #TAXAR 2017
    """
    Then the storage contains "book_2017"

  Scenario: I remove item from storage
    When I import:
    """
    #FLAGGA 0
    #SIETYP 4
    #TAXAR 2017
    """
    And I remove "book_2017" from the storage
    Then the storage does not contain "book_2017"

  Scenario: I clear the storage
    When I import:
    """
    #FLAGGA 0
    #SIETYP 4
    #TAXAR 2017
    """
    And I clear the storage
    Then the storage does not contain "book_2017"
