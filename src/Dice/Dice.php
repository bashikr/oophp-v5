<?php

namespace Bashar\Dice;

/**
 * Showing off a standard class with methods and properties.
 */
class Dice
{
    /**
     * @var int  $sides   The name of the Dice.
     * @var int $lastRoll  Value of the last roll.
     */
    private $sides = 0;
    private $lastRoll = 0;

    /**
     * Constructor to initiate the dicehand with a number of dices.
     *
     * @param int $dices Number of dices to create, defaults to five.
     */
    public function __construct(int $sides = 6)
    {
        $this->sides = $sides;
    }

    public function roll(): int
    {
        $this->lastRoll = rand(1, $this->sides);
        return $this->lastRoll;
    }

    public function lastRoll(): int
    {
        return $this->lastRoll;
    }
}
