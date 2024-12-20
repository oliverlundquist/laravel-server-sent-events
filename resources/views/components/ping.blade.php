@props(['sessionId', 'requestId'])

<script>
    (function () {
        const defaultCallback = (response) => { console.log(response); }
        const dataCallback    = typeof pingDataCallback !== 'undefined' ? pingDataCallback : defaultCallback;

        let requestInFlight   = false;
        let progressCompleted = false;
        const pingTimer = setInterval(() => {
            if (requestInFlight) {
                return;
            }
            if (progressCompleted) {
                clearInterval(shortPollingTimer);
            }
            requestInFlight = true;
            axios.post('/ping', { sessionId: "{{ $sessionId }}", requestId: "{{ $requestId }}" }).then(function(result) {
                dataCallback(result.data);
                requestInFlight = false;
            });
        }, 1000);
    })()
</script>
