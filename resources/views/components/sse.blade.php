@props(['url'])

<script>
    (function () {
        let connection = null;

        const defaultCallback    = (payload) => { console.log(payload); }
        const connectionCallback = typeof sseConnectionCallback !== 'undefined' ? sseConnectionCallback : defaultCallback;
        const dataCallback       = typeof sseDataCallback !== 'undefined' ? sseDataCallback : defaultCallback;
        const errorCallback      = typeof sseErrorCallback !== 'undefined' ? sseErrorCallback : defaultCallback;

        function boot() {
            document.addEventListener("visibilitychange", () => {
                if (document.visibilityState === "visible") {
                    connect();
                } else {
                    disconnect();
                }
            });
            connect();
        }

        function connect() {
            if (connection === null) {
                connection = new EventSource("{{ $url }}");
                connection.onmessage = message;
                connection.onerror   = error;
            }
            connectionCallback(connection);
        }

        function disconnect() {
            if (connection !== null) {
                connection.close();
                connection = null;
            }
            connectionCallback(connection);
        }

        function message(message) {
            dataCallback(message);
        }

        function error(exception) {
            errorCallback(exception);
        }

        boot();
    })()
</script>
