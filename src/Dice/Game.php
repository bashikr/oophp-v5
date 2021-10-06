<?php

namespace Bashar\Dice;

class Game
{
    /**
     * @var int $dice
     */
    private $playersArray = [];
    private $playersValuesArray = [];
    private $playersHandSum = [];
    private $playersRoundsSum = [];
    private $playersFinalSum = [];
    private $playerToStart = 0;

    /**
     * Create DiceHand
     * @param int $playersNumber
     * @param int $dicesAmount
     */
    public function __construct(int $playersNumber, int $dicesAmount)
    {
        for ($i = 0; $i < $playersNumber; $i++) {
            array_push($this->playersArray, new DiceHand($dicesAmount));
        }
    }

    /**
     * Loops through the playersArray, then set every
     * players dices values in player specific arrays 
     * lastly these arrays are pushed into playersValuesArray
     * ex: game with 2 players and 2 dices
     * returns [[4,3], [1,2]]
     *
     * @return array with values of the last roll.
     */
    public function processPlayersArrays()
    {
        $playersNumber = count($this->playersArray);
        for ($i = 0; $i < $playersNumber; $i++) {

            $diceHand = $this->playersArray[$i];
            $diceHand->setValues();

            $dices = sizeof($diceHand->getValues());

            $playerArray = [];
            $playerArray[$i] = [];
            for ($j = 0; $j < $dices; $j++) {
                array_push($playerArray[$i], $diceHand->getValues()[$j]);
            }
            array_push($this->playersValuesArray, $playerArray[$i]);
        }
        return $this->playersValuesArray;
    }

    public function throwAgain()
    {
        $playersNumber = count($this->playersArray);
        $this->playersValuesArray = [];
        $this->playersHandSum = [];

        for ($i = 0; $i < $playersNumber; $i++) {
            $this->playersArray[$i]->changeValuesArray();
            $this->playersArray[$i]->rollHand();
            $this->playersArray[$i]->resetHandScore();

            $diceHand = $this->playersArray[$i];
            $diceHand->setValues();

            $dices = sizeof($diceHand->getValues());

            $playerArray = [];
            $playerArray[$i] = [];
            for ($j = 0; $j < $dices; $j++) {
                array_push($playerArray[$i], $diceHand->getValues()[$j]);
            }
            array_push($this->playersValuesArray, $playerArray[$i]);
        }
        return $this->playersValuesArray;
    }

    /**
     * Get the values of every dice on all players through 
     * their dices to determine who has the highest dices score
     * to start the game.
     *  ex: game with 2 players and 2 dices
     * [[4,3], [1,2]]
     * returns 4, 3, 1, 2
     *
     * @return string with the values of all dices.
     */
    public function getPlayersHands()
    {
        $count = sizeof($this->playersValuesArray);
        $values1 = '';
        for ($i = 0; $i < $count; $i++) {
            $values1 .= "Player's " . ($i + 1) . ' hand dices: ' . implode(', ', $this->playersValuesArray[$i]) . '<br> ';
        }
        return $values1;
    }

    /**
     * Sums every player's dices (hands)
     *  ex: game with 2 players and 2 dices
     * [[4,3], [1,2]]
     * returns 7, 3
     *
     * @return string with the sum of every player's dices.
     */
    public function playersHandSum()
    {
        $playersNumber = count($this->playersArray);
        $this->playersHandSum = [];
        for ($i = 0; $i < $playersNumber; $i++) {
            array_push($this->playersHandSum, $this->playersArray[$i]->sum());
        }
        $playersHandSum = implode(', ', $this->playersHandSum);
        return $playersHandSum;
    }

    /**
     * Searches for the highest sum in the playersHandSum
     * array.
     *  ex: game with 2 players and 2 dices
     * [[4,3], [1,2]]
     * sum 7, 3
     * return 1
     *
     * @return int returns the player to start
     * @return string if two players have the same highest score the
     *  method will return a string that says 'Roll again'
     */
    public function firstPlayer()
    {
        $max = max($this->playersHandSum);
        $itemsInPlayerSum = count($this->playersHandSum);

        $rep = 0;
        for ($i = 0; $i < $itemsInPlayerSum; $i++) {
            if ($max) {
                if ($max == $this->playersHandSum[$i]) {
                    $rep++;
                }
            }
        }

        $playerToStart = array_search($max, $this->playersHandSum) + 1;
        if ($rep > 1) {
            return 'Roll again';
        }
        $this->playerToStart = $playerToStart;
        return $this->playerToStart;
    }

    /**
     * Get values of dices from last roll.
     *
     * @return array with values of the last roll.
     */
    public function checkIfNumberOneIsInHand(int $player)
    {
        if (in_array(1, $this->playersValuesArray[$player - 1])) {
            return True;
        };
        return False;
    }

    public function playerHand(int $player)
    {
        $playerHandValuesArr = $this->playersValuesArray[$player - 1];
        $playerHandValues = implode(', ', $playerHandValuesArr);
        return $playerHandValues;
    }

    public function moveToNextPlayer(int $player)
    {
        $playersAmount = count($this->playersArray);

        if ($player <= $playersAmount && $player > 0) {
            if ($player === $playersAmount) {
                $this->playerToStart = 1;
                return $this->playerToStart;
            } else {
                return $this->playerToStart = $player + 1;
            }
        } else {
            return false;
        }
    }

    public function returnPlayerToStart()
    {
        return $this->playerToStart;
    }

    public function playerRoundSum(int $player)
    {
        if (array_key_exists($player - 1, $this->playersRoundsSum)) {
            $roundSum = $this->playersRoundsSum[$player - 1];
        } else {
            $roundSum = 0;
        }

        if ($this->checkIfNumberOneIsInHand($player) === True) {
            $this->playersRoundsSum[$player - 1] = 0;
            $this->playersHandSum[$player - 1] = 0;
            $this->moveToNextPlayer($player);
            return $this->playersRoundsSum[$player - 1];
        } else if ($this->checkIfNumberOneIsInHand($player) === False) {
            $roundSum += $this->playersHandSum[$player - 1];
            $this->playersRoundsSum[$player - 1] = $roundSum;
            if ($this->playersRoundsSum) {
                if ($this->playersRoundsSum[$player - 1] < 100) {
                    return $this->playersRoundsSum[$player - 1];
                }
                return 'bigger than 100';
            }
        }
    }

    public function savePlayerResults(int $player)
    {
        if (array_key_exists($player - 1, $this->playersFinalSum)) {
            $this->playersFinalSum[$player - 1] += $this->playersRoundsSum[$player - 1];
        } else {
            $this->playersFinalSum[$player - 1] = $this->playersRoundsSum[$player - 1];
        }
        return $this->moveToNextPlayer($player);
    }

    public function playersFinalSum()
    {
        $keys = array_keys($this->playersFinalSum);
        $arrayLength = count($keys);

        $res = '';
        for ($i = 0; $i < $arrayLength; $i++) {
            $res .= 'Player ' .  ($keys[$i] + 1) . "'s score is: " . $this->playersFinalSum[$keys[$i]]
                . ' <br>';
        }
        return $res;
    }

    public function winner(int $player)
    {
        $playersFinalSumCount = count($this->playersFinalSum);
        if ($playersFinalSumCount > 0) {
            if (array_key_exists($player - 1, $this->playersFinalSum)) {
                if ($this->playersFinalSum[$player - 1] < 100) {
                    return 'No winner yet!';
                }
                return 'Player ' . $player . ' wins! :)';
            }
        }
        return 'No winner yet!';
    }

    public function saveButtonVisibility(string $case, int $player)
    {
        if ($case == 'save') {
            if (array_key_exists($player - 1, $this->playersRoundsSum)) {
                $this->playersRoundsSum[$player - 1] = 0;
            }

            if ($this->checkIfNumberOneIsInHand($player) === True) {
                return 'none';
            }
            return 'none';
        } else if ($case == 'visible') {
            if ($this->checkIfNumberOneIsInHand($player) === True) {
                return 'none';
            }
            return 'visible';
        }
    }

    public function playButtonVisibility()
    {
        if (max($this->playersFinalSum) >= 100) {
            return 'none';
        }
        return 'visible';
    }
}
