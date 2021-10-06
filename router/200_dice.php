<?php
/**
 * Create routes for dice game using $app programming style.
 */

/**
 * Init the game redirect to play the game.
 */
$app->router->get("games-view/dice/init", function () use ($app) {
    $app->session->set('game', null);
    $app->session->set("playerRoundSum", null);
    $app->session->set("playersFinalSum", null);
    $app->session->set("playersHandSum", null);
    $app->session->set("winner", null);

    return $app->response->redirect("games-view/dice/start");
});


/**
 * Play the game. show game status
 */
$app->router->get("games-view/dice/start", function () use ($app) {
    $app->page->add("games-view/dice/start");
    // $app->page->add("games-view/dice/debug");

    return $app->page->render([
        "title" => "Start",
    ]);
});


/**
 * Play the game. Make a guess (POST method)
 */
$app->router->post("games-view/dice/start", function () use ($app) {
    $players = $app->request->getPost("playersAmount");
    $dices = $app->request->getPost("dicesAmount");

    $app->session->set("game", new Bashar\Dice\Game($players, $dices));
    $game = $app->session->get("game");
    $game->processPlayersArrays();
    $app->session->set("playersHands", $game->getPlayersHands());
    $app->session->set("playersHandSum", $game->playersHandSum($reset = False));
    $app->session->set("firstPlayer", $game->firstPlayer());

    return $app->response->redirect("games-view/dice/play");
});


/**
 * Play the game. show game status
 */
$app->router->get("games-view/dice/play", function () use ($app) {
    $app->page->add("games-view/dice/play");
    // $app->page->add("games-view/dice/debug");

    return $app->page->render([
        "title" => "play",
    ]);
});


/**
 * Play the game. Make a guess (POST method)
 */
$app->router->post("games-view/dice/play", function () use ($app) {
    $play = $app->request->getPost("play");
    $reset = $app->request->getPost("reset");

    $game = $app->session->get("game");

    if ($play) {
        if($game->firstPlayer() == 'Roll again') {
            $game->throwAgain();
            $app->session->set("playersHands", $game->getPlayersHands());
            $app->session->set("playersHandSum", $game->playersHandSum());
            $app->session->set("firstPlayer", $game->firstPlayer());
            return $app->response->redirect("games-view/dice/play");
        } else {
            $whoWillPlay = $app->session->get('firstPlayer');
            $game->processPlayersArrays();
            $game->throwAgain();
            $app->session->set("playerHand", $game->playerHand($whoWillPlay));
            $app->session->set('saveButtonVisibility', 'visible');
            $app->session->set("playersHandSum", $game->playersHandSum());
            $app->session->set("playerRoundSum", $game->playerRoundSum($whoWillPlay));
            $app->session->set("winner", $game->winner($whoWillPlay));

            return $app->response->redirect("games-view/dice/game");
        }
    } elseif ($reset) {
        return $app->response->redirect("games-view/dice/init");
    }
});


/**
 * Play the game. show game status
 */
$app->router->get("games-view/dice/game", function () use ($app) {
    $app->page->add("games-view/dice/game");
    $game = $app->session->get("game");
    $app->session->set("firstPlayer1", $game->returnPlayerToStart());

    return $app->page->render([
        "title" => "Game",
    ]);
});


/**
 * Play the game. show game status
 */
$app->router->post("games-view/dice/game", function () use ($app) {
    $playGame = $app->request->getPost("playPlayer");
    $saveHand = $app->request->getPost("save");
    $reset = $app->request->getPost("reset");

    if ($playGame) {
        $game = $app->session->get("game");
        $app->session->set("firstPlayer", $game->returnPlayerToStart());
        $whoWillPlay = $app->session->get('firstPlayer');
        $game->processPlayersArrays();
        $game->throwAgain();
        $app->session->set("playerHand",$game->playerHand($whoWillPlay));
        $game->playersHandSum();
        $app->session->set("playerRoundSum", $game->playerRoundSum($whoWillPlay));
        $app->session->set("winner", $game->winner($whoWillPlay));
        $app->session->set('saveButtonVisibility', $game->saveButtonVisibility('visible', $whoWillPlay));

        return $app->response->redirect("games-view/dice/game");
    } elseif ($reset) {
        return $app->response->redirect("games-view/dice/init");
    } elseif ($saveHand) {
        $game = $app->session->get("game");
        $whoWillPlay = $app->session->get('firstPlayer');
        $game->savePlayerResults($whoWillPlay);
        $app->session->set("playersFinalSum", $game->playersFinalSum() );
        $app->session->set("winner", $game->winner($whoWillPlay));
        $app->session->set("playerHand",$game->playerHand($whoWillPlay));
        $app->session->set('saveButtonVisibility', $game->saveButtonVisibility('save', $whoWillPlay));
        $app->session->set('playButtonVisibility', $game->playButtonVisibility());

        return $app->response->redirect("games-view/dice/game");
    }

    return $app->page->render([
        "title" => "Game",
    ]);
});