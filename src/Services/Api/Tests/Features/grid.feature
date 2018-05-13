Feature: Grid
  Can create new grid
  so I can place ships
  check positions of ships

  Scenario: create grid
    Given an empty grid
    Then I can place ships
    And is positions of ships not the same
    And is type of the ship not more then one kind