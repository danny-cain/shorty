CardConstants =
{
    SUIT_CLUBS : 1,
    SUIT_DIAMONDS : 2,
    SUIT_SPADES : 3,
    SUIT_HEARTS : 4
};

function Hand()
{
    this.cards = [];
    this.getHandAsText = function()
    {
        var cards = [];

        for (var i in this.cards)
        {
            if (!this.cards.hasOwnProperty(i))
                continue;

            cards.push(this.cards[i].getName());
        }

        return cards.join(" ");
    };
}

function Card(suit, rank)
{
    this.suit = suit;
    this.rank = rank;

    this.getName = function()
    {
        var suit = "";

        switch(this.suit)
        {
            case CardConstants.SUIT_CLUBS:
                suit = "clubs";
                break;
            case CardConstants.SUIT_DIAMONDS:
                suit = "diamonds";
                break;
            case CardConstants.SUIT_SPADES:
                suit = "spades";
                break;
            case CardConstants.SUIT_HEARTS:
                suit = "hearts";
                break;
        }

        return this.rank + " " + suit;
    };
}

function Deck()
{
    this.cards = [];
    this.shuffledCards = [];
    this.initialise = function()
    {
        for (var suit = 1; suit <= 4; suit ++)
        {
            for (var rank = 1; rank <= 13; rank ++)
            {
                this.cards.push(new Card(suit, rank));
            }
        }
        this.shuffle();
    };

    this.shuffle = function()
    {
        this.shuffledCards = [];
        while (this.shuffledCards.length < 52)
        {
            var num = parseInt(Math.random() * 52);

            if (this.shuffledCards.indexOf(num) != -1)
                continue;
            this.shuffledCards.push(num);
        }
    };

    this.drawCard = function()
    {
        var index = this.shuffledCards.shift();
        return this.cards[index];
    };

    this.initialise();
}