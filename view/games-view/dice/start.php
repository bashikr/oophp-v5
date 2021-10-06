<?php

namespace Anax\View;

/**
 * View for playing guess game
 */
?>
<!doctype html>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
<main style='display:auto; text-align:center;'>

    <h3>Dice game 100</h3>

    <form method="post" action="start">
        Players<input type="number" name="playersAmount" min="2" max="5" required>
        Dices<input type="number" name="dicesAmount" min="1" max="5" required>
        <button class="" type="submit" name="start">Start</button>
    </form>

</main>