(function ($)
{
    $.fn.pluginName = function(options)
    {
         var settings = $.extend(
         {
            // These are the defaults.
            color: "#556b2f",
            backgroundColor: "white",
            action : "initialise"
        }, options );

        switch(settings.action)
        {
            case "initialise":
                console.log("initialising");
                break;
            case "open":
                console.log("opening");
                break;
        }
    };
}(jQuery));