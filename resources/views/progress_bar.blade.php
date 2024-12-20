<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>1. Progress Bar Example</title>
        <script src="/js/axios.min.js"></script>
        <link rel="stylesheet" href="/css/bulma.css">
        <script>
            //
            // Short Polling
            //
            let requestInFlight   = false;
            let progressCompleted = false;
            let shortPollingTimer = setInterval(() => {
                if (requestInFlight) {
                    return;
                }
                if (progressCompleted) {
                    clearInterval(shortPollingTimer);
                }
                requestInFlight = true;
                axios.get('/sp-progress-bar/{{ $requestId }}').then(function(result) {
                    const progress = result.data;
                    document.getElementById("sp-progress-bar").setAttribute('value', progress);
                    document.getElementById("sp-progress-text").innerText = progress;
                    requestInFlight = false;
                    if (progress === 100) {
                        progressCompleted = true;
                    }
                });
            }, 1000);

            //
            // Server-Sent Events
            //
            const connection = new EventSource("/sse-progress-bar/{{ $requestId }}");
            connection.onmessage = (payload) => {
                const progress = parseInt(payload.data, 10);
                document.getElementById("sse-progress-bar").setAttribute("value", progress);
                document.getElementById("sse-progress-text").innerText = progress;
                if (progress === 100) {
                    connection.close();
                }
            };
        </script>
    </head>
    <body>
        <div class="box">
            <h1 class="is-size-3">Server-Sent Events Progress Bar: <span id="sse-progress-text">0</span>%</h1>
            <progress id="sse-progress-bar" class="progress is-success is-large" value="0" max="100" />
        </div>
        <div class="box">
            <h1 class="is-size-3">Short Polling Progress Bar: <span id="sp-progress-text">0</span>%</h1>
            <progress id="sp-progress-bar" class="progress is-success is-large" value="0" max="100" />
        </div>
    </body>
</html>
