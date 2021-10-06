<?php

namespace Anax\View;

?>

<!doctype html>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
<main style='display:auto; text-align:center;'>

    <h3>Dice game 100</h3>
    <?php if ($app->session->get("winner") === 'No winner yet!') : ?>
        <?php if ($app->session->get("firstPlayer") === $app->session->get("firstPlayer1")) : ?>
            <p>Player <?= $app->session->get("firstPlayer") ?>
                Throws <?= $app->session->get("playerHand") ?>
                and the round's score is <?= $app->session->get("playerRoundSum") ?>.
            </p>
            <p style='color:green; font-weight:bold;'>Player
                <?= $app->session->get("firstPlayer") ?>
                can either play or save!</p>
        <?php else : ?>
            <p>Player
                <?= $app->session->get("firstPlayer") ?>
                Throws <?= $app->session->get("playerHand") ?>
                and the round's score is <?= $app->session->get("playerRoundSum") ?>.
            </p>
            <p style='color:red; font-weight:bold;'>It is player's
                <?= $app->session->get("firstPlayer1") ?>
                turn now!</p>
        <?php endif; ?>
        <br>
    <?php endif; ?>

    <?php if ($app->session->get("playersFinalSum")) : ?>
        <h3>The players' final scores:</h3>
        <?= $app->session->get("playersFinalSum") ?>
    <?php endif; ?>
    <br>
    <br>

    <p style='font-weight:bold;'><?= $app->session->get("winner") ?></p>

    <form method="post">
        <button class="" type="submit" name="reset" value="Reset">Reset</button>
        <button style='display:<?= $app->session->get('playButtonVisibility') ?>;' class="" type="submit" name="playPlayer" value="playPlayer">Play</button>
        <button style='display:<?= $app->session->get('saveButtonVisibility') ?>;' class="" type="submit" name="save" value="save">Save</button>
    </form>

</main>