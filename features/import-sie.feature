Feature: Import SIE files
  In order to work with bookkeeping data
  As a user
  I need to be able to import SIE files

  Scenario: I import a nonexistent file
    When I import a nonexistent file
    Then I get an error

  Scenario: I import an SIE file
    Given an organization named "foo"
    When I import:
    """
    #FLAGGA 0
    #SIETYP 4
    #FNAMN "foo"
    #TAXAR 2017
    """
    Then the storage contains "book_2017"

  Scenario: I import an SIE file when organization name is not set
    When I import:
    """
    #FLAGGA 0
    #SIETYP 4
    #FNAMN "foo"
    #TAXAR 2017
    """
    Then the storage contains "book_2017"

  Scenario: I import an SIE file of the wrong type
    Given an organization named "foo"
    When I import:
    """
    #FLAGGA 0
    #SIETYP 1
    #FNAMN "foo"
    #TAXAR 2017
    """
    Then I get an error

  Scenario: I import an SIE file with the wrong organization name
    Given an organization named "foo"
    When I import:
    """
    #FLAGGA 0
    #SIETYP 4
    #FNAMN "bar"
    #TAXAR 2017
    """
    Then I get an error

  Scenario: I import an SIE file without taxation year specified
    Given an organization named "foo"
    When I import:
    """
    #FLAGGA 0
    #SIETYP 4
    #FNAMN "foo"
    """
    Then I get an error
