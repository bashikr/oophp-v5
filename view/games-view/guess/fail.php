<?php

namespace Anax\View;

/**
 * View when failing in guess game
 */


?>
<main style="text-align: center;">
<h1>Guess my number (SESSION)</h1>

<p style="color: red; font-weight: 900;">Oops you lost, Please try again!</p>

<p>The correct guess is: <?= $number ?>.</p>

<form method="post" action="play">
    <input type="submit" name="doInit" value="Play again">
</form>
</main>
