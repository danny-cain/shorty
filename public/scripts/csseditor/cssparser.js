function CSSParser()
{
    this.constants =
    {
        STATE_SELECTORS : 0,
        STATE_RULES : 1
    };

    this.state = this.constants.STATE_SELECTORS;

    this.getRuleFromString = function(text)
    {
        text = text.trim();
        if (text.charAt(text.length - 1) == ';')
            text = text.substr(0, text.length - 1).trim();

        var index = text.indexOf(':');
        var property = text.substr(0, index).trim();
        var value = text.substr(index + 1).trim();

        return new CSSRule(property, value);
    };

    this.selectorFactory = function(selector)
    {
        return new CSSSelector(selector);
    };

    this.getSelectorChainFromWord = function(word)
    {
        var rootSelector = null;
        var currentSelector = null;

        var splitCharacters = ['.', ':', '#'];
        while (word.length > 0)
        {
            var pos = word.length;

            for (var i = 0; i < splitCharacters.length; i ++)
            {
                var thisPos = word.indexOf(splitCharacters[i]);

                if (thisPos > 0 && thisPos < pos)
                    pos = thisPos;
            }

            var selectorText;

            if (pos == word.length)
            {
                selectorText = word;
                word = '';
            }
            else
            {
                selectorText = word.substr(0, pos - 1);
                word = word.substr(pos);
            }

            var selector = this.selectorFactory(selectorText);

            if (rootSelector == null)
            {
                rootSelector = new CSSSelectorChain(selector);
                rootSelector.chainType = CSSParserConstants.CHAIN_TYPE_AND;
                currentSelector = rootSelector;
            }
            else
            {
                var temp = new CSSSelectorChain(selector);
                temp.chainType = CSSParserConstants.CHAIN_TYPE_AND;
                currentSelector.next = temp;
                currentSelector = temp;
            }
        }

        return rootSelector;
    };

    this.getSelectorChainFromSpacedString = function(text)
    {
        var words = text.split(' ');
        var ret = null;
        var currentNode = null;

        while(words.length > 0)
        {
            var newNode = this.getSelectorChainFromWord(words.shift());
            if (newNode == null)
                continue;

            var nextNodeJoin = CSSParserConstants.CHAIN_TYPE_CONTAINS;
            if (words.length > 0)
            {
                switch(words[0])
                {
                    case '>':
                        nextNodeJoin = CSSParserConstants.CHAIN_TYPE_CHILDREN;
                        words.shift();
                        break;
                }
            }
            newNode.chainType = nextNodeJoin;

            if (ret == null)
            {
                ret = newNode;
                currentNode = ret;
            }
            else
            {
                currentNode.next = newNode;
                currentNode = newNode;
            }
        }

        return ret;
    };

    this.parse = function(css)
    {
        alert('This needs to be created in PHP and accessed via AJAX - will be easier to debug as this version hangs the browser');
        return [];

        var buffer = '';
        var ruleBlocks = [];
        var ruleBlock = new CSSRuleBlock();

        for (var i = 0; i < css.length; i ++)
        {
            var character = css.charAt(i);

            switch(this.state)
            {
                case this.constants.STATE_SELECTORS:
                    switch(character)
                    {
                        case ',':
                            ruleBlock.addSelector(this.getSelectorChainFromSpacedString(buffer));
                            buffer = '';
                            break;
                        case '{':
                            ruleBlock.addSelector(this.getSelectorChainFromSpacedString(buffer));
                            buffer = '';
                            this.state = this.constants.STATE_RULES;
                            break;
                        default:
                            buffer = buffer + character;
                    }
                    break;
                case this.constants.STATE_RULES:
                    switch(character)
                    {
                        case ';':
                            ruleBlock.addRule(this.getRuleFromString(buffer));
                            buffer = '';
                            break;
                        case '}':
                            ruleBlock.addRule(this.getRuleFromString(buffer));
                            buffer = '';
                            this.state = this.constants.STATE_SELECTORS;

                            ruleBlocks.push(ruleBlock);
                            ruleBlock = new CSSRuleBlock();
                            break;
                        default:
                            buffer = buffer + character;
                    }
                    break;
            }
        }

        return ruleBlocks;
    };
}