function CSSRule(property, value)
{
    this.property = property;
    this.value = value;
}

function CSSRuleBlock()
{
    this.selectors = []; // CSSSelectorChain[]
    this.rules = []; // CSSRule[]

    this.addSelector = function(selectorChainElement) { this.selectors.push(selectorChainElement); }
    this.addRule = function(rule) { this.rules.push(rule); }
}

function CSSSelector(selector)
{
    this.selector = selector;
    this.getText = function()
    {
        return this.selector;
    };
}

function CSSSelectorChain(selector)
{
    this.next = null;
    this.selector = selector;
    this.chainType = '';

    this.getText = function()
    {
        if (this.next == null)
            return this.selector.getText();

        var chainText = '';
        switch(this.chainType)
        {
            case CSSParserConstants.CHAIN_TYPE_AND:
                chainText = ' and has ';
                break;
            case CSSParserConstants.CHAIN_TYPE_CHILDREN:
                chainText = ' is a child of ';
                break;
            case CSSParserConstants.CHAIN_TYPE_CONTAINS:
                chainText = ' is contained by ';
                break;
        }

        return this.next.getText() + chainText + this.selector.getText();
    };
}

CSSParserConstants =
{
    CHAIN_TYPE_AND : '',
    CHAIN_TYPE_CHILDREN : '>',
    CHAIN_TYPE_CONTAINS : ' '
};