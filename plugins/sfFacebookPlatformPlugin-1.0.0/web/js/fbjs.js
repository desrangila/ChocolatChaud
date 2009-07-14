function fb_ajax_updater(element_id, url, options)
{
    var ajax = new Ajax();

    if (isNaN(options.type)) {
        options.type = Ajax.RAW;
    }

    ajax.responseType = options.type;
    ajax.ondone = function(data)
    {
        switch (options.type)
        {
            case Ajax.FBML:
                document.getElementById(element_id).setInnerFBML(data);
              break;
            case Ajax.RAW:
            default:
                document.getElementById(element_id).setTextValue(data);
              break;
        }
        if (typeof options.onComplete == 'function') {
            options.onComplete();
        }
    };

    if (typeof options.onLoading == 'function') {
        options.onLoading();
    }
    ajax.post(url);
}
