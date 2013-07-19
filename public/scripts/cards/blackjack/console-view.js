function BlackjackDebugGameView()
{
    this.game = null;

    this.gameStarted = function()
    {

    };

    this.displayCurrentPlayersTurn = function(cardChosenCallback, endTurnCallback)
    {
        if (confirm("Your hand is: " + this.game.players[this.game.currentPlayer].hand.getHandAsText() + ", do you want another card?"))
            cardChosenCallback();
        else
            endTurnCallback();
    };

    this.getPlayerNameByIndex = function(index)
    {
        return this.game.players[index].name;
    };

    this.playerDrewCard = function(index, card)
    {
        console.log(this.getPlayerNameByIndex(index) + " drew " + card.getName());
    };

    this.activePlayerChanged = function(newPlayerIndex)
    {
        console.log(this.getPlayerNameByIndex(newPlayerIndex) + "'s turn - their hand is " + this.game.players[newPlayerIndex].hand.getHandAsText());
    };

    this.gameEnded = function(winners, winningScore)
    {
        for (var i in this.game.players)
        {
            if (!this.game.players.hasOwnProperty(i))
                continue;

            console.log(this.getPlayerNameByIndex(i) + " has: " + this.game.players[i].hand.getHandAsText());
        }

        console.log("Game Ended, the winning score was " + winningScore);
        console.log("The winning players were:");
        for (i in winners)
        {
            if (!winners.hasOwnProperty(i))
                continue;

            console.log(this.getPlayerNameByIndex(winners[i]));
        }
    };
}