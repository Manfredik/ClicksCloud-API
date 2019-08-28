self.addEventListener("connect", function (workerEvent) {
    workerEvent.source.addEventListener("message", function (messageEvent) {
        if (messageEvent.data.command === 'fetch') {
            var calbackId = messageEvent.data.calbackId;
            var options = {
                method: messageEvent.data.options.method || 'GET',
                mode: messageEvent.data.options.mode || 'cors',
                redirect: messageEvent.data.options.redirect || 'error',
            };
            if (messageEvent.data.options.headers) {
                options.headers = new Headers();
                for (var i in messageEvent.data.options.headers) {
                    options.headers.append(i, messageEvent.data.options.headers[i]);
                }
            }
            fetch(new Request(messageEvent.data.url, options))
                .then(function (response) {
                    return messageEvent.data.options.type === 'json' ? response.json() : response.text();
                })
                .then(function (data) {
                    workerEvent.source.postMessage({
                        response: data,
                        type: 'success',
                        callbackId: calbackId
                    });
                    workerEvent.source.close();
                })
                .catch(function (error) {
                    workerEvent.source.postMessage({
                        response: null,
                        type: 'error',
                        callbackId: calbackId
                    });
                });
        }
    }, false);
    workerEvent.source.start();
});