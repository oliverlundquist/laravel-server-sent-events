<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>2. Dashboard Example (Product Page)</title>
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
                <h1 class="is-size-3">{{ $productName }}</h1>
                <div>
                    5 engaging product descriptions for "Swimming Feet" (black, waterproof):

                    1. Attention-grabbing opening: Unleash your inner mermaid with our revolutionary Swimming Feet - the ultimate water-conquering accessory!

                    Key features/benefits:
                    • Waterproof design keeps feet dry and comfortable
                    • Streamlined shape for effortless propulsion through the water
                    • Durable, flexible construction for all-day wear
                    • Sleek black color matches any swimwear

                    Benefit paragraph: Say goodbye to clunky flippers and hello to the freedom of natural, fluid movement. Our Swimming Feet harness the power of your own legs, allowing you to glide through the water with unparalleled speed and grace. Experience the thrill of swimming like a pro, whether you're racing laps or exploring the deep blue.

                    Who it's for/how it improves life: Perfect for swimmers, snorkelers, and water sports enthusiasts of all skill levels, our Swimming Feet transform your aquatic adventures. Unlock a new level of aquatic agility and confidence, making every splash, dive, and freestyle stroke more exhilarating than the last.

                    2. Attention-grabbing opening: Conquer the currents and make a splash with our revolutionary Swimming Feet!

                    Key features/benefits:
                    • Waterproof, durable construction for long-lasting use
                    • Streamlined design delivers effortless propulsion
                    • Flexible, comfortable fit for all-day wear
                    • Sleek black color complements any swimwear

                    Benefit paragraph: Imagine swimming with the grace and power of a dolphin. Our Swimming Feet allow you to do just that, transforming your aquatic experience from mundane to magnificent. Say goodbye to clunky flippers and hello to the freedom of natural, fluid movement as you glide through the water.

                    Who it's for/how it improves life: Whether you're an avid swimmer, a passionate snorkeler, or a water sports enthusiast, our Swimming Feet will elevate your aquatic adventures to new heights. Experience the thrill of swimming with unparalleled speed and control, unlocking a new level of confidence and excitement with every splash.

                    3. Attention-grabbing opening: Unlock your inner mermaid with the game-changing Swimming Feet!

                    Key features/benefits:
                    • Waterproof design keeps feet dry and comfortable
                    • Streamlined shape for effortless, high-speed propulsion
                    • Durable, flexible construction for long-lasting use
                    • Sleek black color complements any swimwear

                    Benefit paragraph: Imagine swimming with the grace and agility of a dolphin. Our revolutionary Swimming Feet make this dream a reality, allowing you to conquer the water with unparalleled speed and control. Say farewell to clunky flippers and hello to the freedom of natural, fluid movement as you glide through the waves.

                    Who it's for/how it improves life: Whether you're an avid swimmer, a passionate snorkeler, or a water sports enthusiast, our Swimming Feet will transform your aquatic experiences. Unlock a new level of confidence and excitement with every splash, as you explore the depths or race through the currents with unmatched power and precision.
                </div>
            </div>
        </div>
        <div class="grid">
            @if ($productName !== 'Product 1')
            <div class="cell">
                <h1 class="is-size-3">Product 1</h1>
                <a class="button is-primary" href="/products/1">View Product</a>
            </div>
            @endif
            @if ($productName !== 'Product 2')
            <div class="cell">
                <h1 class="is-size-3">Product 2</h1>
                <a class="button is-primary" href="/products/2">View Product</a>
            </div>
            @endif
            @if ($productName !== 'Product 3')
            <div class="cell">
                <h1 class="is-size-3">Product 3</h1>
                <a class="button is-primary" href="/products/3">View Product</a>
            </div>
            @endif
        </div>
    </body>
</html>
