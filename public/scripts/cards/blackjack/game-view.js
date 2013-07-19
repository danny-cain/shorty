function BlackjackGameView()
{
    this.game = null;
    this.gameContainer = $('<div></div>');
    this.playerContainers = [];

    this.gameStarted = function()
    {
        // display all players + deck
    };

    this.displayCurrentPlayersTurn = function(cardChosenCallback, endTurnCallback)
    {
        // display cards + deck + end turn button
    };

    this.getPlayerNameByIndex = function(index)
    {
        return this.game.players[index].name;
    };

    this.playerDrewCard = function(index, card)
    {
        // animate card being drawn?
    };

    this.activePlayerChanged = function(newPlayerIndex)
    {
        // animate player change?
    };

    this.gameEnded = function(winners, winningScore)
    {
        // display winners and hands
    };
}