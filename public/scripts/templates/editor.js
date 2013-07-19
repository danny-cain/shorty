TemplateEditor = function()
{
    this.getTemplateAsJSON = function()
    {
        return JSON.stringify(this.template.getForJSON());
    };
    this.views = [];
    this.registerView = function(view)
    {
        this.views.push(view);
    };
    this.currentNodeID = "/";
    this.template = new Template();
    this.elements = {};
    this.addElement = function(element)
    {
        this.elements[element.className] = element;
    };
    this.getElementDefinitionByClassName = function(className)
    {
        return this.elements[className];
    };
    this.deleteNode = function(path)
    {
        this.template.removeElement(path);
        this.updateTemplateView();
    };
    this.moveNodeUp = function(path)
    {
        this.template.moveElementUp(path);
        this.updateTemplateView();
    };

    this.selectElement = function(className) // called by the toolbox when an element is selected
    {
        var newElement = new TemplateElement(className, [], []);

        if (this.currentNodeID == "/")
        {
            // add to root
            this.template.addElement(newElement);
        }
        else
        {
            // add to tree
            var parent = this.getChildByPath(this.currentNodeID);
            if (parent == null)
                return;

            parent.children.push(newElement);
        }
        this.updateTemplateView();
    };
    this.selectTemplateElement = function(nodeID) // called by the editor when a node is clicked
    {
        this.currentNodeID = nodeID;
        for (var i in this.views)
        {
            if (!this.views.hasOwnProperty(i))
                continue;

            this.views[i].updatePath(nodeID);
        }
    };
    this.updateToolbox = function()
    {
        for (var i in this.views)
        {
            if (!this.views.hasOwnProperty(i))
                continue;

            this.views[i].updateToolbox(this.elements);
        }
    };
    this.updateTemplateView = function()
    {
        for (var i in this.views)
        {
            if (!this.views.hasOwnProperty(i))
                continue;

            this.views[i].updateTemplateView(this.template);
        }
    };
    this.getChildByPath = function(path)
    {
        var children = this.template.elements;
        var segments = path.split("/");
        var ret = null;

        if (segments[0] == "")
            segments.shift();

        while (segments.length > 0)
        {
            if (children == undefined)
                return null;

            var child = segments.shift();

            if (!children.hasOwnProperty(child))
                return null;

            ret = children[child];
            if (ret == undefined)
                return null;

            children = ret.children;
        }

        return ret;
    };
};