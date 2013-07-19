function BlackjackGame()
{
    this.view = null;
    this.players = [];
    this.currentPlayer = 0;
    this.deck = new Deck();

    this.startGame = function(players)
    {
        this.players = players;
        this.view.game = this;

        for (var i = 0; i < this.players.length; i ++)
        {
            this.players[i].hand.cards = [this.deck.drawCard(), this.deck.drawCard()];
            this.players[i].game = this;
            this.players[i].startGame();
        }

        this.view.gameStarted();
        this.view.activePlayerChanged(0);
        this.currentPlayer = 0;
        this.deck.shuffle();

        this.players[this.currentPlayer].startTurn();
    };

    this.drawCard = function()
    {
        var card = this.deck.drawCard();

        this.view.playerDrewCard(this.currentPlayer, card);
        this.players[this.currentPlayer].hand.cards.push(card);
    };

    this.nextPlayer = function()
    {
        var player = this.players[this.currentPlayer];
        var name = player.name;
        var hand = player.hand.getHandAsText();

        this.players[this.currentPlayer].endTurn();

        var self = this;

        setTimeout(function()
        {
            self.currentPlayer ++;
            if (self.currentPlayer >= self.players.length)
                self.endGame();
            else
            {
                self.view.activePlayerChanged(self.currentPlayer);
                self.players[self.currentPlayer].startTurn();
            }
        }, 500);
    };

    this.endGame = function()
    {
        var winners = [];
        var winningScore = 0;

        for (var i in this.players)
        {
            if (!this.players.hasOwnProperty(i))
                continue;

            var score = this.players[i].getMaxValidScore();

            if (score == winningScore)
            {
                winners.push(i);
            }
            if (score > winningScore && score <= 21)
            {
                winners = [];
                winners.push(i);
                winningScore = score
            }
        }

        this.view.gameEnded(winners, winningScore);
    };
}