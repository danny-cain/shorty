function getHumanPlayer(game, name)
{
    var ret = new Player(name);

    ret.takeTurn = function()
    {
        if (ret.getMaxValidScore() > 21)
        {
            game.nextPlayer();
            return;
        }

        game.view.displayCurrentPlayersTurn(function()
        {
            game.drawCard();
            ret.takeTurn();
        }, function()
        {
            game.nextPlayer();
        });
    };

    ret.startTurn = function()
    {
        this.takeTurn();
    };

    return ret;
}

function getNewAI(name, softHoldThreshold, hardHardThreshold)
{
    var ret = new Player(name);

    ret.softHoldThreshold = softHoldThreshold;
    ret.hardHoldThreshold = hardHardThreshold;

    ret.startTurn = function()
    {
        while (this.shouldDrawCard())
            this.game.drawCard();

        this.game.nextPlayer();
    };

    ret.shouldDrawCard = function()
    {
        if (this.getMinValidScore() < this.softHoldThreshold && this.getMaxValidScore() < this.hardHoldThreshold)
            return true;
        else
            return false;
    };

    return ret;
}

function Player(name)
{
    this.hand = new Hand();
    this.name = name;
    this.game = null;

    this.endTurn = function() {};
    this.startTurn = function() {};
    this.startGame = function() {};

    this.getScoreForDisplay = function()
    {
        var score = 0;
        var aceCount = 0;

        for (var i in this.hand)
        {
            if (!this.hand.hasOwnProperty(i))
                continue;

            var cardScore = this.hand.cards[i].rank;
            if (cardScore > 10)
                cardScore = 10;

            score += cardScore;
            if (this.hand.cards[i].rank == 1)
                aceCount ++;
        }

        var displayableScores = [score];

        while (score <= 11 && aceCount > 0)
        {
            aceCount --;
            score += 10;
            displayableScores.push(score);
        }

        return displayableScores.join(" / ");
    };

    this.getMinValidScore = function()
    {
        var score = 0;

        for (var i in this.hand.cards)
        {
            if (!this.hand.cards.hasOwnProperty(i))
                continue;

            var cardScore = this.hand.cards[i].rank;
            if (cardScore > 10)
                cardScore = 10;

            score += cardScore;
        }

        return score;
    };

    this.getMaxValidScore = function()
    {
        var score = 0;
        var aceCount = 0;

        for (var i in this.hand.cards)
        {
            if (!this.hand.cards.hasOwnProperty(i))
                continue;

            var cardScore = this.hand.cards[i].rank;
            if (cardScore > 10)
                cardScore = 10;

            score += cardScore;

            if (this.hand.cards[i].rank == 1)
                aceCount ++;
        }

        while (aceCount > 0 && score <= 11)
        {
            aceCount --;
            score += 10;
        }

        return score;
    };
}