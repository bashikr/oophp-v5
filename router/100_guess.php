<?php
/**
 * Create routes for guess game using $app programming style.
 */


/**
 * Init the game redirect to play the game.
 */
$app->router->get("guess/init", function () use ($app) {
    $_SESSION["res"] = null;
    // Init the game
    $game = new Bashar\Guess\Guess();
    $_SESSION["number"] = $game->number();
    $_SESSION["tries"] = $game->tries();

    return $app->response->redirect("guess/play");
});



/**
 * Play the game. show game status
 */
$app->router->get("guess/play", function () use ($app) {
    $title = "Play the game";

    
    $data = [
        "guess" => $_SESSION["guess"] ?? null,
        "tries" => $_SESSION["tries"] ?? null,
        "number" => $_SESSION["number"] ?? null,
        "res" => $_SESSION["res"] ?? null,
    ];

    $_SESSION["res"] = null;

    $app->page->add("guess/play", $data);
    // $app->page->add("guess/debug");

    return $app->page->render([
        "title" => $title,
    ]);
});


/**
 * Play the game. Make a guess (POST method)
 */
$app->router->post("guess/play", function () use ($app) {

    // Deal with incoming variables
    $guess = $_POST["guess"] ?? null;
    $doGuess = $_POST["doGuess"] ?? null;
    $doInit = $_POST["doInit"] ?? null;
    $doCheat = $_POST["doCheat"] ?? null;

    // Get current settings from the SESSION
    $number = $_SESSION["number"] ?? null;
    $tries = $_SESSION["tries"] ?? null;
    $res = null;

    if ($_POST["doInit"]) {
        return $app->response->redirect("guess/init");
    } elseif ($doGuess) {
        $_SESSION["guess"] = $guess;
        return $app->response->redirect("guess/make-guess");
    } elseif ($doCheat) {
        $_SESSION["res"] = "Cheated number is: " . $number;
        return $app->response->redirect("guess/play");
    } else {
        return $app->response->redirect("guess/init");
    }
});


/**
 * Make a guess (make-guess)
 */
$app->router->get("guess/make-guess", function () use ($app) {
    $number = $_SESSION["number"] ?? null;
    $tries = $_SESSION["tries"] ?? null;
    $guess = $_SESSION["guess"] ?? null;

    $game = new Bashar\Guess\Guess($number, $tries);

    try {
        $res = $game->makeGuess($guess);
    } catch (Bashar\Guess\GuessException $e) {
        $res = '<p style="color:red; font-weight: 900;">Warning: </p>' . $e->getMessage();
    } catch (TypeError $e) {
        $res = `The given number {$guess} is out of range.`;
    }


    $_SESSION["tries"] = $game->tries();
    $_SESSION["res"] = $res;

    if ($res == "CORRECT") {
        return $app->response->redirect("guess/win");
    } elseif ($_SESSION["tries"] < 1) {
        return $app->response->redirect("guess/fail");
    } else {
        return $app->response->redirect("guess/play");
    }
});



/**
 * Wining the game
 */
$app->router->get("guess/win", function () use ($app) {
    $title =" You won the game!";

    $data = [
        "number" => $_SESSION["number"] ?? null
    ];
    

    $app->page->add("guess/win", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});


/**
 * In case of losing the game
 */
$app->router->get("guess/fail", function () use ($app) {
    $title =" You have lost the game!";

    $data = [
        "tries" => $_SESSION["tries"] ?? null,
        "number" => $_SESSION["number"] ?? null
    ];

    $app->page->add("guess/fail", $data);

    return $app->page->render([
        "title" => $title,
    ]);
});
