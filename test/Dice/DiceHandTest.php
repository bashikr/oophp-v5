<?php

namespace Bashar\Dice;

use PHPUnit\Framework\TestCase;


class DiceHandTest extends TestCase
{
    public int $diceAmount = 2;

    /**
     * Construct object and verify that the object has the expected
     * properties. Use only first argument.
     */
    public function testInitDiceHandClass()
    {
        $diceHand = new DiceHand();
        $this->assertInstanceOf("\Bashar\Dice\DiceHand", $diceHand);
    }

    public function testChangeValuesArray()
    {
        $diceHand = new DiceHand($this->diceAmount);

        $diceHand->setValues();
        $values = $diceHand->getValues();

        $this->assertNotEmpty($values);

        $diceHand->changeValuesArray();

        $values = $diceHand->getValues();

        $this->assertEmpty($values);
    }

    public function testResetValuesArray()
    {
        $diceHand = new DiceHand($this->diceAmount);

        $diceHand->setValues();
        $values = $diceHand->getValues();

        $this->assertCount(2, $values);

        $diceHand->resetValuesArray();
        $values = $diceHand->getValues();
        $this->assertCount(2, $values);

        $this->assertEquals($values, [0, 0]);
    }

    public function testSumMethod()
    {
        $diceHand = new DiceHand($this->diceAmount);

        $diceHand->setValues();

        $values = array_sum($diceHand->getValues());
        $valueOfSum = $diceHand->sum();

        $this->assertEquals($valueOfSum, $values);
    }

    public function testResetHandScore()
    {
        $diceHand = new DiceHand($this->diceAmount);

        $diceHand->setValues();

        // returns handScore
        $handScore = $diceHand->sum();
        $this->assertGreaterThan(0, $handScore);

        $resetHandScore = $diceHand->resetHandScore();

        $this->assertEquals(0, $resetHandScore);
    }
}
