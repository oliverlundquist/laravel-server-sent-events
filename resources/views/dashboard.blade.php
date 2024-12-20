<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dashboard</title>
        <script>
            // when data is received
            const sseDataCallback = (payload) => {
                const event     = JSON.parse(payload.data);
                const data      = JSON.parse(event.payload);
                const innerText = data.session_id + ": " + data.page + (event.inactive_at !== null ? ' time spent: ' + (event.inactive_at - event.active_at) : "");

                let list     = document.getElementById(data.session_id);
                let listItem = document.getElementById(event.ulid);

                if (list === null) {
                    list = document.createElement("ul");
                    list.setAttribute("id", data.session_id);
                    document.getElementById('dashboard').appendChild(list);
                }
                if (listItem === null) {
                    listItem = document.createElement("li");
                    listItem.setAttribute("id", event.ulid);
                    list.appendChild(listItem);
                }
                listItem.innerText = innerText;
            }

            // when an error has occured
            // const sseErrorCallback = (error) => {
                // document.getElementById('sse-error-container').innerHTML = "Connection got interrupted";
            // }

            // when there has been changed to the connection
            // save reference to connection if you need to perform some actions explicitly
            let connection = null;
            const sseConnectionCallback = (connection) => {
                connection = connection;
            }
        </script>
        <x-sse url="/stream" />
    </head>
    <body>
        <div id="dashboard"></div>
    </body>
</html>
