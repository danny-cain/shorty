function Template()
{
    this.elements = [];
    this.addElement = function(element) { this.elements.push(element); }

    this.moveElementUp = function(path)
    {
        var elementArray = this.elements;
        var segments = path.split("/");
        var targetIndex = segments.pop();

        segments.shift();

        var parentElement = null;

        if (targetIndex == 0)
            return;

        while (segments.length > 0)
        {
            var thisIndex = segments.shift();

            parentElement = elementArray[thisIndex];
            elementArray = parentElement.children;
        }

        var temp = elementArray[targetIndex];

        elementArray[targetIndex] = elementArray[targetIndex - 1];
        elementArray[targetIndex - 1] = temp;

        if (parentElement == null)
        {
            this.elements = elementArray;
        }
        else
        {
            parentElement.children = elementArray;
        }
    };

    this.removeElement = function(path)
    {
        var elementArray = this.elements;
        var segments = path.split("/");
        var targetIndex = segments.pop();

        segments.shift();

        var parentElement = null;

        while (segments.length > 0)
        {
            var thisIndex = segments.shift();
            parentElement = elementArray[thisIndex];
            elementArray = parentElement.children;
        }

        if (parentElement == null)
            return;

        elementArray.splice(targetIndex, 1);
        parentElement.children = elementArray;

    };

    this.getForJSON = function()
    {
        var elements = [];

        for (var i in this.elements)
        {
            if (!this.elements.hasOwnProperty(i))
                continue;

            elements.push(this.elements[i].getForJson());
        }

        return elements;
    }
}

function Element(className, friendlyName, parameters, hasChildren)
{
    this.className = className;
    this.parameters = parameters;
    this.friendlyName = friendlyName;
    this.hasChildren = hasChildren;
}

function TemplateElement(className, parameters, children)
{
    this.className = className;
    this.parameters = parameters;
    this.children = children;
    this.path = null;

    this.addChild = function(child) { this.children.push(child); };

    this.getForJson = function()
    {
        var ret =
        {
            "class" : "",
            "params" : [],
            "children" : []
        };

        ret.class = this.className;
        ret.params = this.parameters;
        for (var i in this.children)
        {
            if (!this.children.hasOwnProperty(i))
                continue;

            ret.children.push(this.children[i].getForJson());
        }

        return ret;
    }
}