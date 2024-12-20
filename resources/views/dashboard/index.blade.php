<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>2. Dashboard Example</title>
        <script src="/js/axios.min.js"></script>
        <script src="/js/alpine.min.js" defer></script>
        <link rel="stylesheet" href="/css/bulma.css">
        <script>
            //
            // Server-Sent Events
            //
            const connection = new EventSource("/dashboard/tracking-data-stream");
            connection.onmessage = (payload) => {
                const eventData   = JSON.parse(payload.data);
                const sessionData = Alpine.store('session_data');
                const requestData = Alpine.store('request_data');

                eventData.forEach(data => {
                    if (typeof sessionData[data.session_id] === 'undefined') {
                        sessionData[data.session_id] = [];
                    }
                    if (sessionData[data.session_id].indexOf(data.request_id) === -1) {
                        sessionData[data.session_id].unshift(data.request_id);
                    }
                    requestData[data.request_id] = data;
                });
                Alpine.store('session_data', sessionData);
                Alpine.store('request_data', requestData);
            };
            document.addEventListener('alpine:init', () => {
                Alpine.store('session_data', {});
                Alpine.store('request_data', {});
            })
            function sendMessage(session_id, message_type) {
                axios.post('/dashboard/send-message-to-customer', { session_id, message_type }).then(function (response) {});
            }
            function secondsToHms(d) {
                d = Number(d);

                if (d === 0) {
                    return "0s";
                }

                var h = Math.floor(d / 3600);
                var m = Math.floor(d % 3600 / 60);
                var s = Math.floor(d % 3600 % 60);

                var hDisplay = h > 0 ? (h + "h ") : "";
                var mDisplay = m > 0 ? (m + "m ") : "";
                var sDisplay = s > 0 ? (s + "s") : "";
                return hDisplay + mDisplay + sDisplay;
            }
        </script>
    </head>
    <body class="p-4">
        <div class="grid">
            <div class="cell">
                <h1 class="title">Customer Live Dashboard</h1>
            </div>
        </div>
        <div class="fixed-grid">
            <div class="grid" x-data>
                <template x-for="(request_ids, session_id) in $store.session_data" :key="session_id">
                    <div class="cell" x-data="{ show: false }" x-init="$nextTick(() => { show = true })" x-show="show" x-transition x-transition.duration.1000ms>
                        <article class="panel">
                            <p class="panel-heading is-flex is-justify-content-space-between">
                                <span x-text="'Customer: ' + session_id.slice(0, 5)"></span>
                                <span>
                                    <button @click="() => sendMessage(session_id, 'discount_offer')" class="button is-small">Send 10% Discount Code</button>
                                    <button @click="() => sendMessage(session_id, 'support_message')" class="button is-small">Send Help Message</button>
                                </span>
                            </p>
                            <div class="panel-block is-active">
                                <table class="table is-bordered is-fullwidth">
                                    <thead>
                                        <tr>
                                            <th>Current Page</th>
                                            <th>Time on Page</th>
                                            <th>Total Time on Site</th>
                                            <th>Customer Active</th>
                                        </tr>
                                    </thead>
                                    <template x-for="(request_id, index) in request_ids" :key="request_id">
                                        <tbody>
                                            <template x-if="index === 0">
                                                <tr>
                                                    <td>
                                                        <span x-text="$store.request_data[request_id].visited_page"></span>
                                                    </td>
                                                    <td class="has-text-right">
                                                        <span x-text="secondsToHms($store.request_data[request_id].request_duration)"></span>
                                                    </td>
                                                    <td class="has-text-right">
                                                        <span x-text="secondsToHms($store.request_data[request_id].session_duration)"></span>
                                                    </td>
                                                    <td class="has-text-centered">
                                                        <template x-if="$store.request_data[request_id].completed_at !== null">
                                                            <span>❌</span>
                                                        </template>
                                                        <template x-if="$store.request_data[request_id].completed_at === null">
                                                            <span>✅</span>
                                                        </template>
                                                    </td>
                                                </tr>
                                            </template>
                                            <template x-if="index === 0">
                                                <tr class="has-background-white-ter">
                                                    <td colspan="4">Previous Requests</td>
                                                </tr>
                                            </template>
                                            <template x-if="index > 0">
                                                <tr>
                                                    <td>
                                                        <span x-text="$store.request_data[request_id].visited_page"></span>
                                                    </td>
                                                    <td class="has-text-right">
                                                        <span x-text="secondsToHms($store.request_data[request_id].request_duration)"></span>
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </template>
                                </table>
                            </div>
                        </article>
                    </div>
                </template>
            </div>
        </div>
    </body>
</html>
