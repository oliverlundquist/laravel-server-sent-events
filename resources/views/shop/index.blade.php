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
            const pingDataCallback = (customerMessage) => {
                if (customerMessage === '') {
                    return;
                }
                const customerMessages = Alpine.store('customer_messages');
                customerMessages.unshift(customerMessage);
                Alpine.store('customer_messages', customerMessages);
            }
            document.addEventListener('alpine:init', () => {
                Alpine.store('customer_messages', []);
            })
            function deleteMessage(index) {
                const customerMessages = Alpine.store('customer_messages');
                customerMessages.splice(index, 1);
                Alpine.store('customer_messages', customerMessages);
            }
        </script>
        <x-ping sessionId="{{ $sessionId }}" requestId="{{ $requestId }}" />
    </head>
    <body class="px-4">
        <div x-data>
            <template x-for="(message, index) in $store.customer_messages" :key="index">
                <article class="message is-primary">
                    <div class="message-header">
                        <p x-text="message.message_type === 'discount_offer' ? 'Discount Offer' : 'Support Message'"></p>
                        <button @click="() => deleteMessage(index)" class="delete"></button>
                    </div>
                    <div x-text="message.message_text" class="message-body"></div>
                </article>
            </template>
        </div>
        <div class="grid">
            <div class="cell">
                <h1 class="is-size-3">Product 1</h1>
                <a class="button is-primary" href="/products/1">View Product</a>
            </div>
            <div class="cell">
                <h1 class="is-size-3">Product 2</h1>
                <a class="button is-primary" href="/products/2">View Product</a>
            </div>
            <div class="cell">
                <h1 class="is-size-3">Product 3</h1>
                <a class="button is-primary" href="/products/3">View Product</a>
            </div>
        </div>
    </body>
</html>
