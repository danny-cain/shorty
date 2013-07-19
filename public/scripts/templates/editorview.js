function TemplateEditorView(templateEditor, settings)
{
    this.elementToolbox = $('<div></div>');
    this.templateView = $('<div></div>');
    this.currentPathView = $('<div>' + "/" + '</div>');
    this.editor = templateEditor;
    this.nodeEditor = $('<div></div>');

    this.nodeEditor.css('position', 'fixed');
    this.nodeEditor.css('width', '50%');
    this.nodeEditor.css('height', '50%');
    this.nodeEditor.css('left', '25%');
    this.nodeEditor.css('top', '25%');
    this.nodeEditor.css('border', '1px solid black');
    this.nodeEditor.css('backgroundColor', 'white');
    this.nodeEditor.hide();
    $('body').append(this.nodeEditor);

    if (settings.hasOwnProperty("toolboxID"))
        this.elementToolbox.attr('id', settings.toolboxID);

    if (settings.hasOwnProperty('editorID'))
        this.templateView.attr('id', settings.editorID);

    if (settings.hasOwnProperty('pathID'))
        this.currentPathView.attr('id', settings.pathID);

    this.deleteNode = function(path)
    {
        this.editor.deleteNode(path);
    };

    this.editNode = function(path)
    {
        var self = this;
        var editor = this.nodeEditor;
        var node = this.editor.getChildByPath(path);
        if (node == null)
            return;

        var def = this.editor.getElementDefinitionByClassName(node.className);

        this.nodeEditor.show();
        this.nodeEditor.empty();

        this.nodeEditor.append($('<h1>' + path + '</h1>'));
        this.nodeEditor.attr('data-path', path);

        var index = 0;
        // display parameter editors
        for (var name in def.parameters)
        {
            if (!def.parameters.hasOwnProperty(name))
                continue;

            var type = def.parameters[name];

            var value = null;

            if (node.parameters.length > index)
                value = node.parameters[index];


            var element = $('<div></div>');
            var caption = $('<span style="display: inline-block; width: 25%;"></span>');
            var input = $();

            caption.text(name);

            switch(type)
            {
                case "string":
                    if ((typeof value) != "string")
                        value = "";
                    input = $('<input type="text" value="" />');
                    input.val(value);
                    break;
                case "array":
                    if ((typeof value) != "object" || value == null)
                        value = [];

                    input = $('<textarea></textarea>');
                    input.val(value.join("\r\n"));
                    break;
            }

            input.addClass('paramInput');
            input.css('display', 'inline-block');
            input.css('width', '50%');
            input.attr('data-index', index);

            element.append(caption).append(input);
            self.nodeEditor.append(element);

            index ++;
        }

        var saveButton = $('<button>Save</button>');
        var cancelButton = $('<button>Cancel</button>');

        saveButton.click(function()
        {
            var inputs = $('.paramInput', self.nodeEditor);

            index = 0;
            for (var name in def.parameters)
            {
                if (!def.parameters.hasOwnProperty(name))
                    continue;

                var filter = '[data-index="' + index + '"]';
                var type = def.parameters[name];
                var input = inputs.filter(filter);

                var val = input.val();
                switch(type)
                {
                    case "array":
                        val = val.split("\r\n");
                        break;
                }
                node.parameters[index] = val;

                index ++;
            }
            self.nodeEditor.hide();
        });

        cancelButton.click(function()
        {
            self.nodeEditor.hide();
        });

        this.nodeEditor.append(saveButton);
        this.nodeEditor.append(cancelButton);
    };

    this.updateToolbox = function(elements)
    {
        var toolbox = this.elementToolbox;
        var self = this;

        toolbox.empty();
        for (var index in elements)
        {
            if (!elements.hasOwnProperty(index))
                continue;

            var element = elements[index];
            var elementSelector = $('<div data-class="' + element.className + '">' + element.friendlyName + '</div>');
            elementSelector.click(function()
            {
                self.editor.selectElement($(this).attr('data-class'));
            })

            toolbox.append(elementSelector);
        }
    };

    this.updateTemplateView = function(template)
    {
        var rootNode = this.getNode("/", "ROOT", true);
        var editor = this.templateView;

        editor.empty();
        editor.append(rootNode);
        this._displayChildren(rootNode.children('.children'), "/", template.elements);
    };

    this.updatePath = function(path)
    {
        this.currentPathView.text(path);
    };

    this._displayChildren = function(containerNode, currentPath, children)
    {
        for(var i in children)
        {
            if (!children.hasOwnProperty(i))
                continue;
            var def = this.editor.getElementDefinitionByClassName(children[i].className);

            children[i].path = currentPath + i;
            var node = this.getNode(children[i].path, def.friendlyName, def.hasChildren);
            containerNode.append(node);
            this._displayChildren(node.children('.children'), children[i].path + "/", children[i].children);
        }
    };

    this.toggleChildrenView = function(nodeID)
    {
        var node = $('[data-node="' + nodeID + '"]');

        node.children('.children').toggle();
    };

    this.getNode = function(nodeID, caption, hasChildren)
    {
        var nodeParts = nodeID.split("/");
        var nodeIndex = nodeParts.pop();

        var node = $('<div data-node="' + nodeID + '"></div>');
        var self = this;

        if (hasChildren)
        {
            var children = $('<div class="toggleChildren">[ + ]</div>');

            node.addClass('hasChildren');
            node.append(children);

            children.click(function()
            {
                self.toggleChildrenView($(this).parent().attr('data-node'));
            });
        }

        node.addClass('templateNode');

        var captionNode = $('<div class="caption">' + caption + '</div>');
        node.append(captionNode);

        captionNode.click(function()
        {
            self.editor.selectTemplateElement($(this).parent().attr('data-node'));
        });

        if (nodeID != "/")
        {
            var editNode = $('<div class="edit">[ edit ]</div>');
            node.append(editNode);

            editNode.click(function()
            {
                self.editNode($(this).parent().attr('data-node'));
            });

            var deleteNode = $('<div class="delete">[ delete ]</div>');
            node.append(deleteNode);

            deleteNode.click(function()
            {
                self.deleteNode($(this).parent().attr('data-node'));
            });
        }

        if (nodeID != "/" && nodeIndex > 0)
        {
            var moveNodeUp = $('<div class="move">[ move up ]</div>');
            node.append(moveNodeUp);

            moveNodeUp.click(function()
            {
                self.editor.moveNodeUp($(this).parent().attr('data-node'));
            });
        }

        if (hasChildren)
        {
            var style = 'display: none;';

            if (this.editor.currentNodeID.substr(0, nodeID.length) == nodeID)
                style = 'display: block; ';

            node.append('<div style="' + style + ' " class="children"></div>');
        }

        return node;
    };

    templateEditor.registerView(this);
    this.updateToolbox(templateEditor.elements);
    this.updateTemplateView(templateEditor.template);
    this.updatePath(templateEditor.currentNodeID);

    $(settings.container).append(this.currentPathView);
    $(settings.container).append(this.elementToolbox);
    $(settings.container).append(this.templateView);
}