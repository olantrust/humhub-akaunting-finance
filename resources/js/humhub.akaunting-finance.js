humhub.module('akaunting-finance', function(module, require, $) {

    var client = require('client');
    var status = require('ui.status');
    var loader = require('ui.loader');

    var init = function() {
        console.log('akaunting finance module activated');
    };

    var hello = function() {
        alert(module.text('hello')+' - '+module.config.username)
    };

    var testApi = function (evt) {
        client.get(evt).then(function(response) {
            loader.reset(evt.$target);

            if(response)
            {
                response = JSON.parse(response.response);

                // console.log(response);

                if(response.status == "ok")
                {
                    status.success(response.response);
                }
                else
                {
                    status.error("There is some error, please try again later.");
                }
            }
            else
            {
                status.error("There is some error, please try again later.");
            }

        }).catch(function(err) {
            module.log.error(err, true);
        });
    };

    var linkSpace = function (evt) {
        client.get(evt).then(function(response) {
            loader.reset(evt.$target);

            response = JSON.parse(response.response);

            // console.log(response);

            // if(response.status == "ok")
            // {
                status.success(response.response);
            // }
            // else
            // {
            //     status.error("There is some error, please try again later.");
            // }

        }).catch(function(err) {
            module.log.error(err, true);
        });
    };

    module.export({
        //uncomment the following line in order to call the init() function also for each pjax call
        //initOnPjaxLoad: true,
        init: init,
        hello: hello,
        testApi: testApi,
        linkSpace: linkSpace
    });
});