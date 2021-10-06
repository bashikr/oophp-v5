<?php

namespace Anax\View;

// $app->session->start();
/**
 * View for playing guess game
 */
?>

<!doctype html>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
<main style='display:auto; text-align:center;'>

    <h3>Dice game 100</h3>

    <p>The players' initial throw:</p>
    <?= $app->session->get("playersHands") ?>
    <br>

    <p>The players' hands' sums:</p>
    <?= $app->session->get("playersHandSum") ?>
    <br>
    <br>

    <?php if (is_int($app->session->get("firstPlayer"))) : ?>
        <p>Player <?= $app->session->get("firstPlayer") ?> will start playing</p>
        <br>
    <?php else : ?>
        <?= $app->session->get("firstPlayer") ?>
    <?php endif; ?>


    <form method="post">
        <button class="" type="submit" name="reset" value="Reset">Reset</button>
        <button class="" type="submit" name="play" value="Play">Play</button>
    </form>

</main>